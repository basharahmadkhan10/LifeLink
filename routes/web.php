<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    
    if ($user->role === 'hospital') {
        return redirect()->route('hospital.dashboard');
    }

    $stats = [
        'total_donors' => \App\Models\User::where('role', 'user')->where('availability_status', 'available')->count(),
        'active_requests' => \App\Models\BloodRequest::where('status', 'Pending')->count(),
        'my_requests' => \App\Models\BloodRequest::where('user_id', $user->id)->count(),
    ];

    $requests = \App\Models\BloodRequest::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(2)->get();

    // Fetch active emergencies matching user's blood group, near their location
    $city    = $user->city;
    $userLat = $user->location['coordinates'][1] ?? null;
    $userLng = $user->location['coordinates'][0] ?? null;

    $emergenciesQuery = \App\Models\BloodRequest::where('status', 'Pending')
        ->where('urgency_level', 'High')
        ->where('user_id', '!=', $user->id)
        ->where('blood_group', $user->blood_group); // Only matching blood group

    if ($userLat && $userLng) {
        // Primary: geolocation 50km radius; fallback OR city text match
        $emergenciesQuery->where(function ($q) use ($userLat, $userLng, $city) {
            $q->where('location', 'geoWithin', [
                '$centerSphere' => [
                    [(float) $userLng, (float) $userLat],
                    50 / 6378.1 // 50km radius in radians
                ]
            ]);
            if ($city) {
                $q->orWhere('city', 'LIKE', '%' . $city . '%');
            }
        });
    } elseif ($city) {
        $emergenciesQuery->where('city', 'LIKE', '%' . $city . '%');
    }

    $emergencies = $emergenciesQuery->orderBy('created_at', 'desc')->take(2)->get();

    // IDs of MY blood requests that have at least one help offer (pending or accepted)
    $requestIds = $requests->pluck('id')->map(fn($id) => (string) $id)->toArray();
    $helpOfferedRequestIds = \App\Models\DonationRequest::whereIn('blood_request_id', $requestIds)
        ->whereIn('status', ['pending', 'accepted'])
        ->pluck('blood_request_id')
        ->map(fn($id) => (string) $id)
        ->unique()
        ->values()
        ->toArray();

    return view('dashboard', compact('user', 'stats', 'requests', 'emergencies', 'helpOfferedRequestIds'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Hospital Routes
Route::middleware(['auth'])->prefix('hospital')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\HospitalController::class, 'dashboard'])->name('hospital.dashboard');
    Route::post('/inventory/update', [\App\Http\Controllers\HospitalController::class, 'updateInventory'])->name('hospital.inventory.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::patch('/users/{user}/ban', [\App\Http\Controllers\AdminController::class, 'toggleBan'])->name('admin.users.ban');
    Route::get('/rewards', [\App\Http\Controllers\AdminController::class, 'rewards'])->name('admin.rewards');
});


// OTP (Accessible to guests for pre-registration)
Route::post('/otp/send', [\App\Http\Controllers\OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [\App\Http\Controllers\OtpController::class, 'verify'])->name('otp.verify');
Route::post('/otp/email/send', [\App\Http\Controllers\OtpController::class, 'sendEmail'])->name('otp.email.send');
Route::post('/otp/email/verify', [\App\Http\Controllers\OtpController::class, 'verifyEmail'])->name('otp.email.verify');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Email Verified Required Routes
    Route::middleware('verified')->group(function () {
        // Donor toggle
        Route::patch('/donor/toggle-availability', [\App\Http\Controllers\UserController::class, 'toggleAvailability'])->name('donor.toggle');

        // Secure Donor Directory
        Route::get('/donors', [\App\Http\Controllers\DonorController::class, 'index'])->name('donors.index');

        // Inbox
        Route::get('/inbox', [\App\Http\Controllers\InboxController::class, 'index'])->name('inbox.index');
        Route::get('/notifications/count', [\App\Http\Controllers\NotificationController::class, 'count'])->name('notifications.count');

        // Donation requests & chat
        Route::post('/donation-request', [\App\Http\Controllers\DonationRequestController::class, 'store'])->name('donation_request.store');
        Route::patch('/donation-request/{id}/respond', [\App\Http\Controllers\DonationRequestController::class, 'respond'])->name('donation_request.respond');
        Route::patch('/donation-requests/{donationRequest}/confirm', [\App\Http\Controllers\DonationRequestController::class, 'confirmReceived'])->name('donation_request.confirm_received');
        Route::delete('/donation-requests/{donationRequest}', [\App\Http\Controllers\DonationRequestController::class, 'destroy'])->name('donation_request.destroy');
        Route::get('/chat/{donationRequestId}', [\App\Http\Controllers\MessageController::class, 'index'])->name('chat.index');
        Route::post('/chat/{donationRequestId}', [\App\Http\Controllers\MessageController::class, 'store'])->name('chat.store');

        // Emergency
        Route::get('/emergency', [\App\Http\Controllers\EmergencyController::class, 'create'])->name('emergency.create');
        Route::post('/emergency', [\App\Http\Controllers\EmergencyController::class, 'store'])->name('emergency.store');
        Route::patch('/emergency/{id}/status', [\App\Http\Controllers\EmergencyController::class, 'updateStatus'])->name('emergency.status');
        Route::get('/emergencies/active', [\App\Http\Controllers\EmergencyController::class, 'activeList'])->name('emergencies.active');

        // Hospitals & blood stocks
        Route::get('/hospitals', [\App\Http\Controllers\HospitalController::class, 'index'])->name('hospitals.index');
        Route::resource('/hospital/stocks', \App\Http\Controllers\BloodStockController::class);
        Route::post('/hospital/donors', [\App\Http\Controllers\HospitalController::class, 'storeDonor'])->name('hospital.donors.store');
        Route::patch('/hospital/donors/{id}/toggle', [\App\Http\Controllers\HospitalController::class, 'toggleDonorStatus'])->name('hospital.donors.toggle');

        Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');
    });
});

require __DIR__.'/auth.php';
