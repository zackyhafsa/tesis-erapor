<?php

namespace App\Filament\Widgets;

use App\Models\Score;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PredikatChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Predikat Kelas';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Menghitung rata-rata skor per siswa
        $siswaScores = Score::select('student_id', DB::raw('avg(score_value) as rata_rata'))
            ->groupBy('student_id')
            ->get();

        $sb = 0; // Sangat Berkembang (91-100)
        $bsh = 0; // Berkembang Sesuai Harapan (76-90)
        $mb = 0; // Mulai Berkembang (61-75)
        $bb = 0; // Belum Berkembang (<=60)

        foreach ($siswaScores as $score) {
            // Konversi dari skala 4 ke skala 100
            $nilai100 = ($score->rata_rata / 4) * 100;
            
            if ($nilai100 > 90) {
                $sb++;
            } elseif ($nilai100 > 75) {
                $bsh++;
            } elseif ($nilai100 > 60) {
                $mb++;
            } else {
                $bb++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => [$sb, $bsh, $mb, $bb],
                    'backgroundColor' => ['#22c55e', '#3b82f6', '#eab308', '#ef4444'], // Hijau, Biru, Kuning, Merah
                ],
            ],
            'labels' => ['Sangat Berkembang (SB)', 'Berkembang Sesuai Harapan (BSH)', 'Mulai Berkembang (MB)', 'Belum Berkembang (BB)'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}