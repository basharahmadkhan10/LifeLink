<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class DonationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'blood_request_id',
        'message',
        'status',            // pending, accepted, declined
        'donation_type',     // emergency (+150 pts) | normal (+20 pts)
        'confirmed_received',// boolean — receiver confirmed blood arrived
        'confirmed_at',      // timestamp of confirmation
        'points_awarded',    // boolean — prevent double awarding
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }
}
