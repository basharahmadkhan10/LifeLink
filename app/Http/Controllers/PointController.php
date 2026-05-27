<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PointController extends Controller
{
    public function leaderboard(Request $request)
    {
        // Default to current month; allow switching via ?month=YYYY-MM
        $monthParam = $request->input('month', now()->format('Y-m'));

        try {
            $start = Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth();
        } catch (\Exception $e) {
            $start = now()->startOfMonth();
            $monthParam = now()->format('Y-m');
        }
        $end = $start->copy()->endOfMonth();

        // Fetch all points in the selected month grouped by user
        $pointRows = Point::whereBetween('created_at', [$start, $end])->get();

        // Sum per user
        $totals = $pointRows->groupBy('user_id')->map(fn($rows) => $rows->sum('amount'));

        // Sort descending, take top 10
        $totals = $totals->sortDesc()->take(10);

        // Load user models for the top 10
        $userIds = $totals->keys()->toArray();
        $users   = User::whereIn('_id', $userIds)->get()->keyBy('_id');

        // Build ranked list
        $leaderboard = $totals->map(function ($pts, $uid) use ($users) {
            return [
                'user'   => $users[$uid] ?? null,
                'points' => $pts,
            ];
        })->values()->filter(fn($row) => $row['user'] !== null);

        // Month nav: prev/next
        $prevMonth = $start->copy()->subMonth()->format('Y-m');
        $nextMonth = $start->copy()->addMonth()->format('Y-m');
        $isCurrentMonth = $start->format('Y-m') === now()->format('Y-m');

        return view('leaderboard.index', compact(
            'leaderboard', 'monthParam', 'prevMonth', 'nextMonth', 'isCurrentMonth', 'start'
        ));
    }
}
