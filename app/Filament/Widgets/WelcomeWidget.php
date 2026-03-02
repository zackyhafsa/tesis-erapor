<?php

namespace App\Filament\Widgets;

use App\Models\SchoolProfile;
use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';

    protected static ?int $sort = -3;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $user = auth()->user();
        $sekolah = SchoolProfile::first();

        $waktuWIB = now()->setTimezone('Asia/Jakarta')->locale('id');
        $hour = (int) $waktuWIB->format('H');
        if ($hour >= 5 && $hour < 11) {
            $salam = 'Selamat Pagi';
            $emoji = '☀️';
        } elseif ($hour >= 11 && $hour < 15) {
            $salam = 'Selamat Siang';
            $emoji = '🌤️';
        } elseif ($hour >= 15 && $hour < 18) {
            $salam = 'Selamat Sore';
            $emoji = '🌅';
        } else {
            $salam = 'Selamat Malam';
            $emoji = '🌙';
        }

        return [
            'salam' => $salam,
            'emoji' => $emoji,
            'userName' => $user?->name ?? 'Guru',
            'namaSekolah' => $sekolah?->nama_sekolah ?? '',
            'tahunPelajaran' => $sekolah?->tahun_pelajaran ?? '',
            'semester' => $sekolah?->semester ?? '',
            'tanggal' => $waktuWIB->translatedFormat('l, d F Y'),
        ];
    }
}
