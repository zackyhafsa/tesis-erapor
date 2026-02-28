<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Score;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Menghitung total siswa
        $totalSiswa = Student::count();

        // 2. Menghitung rata-rata nilai seluruh kelas (dari skor 1-4)
        $rataRataKelas = Score::avg('score_value') ?? 0;
        
        // Mengubah rata-rata menjadi skala 100 (opsional, agar mudah dibaca)
        // Karena nilai maksimal 4, maka (rata-rata / 4) * 100
        $rataRataSkala100 = ($rataRataKelas / 4) * 100;

        // 3. Menghitung total data nilai yang sudah masuk
        $totalPenilaian = Score::count();

        return [
            Stat::make('Total Siswa', $totalSiswa)
                ->description('Jumlah anak didik terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Rata-rata Kelas', number_format($rataRataSkala100, 2) . ' / 100')
                ->description('Skor rata-rata: ' . number_format($rataRataKelas, 2) . ' (Skala 4)')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
                
            Stat::make('Total Penilaian Input', $totalPenilaian)
                ->description('Data nilai yang telah dimasukkan guru')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}