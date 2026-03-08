<?php

namespace App\Imports;

use App\Models\Indicator;
use App\Models\Aspect;
use Filament\Facades\Filament;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class IndicatorsImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['nama_indikator'])) {
            return null;
        }

        $schoolProfileId = Filament::getTenant()->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;
        
        $kelasImport = ($userRole === 'admin' && $userKelas) ? $userKelas : ($row['kelas'] ?? null);

        // Find the Aspect
        $aspect = Aspect::where('school_profile_id', $schoolProfileId)
            ->where('nama_aspek', trim($row['nama_aspek']))
            ->when($kelasImport, function($query) use ($kelasImport) {
                // If it's a class-scoped aspect
                $query->where('kelas', $kelasImport);
            })
            ->first();
            
        // If aspect not found, we could either create it or skip. It's safer to skip or assign to a default.
        // Let's create it if it doesn't exist
        if (!$aspect) {
            $aspect = new Aspect();
            $aspect->school_profile_id = $schoolProfileId;
            $aspect->jenis_penilaian = $row['jenis_penilaian'] ?? 'Kinerja';
            $aspect->nama_aspek = trim($row['nama_aspek'] ?? 'Aspek Baru');
            $aspect->kelas = $kelasImport;
            $aspect->save();
        }

        $indicator = new Indicator();
        $indicator->school_profile_id = $schoolProfileId;
        $indicator->aspect_id = $aspect->id;
        $indicator->kelas = $kelasImport;
        
        $indicator->nama_indikator = trim($row['nama_indikator']);
        $indicator->deskripsi_kriteria = $row['deskripsi_kriteria'] ?? null;
        $indicator->catatan_skor_1 = $row['rubrik_skor_1'] ?? null;
        $indicator->catatan_skor_2 = $row['rubrik_skor_2'] ?? null;
        $indicator->catatan_skor_3 = $row['rubrik_skor_3'] ?? null;
        $indicator->catatan_skor_4 = $row['rubrik_skor_4'] ?? null;

        return $indicator;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
