<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lifelink.com'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'phone' => '1234567890',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'availability_status' => 'unavailable',
            ]
        );
    }
}
