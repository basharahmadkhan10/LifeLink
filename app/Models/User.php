<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'blood_group',
        'gender',
        'age',
        'city',
        'address',
        'location',
        'availability_status',
        'role',
        'license_number',
        'email_verified_at',
        'phone_verified_at',
        'registered_by_hospital_id',
        'points',
        'is_banned',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_donated_at' => 'datetime',
            'password' => 'hashed',
            'points' => 'integer',
            'is_banned' => 'boolean',
        ];
    }

    /**
     * The hospital that registered this donor (if any).
     */
    public function registeringHospital()
    {
        return $this->belongsTo(User::class, 'registered_by_hospital_id');
    }
}
