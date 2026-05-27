<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_name',
        'blood_group',
        'units_needed',
        'hospital_name',
        'city',
        'address',
        'location',
        'urgency_level', // High, Medium, Low
        'emergency_level', // specific for emergency broadcast
        'status', // Pending, Fulfilled, Cancelled
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
