<?php

namespace App\Http\Controllers;

use App\Models\BloodInventory;
use App\Models\BloodRequest;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $city = $request->query('city', $user->city);

        // Fetch hospitals in the city
        $hospitalsQuery = \App\Models\User::where('role', 'hospital');
        if ($city) {
            $hospitalsQuery->where('city', 'LIKE', '%' . $city . '%');
        }
        
        $hospitals = $hospitalsQuery->paginate(12);

        // Fetch inventories for these hospitals
        $hospitalIds = $hospitals->pluck('id')->toArray();
        $inventories = \App\Models\BloodInventory::whereIn('hospital_id', $hospitalIds)
            ->get()
            ->groupBy('hospital_id');

        return view('hospitals.index', compact('hospitals', 'inventories', 'city'));
    }
    public function dashboard()
    {
        $hospital = auth()->user();

        // Ensure default inventory records exist for all blood groups
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        
        foreach ($bloodGroups as $group) {
            BloodInventory::firstOrCreate(
                ['hospital_id' => $hospital->id, 'blood_group' => $group],
                ['units' => 0, 'last_updated_at' => now()]
            );
        }

        $inventory = BloodInventory::where('hospital_id', $hospital->id)->get();
        
        // Active patient requests created by this hospital
        $activeRequests = BloodRequest::where('user_id', $hospital->id)
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Donors registered by this hospital
        $hospitalDonors = \App\Models\User::where('registered_by_hospital_id', $hospital->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hospital.dashboard', compact('hospital', 'inventory', 'activeRequests', 'hospitalDonors'));
    }

    public function storeDonor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'blood_group' => 'required|string',
            'gender' => 'required|string',
            'age' => 'required|integer|min:18|max:65',
            'city' => 'required|string|max:255',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'blood_group' => $request->blood_group,
            'gender' => $request->gender,
            'age' => $request->age,
            'city' => $request->city,
            'role' => 'user', // Normal donor role
            'password' => bcrypt(\Illuminate\Support\Str::random(16)), // Random password, login via OTP
            'registered_by_hospital_id' => auth()->id(),
            'availability_status' => 'available', // Active by default
        ]);

        return back()->with('status', 'Donor registered successfully!');
    }

    public function toggleDonorStatus(Request $request, $id)
    {
        $donor = \App\Models\User::where('_id', $id)
            ->where('registered_by_hospital_id', auth()->id())
            ->firstOrFail();

        $donor->availability_status = $donor->availability_status === 'available' ? 'unavailable' : 'available';
        $donor->save();

        return back()->with('status', 'Donor status updated.');
    }

    public function updateInventory(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|string',
            'action' => 'required|in:increment,decrement',
        ]);

        $inventory = BloodInventory::where('_id', $request->inventory_id)
            ->where('hospital_id', auth()->id())
            ->firstOrFail();

        if ($request->action === 'increment') {
            $inventory->units += 1;
        } else {
            if ($inventory->units > 0) {
                $inventory->units -= 1;
            }
        }
        $inventory->last_updated_at = now();
        $inventory->save();

        broadcast(new \App\Events\BloodInventoryUpdated($inventory->hospital_id, $inventory->blood_group, $inventory->units));

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'units' => $inventory->units]);
        }
        
        return back()->with('status', 'Inventory updated');
    }
}
