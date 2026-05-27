<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function toggleAvailability(Request $request)
    {
        $user = auth()->user();
        
        // 90-Day Cooldown Check
        if ($user->last_donated_at && $user->last_donated_at->diffInDays(now()) < 90) {
            $daysLeft = 90 - $user->last_donated_at->diffInDays(now());
            return response()->json([
                'status' => 'error',
                'message' => "You must wait {$daysLeft} more days before donating again for medical safety."
            ], 403);
        }

        $newStatus = $user->availability_status === 'available' ? 'unavailable' : 'available';
        $user->availability_status = $newStatus;
        $user->save();

        return response()->json([
            'status' => 'success',
            'availability_status' => $newStatus
        ]);
    }
}
