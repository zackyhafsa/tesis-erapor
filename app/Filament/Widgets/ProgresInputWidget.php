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

        $totalSiswa = Student::where('school_profile_id', $tenantId)->count();
        $totalIndikator = Indicator::where('school_profile_id', $tenantId)->count();
        $targetTotal = $totalSiswa * $totalIndikator;

        $sudahDinilai = Score::where('school_profile_id', $tenantId)->count();
        $persentase = $targetTotal > 0 ? round(($sudahDinilai / $targetTotal) * 100, 1) : 0;

        // Siswa yang sudah dinilai LENGKAP (semua indikator terisi)
        $siswaLengkap = 0;
        if ($totalIndikator > 0) {
            $siswaLengkap = Student::where('school_profile_id', $tenantId)
                ->withCount('scores')
                ->get()
                ->filter(fn ($s) => $s->scores_count >= $totalIndikator)
                ->count();
        }
        $siswaBelum = $totalSiswa - $siswaLengkap;

        // Jumlah Indikator Proyek vs Kinerja
        $indikatorProyek = Indicator::where('school_profile_id', $tenantId)
            ->whereHas('aspect', fn ($q) => $q->where('jenis_penilaian', 'Proyek'))
            ->count();
            
        $indikatorKinerja = Indicator::where('school_profile_id', $tenantId)
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
