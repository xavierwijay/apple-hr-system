<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin (Tim Cook)
        User::updateOrCreate(
            ['email' => 'admin@apple.com'],
            [
                'name' => 'Tim Cook (Admin HR)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
        );

        // 2. Akun User Biasa (Craig Federighi)
        User::updateOrCreate(
            ['email' => 'user@apple.com'],
            [
                'name' => 'Craig Federighi (Staff)',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ],
        );

        // 3. Data Initial Karyawan Apple
        Employee::updateOrCreate(
            ['employee_id' => 'AAPL-101'],
            [
                'nama' => 'Jony Ive',
                'role_title' => 'Chief Industrial Designer',
                'team_division' => 'Hardware Design',
                'location' => 'Apple Park, Cupertino',
                'salary' => 150000,
            ],
        );

        Employee::updateOrCreate(
            ['employee_id' => 'AAPL-102'],
            [
                'nama' => 'Eddy Cue',
                'role_title' => 'Genius Bar Specialist',
                'team_division' => 'Retail Operations',
                'location' => 'Apple Store Orchard Road',
                'salary' => 45000,
            ],
        );
    }
}
