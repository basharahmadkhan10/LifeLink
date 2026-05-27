<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonationRequest;
use App\Models\Message;

class NotificationController extends Controller
{
    public function count()
    {
        $userId = auth()->id();
        
        $unreadRequests = DonationRequest::where('to_user_id', $userId)->where('status', 'pending')->count();
        
        $activeRequestIds = DonationRequest::where(function ($query) use ($userId) {
                $query->where('to_user_id', $userId)
                      ->orWhere('from_user_id', $userId);
            })
            ->where('status', 'accepted')
            ->pluck('id');

        $unreadMessages = Message::where('receiver_id', $userId)
            ->whereIn('donation_request_id', $activeRequestIds)
            ->whereNull('read_at')
            ->count();
        
        \Log::info("Notification count debug for User {$userId}: unreadRequests = {$unreadRequests}, unreadMessages = {$unreadMessages}");
        
        return response()->json([
            'count' => $unreadRequests + $unreadMessages,
            'unread_requests' => $unreadRequests,
            'unread_messages' => $unreadMessages
        ]);
    }
}
