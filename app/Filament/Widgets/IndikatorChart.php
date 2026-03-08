<?php

namespace App\Filament\Widgets;

use App\Models\Score;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IndikatorChart extends ChartWidget
{
    protected static ?string $heading = 'Peta Kekuatan & Kelemahan Indikator';
    protected static ?int $sort = 3; // Agar posisinya di bawah Kartu Statistik

    protected function getData(): array
    {
        // Menghitung rata-rata nilai untuk setiap indikator
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $query = Score::where('school_profile_id', $tenantId);

        if ($userRole === 'admin' && $userKelas) {
            $query->whereHas('student', function ($q) use ($userKelas) {
                $q->where('kelas', $userKelas);
            });
        }

        $data = $query->select('indicator_id', DB::raw('avg(score_value) as rata_rata'))
            ->groupBy('indicator_id')
            ->with(['indicator' => function ($q) use ($userRole, $userKelas) {
                if ($userRole === 'admin' && $userKelas) {
                    $q->where('kelas', $userKelas);
                }
            }])
            ->get()
            ->filter(function ($item) {
                return $item->indicator !== null;
            });

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