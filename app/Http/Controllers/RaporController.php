<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Models\Reflection;
use App\Models\SchoolProfile;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    // TAMBAHKAN Request $request DI DALAM KURUNG
    public function cetakPdf(Request $request, $id)
    {
        $siswa = Student::findOrFail($id);
        $sekolah = SchoolProfile::first();

        // 1. TANGKAP PILIHAN DARI POP-UP FILAMENT
        $subjectId = $request->query('subject_id');
        $jenisPenilaian = $request->query('jenis_penilaian') ?? 'Proyek'; // Default Proyek

        // Ambil mapel sesuai yang dipilih
        $mapel = Subject::find($subjectId) ?? Subject::first();

        $cpIds = $request->query('cp_ids', []); // Tangkap array ID CP
        $tpIds = $request->query('tp_ids', []); // Tangkap array ID TP

        // Ambil data aslinya dari database
        $cps = \App\Models\LearningOutcome::whereIn('id', is_array($cpIds) ? $cpIds : [])->get();
        $tps = \App\Models\LearningObjective::whereIn('id', is_array($tpIds) ? $tpIds : [])->get();

        // 2. AMBIL INDIKATOR HANYA YANG SESUAI JENIS PENILAIAN (PROYEK/KINERJA)
        $semuaIndikator = Indicator::whereHas('aspect', function ($q) use ($jenisPenilaian) {
            $q->where('jenis_penilaian', $jenisPenilaian);
        })->with('aspect')->get();

        // 3. AMBIL NILAI SISWA (Hanya untuk indikator yang tersaring di atas)
        $skorSiswa = Score::where('student_id', $id)
            ->whereIn('indicator_id', $semuaIndikator->pluck('id')) // Filter skor
            ->with('indicator')
            ->get()
            ->keyBy('indicator_id');

        // 4. GABUNGKAN (Sama seperti sebelumnya)
        $nilaiSiswa = $semuaIndikator->map(function ($indikator) use ($skorSiswa) {
            $skor = $skorSiswa->get($indikator->id);
            $scoreValue = $skor ? $skor->score_value : null;

            $catatanGuru = '-';
            if ($scoreValue == 1) {
                $catatanGuru = $indikator->catatan_skor_1;
            } elseif ($scoreValue == 2) {
                $catatanGuru = $indikator->catatan_skor_2;
            } elseif ($scoreValue == 3) {
                $catatanGuru = $indikator->catatan_skor_3;
            } elseif ($scoreValue == 4) {
                $catatanGuru = $indikator->catatan_skor_4;
            }

            return (object) [
                'indicator' => $indikator,
                'score_value' => $scoreValue,
                'catatan_guru' => $catatanGuru,
            ];
        });

        // --- HITUNG-HITUNGAN (Tetap sama, tidak ada yang diubah) ---
        $jumlahSkor = $skorSiswa->sum('score_value');
        $jumlahIndikator = $semuaIndikator->count();
        $nilaiSkala100 = $jumlahIndikator > 0 ? round(($jumlahSkor / ($jumlahIndikator * 4)) * 100) : 0;

        $rataRata = $skorSiswa->avg('score_value') ?? 0;
        $kategori = 'Perlu Bimbingan / Belum Berkembang';
        if ($rataRata >= 3.5) {
            $kategori = 'Sangat Baik / Sangat Berkembang';
        } elseif ($rataRata >= 2.5) {
            $kategori = 'Baik / Berkembang Sesuai Harapan';
        } elseif ($rataRata >= 1.5) {
            $kategori = 'Cukup / Mulai Berkembang';
        }

        $kktp = $mapel->kktp ?? 75;
        $ketuntasan = $nilaiSkala100 >= $kktp ? 'Tuntas' : 'Belum Tuntas';

        $terkuat = $skorSiswa->sortByDesc('score_value')->take(3);
        $terlemah = $skorSiswa->sortBy('score_value')->take(3);

        $rataPerAspek = $skorSiswa->groupBy(function ($item) {
            return $item->indicator->aspect->nama_aspek ?? 'Tanpa Aspek';
        })->map(function ($row) {
            return round(collect($row)->avg('score_value'), 2);
        });

        $kategoriPendek = explode(' / ', $kategori)[0];
        $refleksi = Reflection::where('kategori_predikat', 'LIKE', "%$kategoriPendek%")->first();

        $pdf = Pdf::loadView('cetak.rapor', [
            'siswa' => $siswa,
            'sekolah' => $sekolah,
            'nilaiSiswa' => $nilaiSiswa,
            'mapel' => $mapel,
            'jenisPenilaian' => $jenisPenilaian, // Kirim variabel ini ke PDF
            'jumlahSkor' => $jumlahSkor,
            'nilaiSkala100' => $nilaiSkala100,
            'kategori' => $kategori,
            'ketuntasan' => $ketuntasan,
            'kktp' => $kktp,
            'terkuat' => $terkuat,
            'terlemah' => $terlemah,
            'rataPerAspek' => $rataPerAspek,
            'refleksi' => $refleksi,
            'cps' => $cps,
            'tps' => $tps,
        ]);

        return $pdf->stream('Rapor_'.$jenisPenilaian.'_'.$siswa->nama.'.pdf');
    }
}
