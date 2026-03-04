<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Super Admin default
        User::firstOrCreate(
            ['email' => 'superadmin@sipeka.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
            ]
        );

        // Akun Admin (Guru) contoh
        User::firstOrCreate(
            ['email' => 'guru@sipeka.com'],
            [
                'name' => 'Guru',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
    }
}
