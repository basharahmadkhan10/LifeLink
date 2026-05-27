<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')
            ->where('points', '>', 0)
            ->where('is_banned', '!=', true);

        // City filter only (blood group removed — city-wise top performers are rewarded)
        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        // Optimized pagination for lakhs of users
        // Since points is indexed, orderBy('points', 'desc') is fast.
        $donors = $query->orderBy('points', 'desc')->paginate(20);

        // Append query strings to pagination links
        $donors->appends($request->all());

        // Get Top 3 globally (without pagination) for the Podium
        // If there are filters applied, podium reflects the filtered Top 3
        $podiumQuery = User::where('role', 'user')
            ->where('points', '>', 0)
            ->where('is_banned', '!=', true);
            
        if ($request->filled('city')) {
            $podiumQuery->where('city', 'LIKE', '%' . $request->city . '%');
        }
        
        $topThree = $podiumQuery->orderBy('points', 'desc')->take(3)->get();

        return view('leaderboard.index', compact('donors', 'topThree'));
    }
}
