<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'المدير العام',
            'email' => 'admin@company.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);
        
        // Create additional test user
        User::create([
            'name' => 'مدير الموارد البشرية',
            'email' => 'hr@company.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);
    }
}
