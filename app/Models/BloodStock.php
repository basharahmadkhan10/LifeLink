<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'blood_group',
        'units_available'
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
