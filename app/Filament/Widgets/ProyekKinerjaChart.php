<?php

namespace App\Filament\Widgets;

use App\Models\Aspect;
use App\Models\Score;
use Filament\Widgets\ChartWidget;

class ProyekKinerjaChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Rata-rata: Proyek vs Kinerja';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        // Rata-rata Proyek per Aspek
        $aspekProyek = Aspect::where('jenis_penilaian', 'Proyek')->with('indicators')->get();
        $aspekKinerja = Aspect::where('jenis_penilaian', 'Kinerja')->with('indicators')->get();

        $labelsProyek = [];
        $valuesProyek = [];
        foreach ($aspekProyek as $aspek) {
            $indIds = $aspek->indicators->pluck('id');
            $avg = Score::whereIn('indicator_id', $indIds)->avg('score_value');
            $labelsProyek[] = $aspek->nama_aspek;
            $valuesProyek[] = round(($avg ?? 0), 2);
        }

        $labelsKinerja = [];
        $valuesKinerja = [];
        foreach ($aspekKinerja as $aspek) {
            $indIds = $aspek->indicators->pluck('id');
            $avg = Score::whereIn('indicator_id', $indIds)->avg('score_value');
            $labelsKinerja[] = $aspek->nama_aspek;
            $valuesKinerja[] = round(($avg ?? 0), 2);
        }

        // Combine all labels
        $allLabels = collect($labelsProyek)->merge($labelsKinerja)->unique()->values()->toArray();

        // Map values to combined labels
        $proyekData = [];
        $kinerjaData = [];
        foreach ($allLabels as $label) {
            $idxP = array_search($label, $labelsProyek);
            $proyekData[] = $idxP !== false ? $valuesProyek[$idxP] : 0;

            $idxK = array_search($label, $labelsKinerja);
            $kinerjaData[] = $idxK !== false ? $valuesKinerja[$idxK] : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Proyek',
                    'data' => $proyekData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Kinerja',
                    'data' => $kinerjaData,
                    'backgroundColor' => 'rgba(234, 179, 8, 0.7)',
                    'borderColor' => '#eab308',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $allLabels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
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
        return 'bar';
    }
}
