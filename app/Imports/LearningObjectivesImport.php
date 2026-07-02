<?php

namespace App\Imports;

use App\Models\LearningObjective;
use App\Models\Subject;
use Filament\Facades\Filament;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class LearningObjectivesImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        if (empty($row['deskripsi_tp'])) {
            return null;
        }

        $schoolProfileId = Filament::getTenant()->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $kelasImport = ($userRole === 'admin' && $userKelas) ? $userKelas : ($row['kelas'] ?? null);

        // Cari mapel berdasarkan nama dan kelas (jika mapel punya kelas)
        $subject = Subject::where('school_profile_id', $schoolProfileId)
            ->where('nama_mapel', trim($row['nama_mata_pelajaran']))
            ->when($kelasImport, function ($query) use ($kelasImport) {
                $query->where('kelas', $kelasImport);
            })
            ->first();

        // Jika mapel tidak ditemukan, buat baru
        if (!$subject) {
            $subject = new Subject();
            $subject->school_profile_id = $schoolProfileId;
            $subject->nama_mapel = trim($row['nama_mata_pelajaran'] ?? 'Mapel Baru');
            $subject->kelas = $kelasImport;
            $subject->kktp = 75; // Default KKTP
            $subject->save();
        }

        $tp = new LearningObjective();
        $tp->school_profile_id = $schoolProfileId;
        $tp->subject_id = $subject->id;
        $tp->kelas = $kelasImport;
        $tp->deskripsi = trim($row['deskripsi_tp']);

        if (!empty($tp->kelas)) {
            $angkaKelas = (int) $tp->kelas;
            $tp->fase = match (true) {
                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                $angkaKelas >= 7 && $angkaKelas <= 9 => 'D',
                default => $row['fase'] ?? null,
            };
        }

        return $tp;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
