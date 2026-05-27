<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalize phone number to match OTP verification format
        $phone = preg_replace('/[^\d+]/', '', $request->phone);
        if (!str_starts_with($phone, '+')) {
            $phone = '+91' . $phone;
        }

        $request->merge(['phone' => $phone]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:user,hospital,admin'],
            'blood_group' => ['nullable', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'age' => ['nullable', 'integer', 'min:18', 'max:120'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ]);

        if (session('verified_email') !== $request->email) {
            throw ValidationException::withMessages([
                'email' => ['You must verify your email address before registering.'],
            ]);
        }

        if (session('verified_phone') !== $request->phone) {
            throw ValidationException::withMessages([
                'phone' => ['You must verify your phone number before registering.'],
            ]);
        }

        $location = null;
        if ($request->filled('lat') && $request->filled('lng')) {
            $location = [
                'type' => 'Point',
                'coordinates' => [(float) $request->lng, (float) $request->lat] // MongoDB format: [longitude, latitude]
            ];
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city' => $request->city,
            'address' => $request->address,
            'role' => $request->role,
            'blood_group' => $request->blood_group,
            'age' => $request->age,
            'gender' => $request->gender,
            'license_number' => $request->role === 'hospital' ? $request->license_number : null,
            'availability_status' => $request->role === 'user' ? 'available' : 'unavailable',
            'location' => $location,
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        // Clear session data
        session()->forget(['verified_email', 'verified_phone']);

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
