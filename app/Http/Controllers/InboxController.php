<?php

namespace App\Http\Controllers;

use App\Models\DonationRequest;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        // Pending requests sent TO the user
        $pendingRequests = DonationRequest::where('to_user_id', auth()->id())
            ->where('status', 'pending')
            ->with('fromUser')
            ->latest()
            ->get();

        // Active chats (Accepted requests involving the user, either as sender or receiver)
        $activeChats = DonationRequest::where(function ($query) {
                $query->where('to_user_id', auth()->id())
                      ->orWhere('from_user_id', auth()->id());
            })
            ->where('status', 'accepted')
            ->with(['fromUser', 'toUser'])
            ->latest()
            ->get();

        return view('inbox.index', compact('pendingRequests', 'activeChats'));
    }
}
