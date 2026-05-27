<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\DonationRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index($donationRequestId)
    {
        $donationRequest = DonationRequest::findOrFail($donationRequestId);
        
        // Ensure user is either sender or receiver
        if ($donationRequest->from_user_id !== auth()->id() && $donationRequest->to_user_id !== auth()->id()) {
            abort(403);
        }

        // Mark messages as read for the logged in user
        Message::where('donation_request_id', $donationRequestId)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where('donation_request_id', $donationRequestId)
            ->orderBy('created_at', 'asc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'messages' => $messages,
                'chat_status' => $donationRequest->status
            ]);
        }

        return view('chat.index', compact('donationRequest', 'messages'));
    }

    public function store(Request $request, $donationRequestId)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        $donationRequest = DonationRequest::findOrFail($donationRequestId);
        
        $receiverId = $donationRequest->from_user_id === auth()->id() ? $donationRequest->to_user_id : $donationRequest->from_user_id;

        $message = Message::create([
            'donation_request_id' => $donationRequestId,
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'body' => $request->body,
            'read_at' => null
        ]);

        broadcast(new MessageSent($message, $donationRequestId))->toOthers();

        // Return message as JSON for AJAX support if requested, else back()
        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => $message]);
        }

        return back();
    }
}
