<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
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
        // 1. Buat Profil Sekolah Default (sebagai tenant awal)
        $sekolah = SchoolProfile::firstOrCreate(
            ['nama_sekolah' => 'Sekolah Dasar Negeri 1'],
            [
                'npsn' => '00000000',
                'alamat' => 'Alamat Sekolah',
                'kabupaten' => 'Kabupaten',
                'provinsi' => 'Jawa Barat',
                'kepala_sekolah' => 'Nama Kepala Sekolah',
                'guru_kelas' => 'Nama Guru Kelas',
                'semester' => 'I (Satu)',
                'tahun_pelajaran' => '2025/2026',
            ]
        );

        // 2. Akun Super Admin (bisa akses semua sekolah)
        User::firstOrCreate(
            ['email' => 'superadmin@sipeka.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'superadmin',
                'school_profile_id' => $sekolah->id,
            ]
        );

        // 3. Akun Admin (Guru) contoh — terhubung ke sekolah default
        User::firstOrCreate(
            ['email' => 'guru@sipeka.com'],
            [
                'name' => 'Guru',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'school_profile_id' => $sekolah->id,
            ]
        );
    }
}
