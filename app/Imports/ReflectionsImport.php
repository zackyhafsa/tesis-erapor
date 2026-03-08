<?php

namespace App\Imports;

use App\Models\Reflection;
use Filament\Facades\Filament;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ReflectionsImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    public function model(array $row)
    {
        // Skip baris kosong (cek satu field aja, misal kelebihan siswa)
        if (empty($row['kelebihan_siswa']) && empty($row['aspek_yang_perlu_ditingkatkan'])) {
            return null;
        }

        $schoolProfileId = Filament::getTenant()->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $reflection = new Reflection();
        $reflection->school_profile_id = $schoolProfileId;
        
        $reflection->jenis_penilaian = $row['jenis_penilaian'] ?? 'Kinerja';
        $reflection->kategori_predikat = $row['kategori_predikat'] ?? 'Sangat Baik';
        
        // Memasukkan 3 field baru
        $reflection->kelebihan_siswa = $row['kelebihan_siswa'] ?? null;
        $reflection->aspek_ditingkatkan = $row['aspek_yang_perlu_ditingkatkan'] ?? null;
        $reflection->tindak_lanjut = $row['rencana_tindak_lanjut_pengayaan'] ?? null;
        
        if ($userRole === 'admin' && $userKelas) {
            $reflection->kelas = $userKelas;
        } else {
            $reflection->kelas = $row['kelas'] ?? null;
        }

        return $reflection;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
