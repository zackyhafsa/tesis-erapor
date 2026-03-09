<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// simulate login
$admin = App\Models\User::where('role', 'admin')->first();
if ($admin) {
    Auth::login($admin);
    Filament\Facades\Filament::setTenant($admin->schoolProfile);
    try {
        $p = new App\Filament\Pages\AnalisisKelas();
        $p->mount();
        $data = $p->getViewData();
        echo "Analisis Kelas OK\n";
    } catch (\Exception $e) {
        echo "Analisis Kelas Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    }

    try {
        $p = new App\Filament\Pages\RekapNilai();
        $p->mount();
        $data = $p->getViewData();
        echo "Rekap Nilai OK\n";
    } catch (\Exception $e) {
        echo "Rekap Nilai Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
} else {
    echo "No admin user found\n";
}
