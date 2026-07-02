<?php

namespace App\Imports;

use App\Models\Aspect;
use Filament\Facades\Filament;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class AspectsImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['nama_aspek'])) {
            return null;
        }

        $schoolProfileId = Filament::getTenant()->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $aspect = new Aspect();
        $aspect->school_profile_id = $schoolProfileId;
        
        $aspect->jenis_penilaian = $row['jenis_penilaian'] ?? 'Kinerja';
        $aspect->nama_aspek = $row['nama_aspek'];
        
        // Auto-assign class if logged in user is a teacher
        if ($userRole === 'admin' && $userKelas) {
            $aspect->kelas = $userKelas;
        } else {
            $aspect->kelas = $row['kelas'] ?? null;
        }

        if (!empty($aspect->kelas)) {
            $angkaKelas = (int) $aspect->kelas;
            $aspect->fase = match (true) {
                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                $angkaKelas >= 7 && $angkaKelas <= 9 => 'D',
                default => $row['fase'] ?? null,
            };
        }

        return $aspect;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
