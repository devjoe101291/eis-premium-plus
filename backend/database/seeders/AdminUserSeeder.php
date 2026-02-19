<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('proweaver'),
                'role' => 'admin',
                'status' => 'active',
                'title' => 'System Administrator',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Employee User',
                'email' => 'employee@example.com',
                'password' => Hash::make('proweaver'),
                'role' => 'employee',
                'status' => 'active',
                'title' => 'Employee',
                'email_verified_at' => now(),
            ]
        );
    }
}