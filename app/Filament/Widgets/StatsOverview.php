<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Score;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = -2;
    protected function getStats(): array
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        $userRole = auth()->user()?->role;
        $userKelas = auth()->user()?->kelas;

        $studentQuery = Student::where('school_profile_id', $tenantId);
        $scoreQuery = Score::where('school_profile_id', $tenantId);

        if ($userRole === 'admin' && $userKelas) {
            $studentQuery->where('kelas', $userKelas);
            $scoreQuery->whereHas('student', function ($query) use ($userKelas) {
                $query->where('kelas', $userKelas);
            });
        }

        // 1. Menghitung total siswa
        $totalSiswa = $studentQuery->count();

        // 2. Menghitung rata-rata nilai seluruh kelas (dari skor 1-4)
        $rataRataKelas = $scoreQuery->clone()->avg('score_value') ?? 0;
        
        // Mengubah rata-rata menjadi skala 100 (opsional, agar mudah dibaca)
        // Karena nilai maksimal 4, maka (rata-rata / 4) * 100
        $rataRataSkala100 = ($rataRataKelas / 4) * 100;

        // 3. Menghitung total data nilai yang sudah masuk
        $totalPenilaian = $scoreQuery->clone()->count();

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