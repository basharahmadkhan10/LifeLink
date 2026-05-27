<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')
            ->where('availability_status', 'available')
            ->where('is_banned', '!=', true)
            ->where('_id', '!=', auth()->id());

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        $donors = $query->orderBy('last_donated_at', 'asc')->paginate(12);

        // Append filters to pagination
        $donors->appends($request->all());

        return view('donors.index', compact('donors'));
    }
}
