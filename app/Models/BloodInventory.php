<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class BloodInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'blood_group',
        'units',
        'last_updated_at',
    ];

    protected $casts = [
        'units' => 'integer',
        'last_updated_at' => 'datetime',
    ];

    public function hospital()
    {
        return $this->belongsTo(User::class, 'hospital_id');
    }
}
