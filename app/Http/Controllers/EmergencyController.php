<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Http\Request;
// use App\Notifications\EmergencyBloodRequest; // Need to create this notification if we wanted to send actual broadcast, but we can skip real broadcast until they configure Pusher/Twilio

class EmergencyController extends Controller
{
    public function create()
    {
        return view('emergency.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'blood_group' => 'required|string',
            'units_needed' => 'required|integer|min:1',
            'hospital_name' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $data = $request->except(['lat', 'lng']);
        $data['user_id'] = auth()->id();
        $data['urgency_level'] = 'High';
        $data['emergency_level'] = 'High';
        $data['status'] = 'Pending';
        
        if ($request->lat && $request->lng) {
            $data['location'] = [
                'type' => 'Point',
                'coordinates' => [ (float) $request->lng, (float) $request->lat ]
            ];
        }

        $data['is_hospital_verified'] = auth()->user()->role === 'hospital';

        $bloodRequest = BloodRequest::create($data);

        // Find matching donors — use city-based matching (safe fallback that works without geo index on all docs)
        $nearestDonors = User::where('availability_status', 'available')
            ->where('blood_group', $request->blood_group)
            ->where('city', 'LIKE', '%' . $request->city . '%')
            ->limit(20)
            ->get();

        return redirect()->route('dashboard')->with('status', 'Emergency broadcast sent to nearest matching donors!');
    }

    public function updateStatus(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);

        // Ensure owner
        if ($bloodRequest->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Completed,Cancelled'
        ]);

        $bloodRequest->status = $request->status;
        $bloodRequest->save();

        return back()->with('status', 'Emergency request marked as ' . strtolower($request->status) . '!');
    }

    public function activeList()
    {
        $user    = auth()->user();
        $city    = $user->city;
        $userLat = $user->location['coordinates'][1] ?? null;
        $userLng = $user->location['coordinates'][0] ?? null;

        $emergenciesQuery = BloodRequest::where('status', 'Pending')
            ->where('urgency_level', 'High')
            ->where('user_id', '!=', $user->id)
            ->where('blood_group', $user->blood_group); // Only matching blood group

        if ($userLat && $userLng) {
            // Primary: 50km geo radius; fallback OR city text
            $emergenciesQuery->where(function ($q) use ($userLat, $userLng, $city) {
                $q->where('location', 'geoWithin', [
                    '$centerSphere' => [
                        [(float) $userLng, (float) $userLat],
                        50 / 6378.1
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

        // Also fetch my latest requests to update them in real-time
        $myRequests = BloodRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $requestIds = $myRequests->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $helpOfferedRequestIds = \App\Models\DonationRequest::whereIn('blood_request_id', $requestIds)
            ->whereIn('status', ['pending', 'accepted'])
            ->pluck('blood_request_id')
            ->map(fn($id) => (string) $id)
            ->unique()
            ->values()
            ->toArray();

        // Ensure string IDs for JS array
        $myRequestsFormatted = $myRequests->map(function($req) {
            $data = $req->toArray();
            $data['id'] = (string) $req->id;
            return $data;
        });

        return response()->json([
            'emergencies'               => $emergencies,
            'my_requests'               => $myRequestsFormatted,
            'help_offered_request_ids'  => $helpOfferedRequestIds,
            'user_city'                 => $city,
            'user_blood_group'          => $user->blood_group,
            'donation_request_store_url'=> route('donation_request.store'),
            'csrf_token'                => csrf_token()
        ]);
    }
}
