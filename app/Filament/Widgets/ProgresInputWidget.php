<?php

namespace App\Filament\Widgets;

use App\Models\Aspect;
use App\Models\Indicator;
use App\Models\Score;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProgresInputWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $studentQuery = Student::where('school_profile_id', $tenantId);
        $indicatorQuery = Indicator::where('school_profile_id', $tenantId);
        $scoreQuery = Score::where('school_profile_id', $tenantId);

        if ($userRole === 'admin' && $userKelas) {
            $studentQuery->where('kelas', $userKelas);
            $indicatorQuery->where('kelas', $userKelas);
            $scoreQuery->whereHas('student', function ($q) use ($userKelas) {
                $q->where('kelas', $userKelas);
            });
        }

        $totalSiswa = $studentQuery->count();
        $totalIndikator = $indicatorQuery->clone()->count();
        $targetTotal = $totalSiswa * $totalIndikator;

        $sudahDinilai = $scoreQuery->count();
        $persentase = $targetTotal > 0 ? round(($sudahDinilai / $targetTotal) * 100, 1) : 0;

        // Siswa yang sudah dinilai LENGKAP (semua indikator terisi)
        $siswaLengkap = 0;
        if ($totalIndikator > 0) {
            $siswaLengkap = $studentQuery->clone()
                ->withCount('scores')
                ->get()
                ->filter(fn ($s) => $s->scores_count >= $totalIndikator)
                ->count();
        }
        $siswaBelum = $totalSiswa - $siswaLengkap;

        // Jumlah Indikator Proyek vs Kinerja
        $indikatorProyek = $indicatorQuery->clone()
            ->whereHas('aspect', fn ($q) => $q->where('jenis_penilaian', 'Proyek'))
            ->count();
            
        $indikatorKinerja = $indicatorQuery->clone()
            ->whereHas('aspect', fn ($q) => $q->where('jenis_penilaian', 'Kinerja'))
            ->count();

        return [
            Stat::make('Progres Penilaian', $persentase . '%')
                ->description($sudahDinilai . ' dari ' . $targetTotal . ' data nilai')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color($persentase >= 100 ? 'success' : ($persentase >= 50 ? 'warning' : 'danger'))
                ->chart([$persentase, 100 - $persentase]),

            Stat::make('Siswa Sudah Lengkap', $siswaLengkap . ' / ' . $totalSiswa)
                ->description($siswaBelum > 0 ? $siswaBelum . ' siswa belum lengkap' : 'Semua siswa sudah lengkap!')
                ->descriptionIcon($siswaBelum > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($siswaBelum === 0 ? 'success' : 'warning'),

            Stat::make('Total Indikator', $totalIndikator)
                ->description('Proyek: ' . $indikatorProyek . ' | Kinerja: ' . $indikatorKinerja)
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('info'),
        ];
    }
}
