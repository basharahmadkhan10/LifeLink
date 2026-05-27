<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\DonationRequest;
use App\Models\Point;
use Illuminate\Http\Request;

class DonationRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|string',
        ]);

        $myId     = auth()->id();
        $targetId = $request->to_user_id;

        // 1. Redirect to existing accepted chat
        $existingAccepted = DonationRequest::where('status', 'accepted')
            ->where(function ($q) use ($myId, $targetId) {
                $q->where(fn($x) => $x->where('from_user_id', $myId)->where('to_user_id', $targetId))
                  ->orWhere(fn($x) => $x->where('from_user_id', $targetId)->where('to_user_id', $myId));
            })->first();

        if ($existingAccepted) {
            return redirect()->route('chat.index', $existingAccepted->id)
                ->with('status', 'Opened existing active chat room.');
        }

        // 2. Block duplicate pending
        $existingPending = DonationRequest::where('status', 'pending')
            ->where(function ($q) use ($myId, $targetId) {
                $q->where(fn($x) => $x->where('from_user_id', $myId)->where('to_user_id', $targetId))
                  ->orWhere(fn($x) => $x->where('from_user_id', $targetId)->where('to_user_id', $myId));
            })->first();

        if ($existingPending) {
            return redirect()->route('inbox.index')
                ->with('status', 'A request is already pending between you and this user.');
        }

        // 3. Find the specific blood request linked to target user
        $bloodRequest = BloodRequest::where('user_id', $targetId)
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->first();

        $donationType = ($bloodRequest && $bloodRequest->urgency_level === 'High')
            ? 'emergency'
            : 'normal';

        DonationRequest::create([
            'from_user_id'    => $myId,
            'to_user_id'      => $targetId,
            'blood_request_id'=> $bloodRequest ? (string) $bloodRequest->id : null,
            'status'          => 'pending',
            'donation_type'   => $donationType,
            'message'         => 'I would like to help. Please accept this request.',
        ]);

        return back()->with('status', 'Donation request sent successfully!');
    }

    public function respond(Request $request, $id)
    {
        $donationRequest = DonationRequest::findOrFail($id);

        if ($donationRequest->to_user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:accepted,declined']);

        $donationRequest->status = $request->status;
        $donationRequest->save();

        if ($request->status === 'accepted') {
            // Concurrency Fix: If this is linked to a BloodRequest, mark it Fulfilled and cancel competing offers
            if ($donationRequest->blood_request_id) {
                $bloodRequest = \App\Models\BloodRequest::find($donationRequest->blood_request_id);
                if ($bloodRequest && $bloodRequest->status === 'Pending') {
                    $bloodRequest->status = 'Completed';
                    $bloodRequest->save();

                    // Decline all other pending offers for this emergency to prevent duplicacy
                    DonationRequest::where('blood_request_id', $bloodRequest->id)
                        ->where('_id', '!=', $donationRequest->id)
                        ->where('status', 'pending')
                        ->update(['status' => 'declined']);
                }
            }

            return redirect()->route('chat.index', $donationRequest->id)
                ->with('status', 'Request accepted. Other pending offers for this emergency have been closed.');
        }

        return back()->with('status', 'Request declined.');
    }

    /**
     * Receiver confirms they received the blood.
     * Awards points to the donor (from_user_id).
     *   - Emergency donation : +150 pts
     *   - Normal donation    : +20  pts
     */
    public function confirmReceived(Request $request, DonationRequest $donationRequest)
    {
        $bloodReceiverId = $donationRequest->donation_type === 'emergency' 
            ? $donationRequest->to_user_id 
            : $donationRequest->from_user_id;

        if (auth()->id() !== $bloodReceiverId) {
            abort(403, 'Only the receiver can confirm blood receipt.');
        }

        if ($donationRequest->status !== 'accepted') {
            return back()->with('error', 'This donation request is not active.');
        }

        if ($donationRequest->confirmed_received) {
            return back()->with('error', 'Blood receipt has already been confirmed.');
        }

        if ($donationRequest->points_awarded) {
            return back()->with('error', 'Points have already been awarded for this donation.');
        }

        $outcome = $request->input('outcome', 'received');

        if ($outcome === 'not_received') {
            $donationRequest->status = 'declined';
            $donationRequest->save();
            return redirect()->route('inbox.index')->with('status', 'Help declined. Chat closed. Your emergency is still active.');
        }

        // Determine points based on donation type
        $points = $donationRequest->donation_type === 'emergency' ? 100 : 50;
        $label  = $donationRequest->donation_type === 'emergency'
            ? 'Emergency blood donation support'
            : 'Blood donation support';

        // Determine the actual donor
        $donorId = $donationRequest->donation_type === 'emergency' 
            ? $donationRequest->from_user_id 
            : $donationRequest->to_user_id;

        // Award points to the donor (Log in Point table for history)
        Point::create([
            'user_id'     => $donorId,
            'amount'      => $points,
            'reason'      => $label,
            'donation_id' => $donationRequest->id,
        ]);

        // Increment cached points on User model for instant high-performance Leaderboard queries
        $donorUser = \App\Models\User::find($donorId);
        if ($donorUser) {
            $donorUser->increment('points', $points);
        }

        // Mark the request as confirmed — prevent double awarding, and set status to completed
        $donationRequest->confirmed_received = true;
        $donationRequest->confirmed_at       = now();
        $donationRequest->points_awarded     = true;
        $donationRequest->status             = 'completed'; // Change status to completed so future requests aren't blocked
        $donationRequest->save();

        // Anti-Fraud: 90-Day Medical Cooldown
        $donor = \App\Models\User::find($donorId);
        if ($donor) {
            $donor->last_donated_at = now();
            $donor->availability_status = 'unavailable';
            $donor->save();
        }

        // Also complete the parent BloodRequest so it drops off the dashboard
        if ($donationRequest->blood_request_id) {
            $bloodRequest = \App\Models\BloodRequest::find($donationRequest->blood_request_id);
            if ($bloodRequest) {
                $bloodRequest->status = 'Completed';
                $bloodRequest->save();
            }
        }

        return redirect()->route('inbox.index')->with('status', "Thank you! {$points} points awarded to your donor.");
    }

    /**
     * Delete/close the active chat.
     */
    public function destroy(DonationRequest $donationRequest)
    {
        // Only sender or receiver can delete
        if (auth()->id() !== $donationRequest->from_user_id && auth()->id() !== $donationRequest->to_user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Just delete the donation request record to remove the chat
        $donationRequest->delete();

        return redirect()->route('inbox.index')->with('status', 'Chat has been deleted and connection closed.');
    }
}
