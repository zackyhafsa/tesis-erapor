<?php

namespace App\Imports;

use App\Models\Subject;
use Filament\Facades\Filament;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class SubjectsImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        if (empty($row['nama_mata_pelajaran'])) {
            return null;
        }

        $schoolProfileId = Filament::getTenant()->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $subject = new Subject();
        $subject->school_profile_id = $schoolProfileId;
        
        $subject->nama_mapel = $row['nama_mata_pelajaran'];
        $subject->kktp = $row['kktp'] ?? 75;
        
        if ($userRole === 'admin' && $userKelas) {
            $subject->kelas = $userKelas;
        } else {
            $subject->kelas = $row['kelas'] ?? null;
        }

        if (!empty($subject->kelas)) {
            $angkaKelas = (int) $subject->kelas;
            $subject->fase = match (true) {
                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                $angkaKelas >= 7 && $angkaKelas <= 9 => 'D',
                default => $row['fase'] ?? null,
            };
        }

        return $subject;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
