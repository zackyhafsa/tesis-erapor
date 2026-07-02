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
        
        // Memasukkan 3 field baru (dengan pemisah titik koma / baris baru)
        $reflection->kelebihan_siswa = $this->parseTags($row['kelebihan_siswa'] ?? null);
        $reflection->aspek_ditingkatkan = $this->parseTags($row['aspek_yang_perlu_ditingkatkan'] ?? null);
        $reflection->tindak_lanjut = $this->parseTags($row['rencana_tindak_lanjut_pengayaan'] ?? null);
        
        if ($userRole === 'admin' && $userKelas) {
            $reflection->kelas = $userKelas;
        } else {
            $reflection->kelas = $row['kelas'] ?? $row['kelas_opsional_untuk_guru'] ?? null;
        }

        if (!empty($reflection->kelas)) {
            $angkaKelas = (int) $reflection->kelas;
            $reflection->fase = match (true) {
                $angkaKelas >= 1 && $angkaKelas <= 2 => 'A',
                $angkaKelas >= 3 && $angkaKelas <= 4 => 'B',
                $angkaKelas >= 5 && $angkaKelas <= 6 => 'C',
                $angkaKelas >= 7 && $angkaKelas <= 9 => 'D',
                default => $row['fase'] ?? null,
            };
        }

        return $reflection;
    }

    private function parseTags(?string $value): ?array
    {
        if (empty($value)) return null;
        
        // Ganti baris baru koma dengan titik koma untuk displit
        $value = str_replace(["\r\n", "\r", "\n", "||"], ';', $value);
        $tags = array_map('trim', explode(';', $value));
        
        // Hapus array yang kosong
        $tags = array_filter($tags, function($t) {
            return $t !== '' && $t !== null;
        });

        $tags = array_values($tags);

        return empty($tags) ? null : $tags;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
