<?php

namespace App\Filament\Widgets;

use App\Models\Score;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IndikatorChart extends ChartWidget
{
    protected static ?string $heading = 'Peta Kekuatan & Kelemahan Indikator';
    protected static ?int $sort = 2; // Agar posisinya di bawah Kartu Statistik

    protected function getData(): array
    {
        // Menghitung rata-rata nilai untuk setiap indikator
        $data = Score::select('indicator_id', DB::raw('avg(score_value) as rata_rata'))
            ->groupBy('indicator_id')
            ->with('indicator')
            ->get();

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            // Kita potong nama indikator maksimal 25 huruf agar grafik tidak terlalu berdesakan
            $labels[] = substr($item->indicator->nama_indikator ?? 'Unknown', 0, 25) . '...';
            $values[] = round($item->rata_rata, 2); // Membulatkan 2 angka di belakang koma
        }

        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata Skor Kelas (Skala 1-4)',
                    'data' => $values,
                    'backgroundColor' => '#3b82f6', // Warna Biru
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}