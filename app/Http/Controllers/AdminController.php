<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_donors' => User::where('role', 'user')->where('availability_status', 'available')->count(),
            'total_requests' => \App\Models\BloodRequest::count(),
            'active_requests' => \App\Models\BloodRequest::where('status', 'Pending')->count(),
            'emergency_requests' => \App\Models\BloodRequest::where('urgency_level', 'High')->count(),
        ];
        $recent_donors = User::where('role', 'user')->orderBy('created_at', 'desc')->take(5)->get();
        $recent_requests = \App\Models\BloodRequest::orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recent_donors', 'recent_requests'));
    }

    public function users(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleBan(User $user)
    {
        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'User has been banned.' : 'User has been unbanned.';
        return back()->with('status', $status);
    }

    public function rewards(Request $request)
    {
        // Reward manager: shows top users who haven't been banned.
        $query = User::where('role', 'user')
            ->where('points', '>', 0)
            ->where('is_banned', '!=', true);

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $donors = $query->orderBy('points', 'desc')->paginate(20);
        return view('admin.rewards', compact('donors'));
    }
}
