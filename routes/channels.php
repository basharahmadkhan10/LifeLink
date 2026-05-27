<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});

Broadcast::channel('chat.{donationRequestId}', function ($user, $donationRequestId) {
    $donationRequest = \App\Models\DonationRequest::find($donationRequestId);
    if (!$donationRequest) {
        return false;
    }
    return ((string) $user->id === (string) $donationRequest->from_user_id || 
            (string) $user->id === (string) $donationRequest->to_user_id);
});
