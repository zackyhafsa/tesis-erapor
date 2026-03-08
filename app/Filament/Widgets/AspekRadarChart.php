<?php

namespace App\Filament\Widgets;

use App\Models\Aspect;
use App\Models\Score;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AspekRadarChart extends ChartWidget
{
    protected static ?string $heading = 'Rata-rata Skor per Aspek Kompetensi';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $aspectQuery = Aspect::where('school_profile_id', $tenantId)->with('indicators');
        $scoreQuery = Score::where('school_profile_id', $tenantId);

        if ($userRole === 'admin' && $userKelas) {
            $aspectQuery->where('kelas', $userKelas);
            $scoreQuery->whereHas('student', function ($query) use ($userKelas) {
                $query->where('kelas', $userKelas);
            });
        }

        $aspects = $aspectQuery->get();

        $labels = [];
        $dataProyek = [];
        $dataKinerja = [];

        // Group aspects by jenis_penilaian
        $aspekProyek = $aspects->where('jenis_penilaian', 'Proyek');
        $aspekKinerja = $aspects->where('jenis_penilaian', 'Kinerja');

        // Collect all unique aspect names across both types
        $allAspekNames = $aspects->pluck('nama_aspek')->unique()->values();

        foreach ($allAspekNames as $namaAspek) {
            $labels[] = $namaAspek;

            // Proyek score for this aspect name
            $aspekP = $aspekProyek->firstWhere('nama_aspek', $namaAspek);
            if ($aspekP) {
                $indIds = $aspekP->indicators->pluck('id');
                $avg = $scoreQuery->clone()->whereIn('indicator_id', $indIds)->avg('score_value');
                $dataProyek[] = round($avg ?? 0, 2);
            } else {
                $dataProyek[] = 0;
            }

            // Kinerja score for this aspect name
            $aspekK = $aspekKinerja->firstWhere('nama_aspek', $namaAspek);
            if ($aspekK) {
                $indIds = $aspekK->indicators->pluck('id');
                $avg = $scoreQuery->clone()->whereIn('indicator_id', $indIds)->avg('score_value');
                $dataKinerja[] = round($avg ?? 0, 2);
            } else {
                $dataKinerja[] = 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Proyek',
                    'data' => $dataProyek,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => '#3b82f6',
                    'pointBackgroundColor' => '#3b82f6',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Kinerja',
                    'data' => $dataKinerja,
                    'backgroundColor' => 'rgba(234, 179, 8, 0.2)',
                    'borderColor' => '#eab308',
                    'pointBackgroundColor' => '#eab308',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'r' => [
                    'min' => 0,
                    'max' => 4,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }
}
