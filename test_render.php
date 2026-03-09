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
        echo view('filament.pages.analisis-kelas', $data)->render();
        echo "\nRender Analisis Kelas OK\n";
    } catch (\Exception $e) {
        echo "Analisis Kelas Render Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    }

    try {
        $p = new App\Filament\Pages\RekapNilai();
        $p->mount();
        $data = $p->getViewData();
        echo view('filament.pages.rekap-nilai', $data)->render();
        echo "\nRender Rekap Nilai OK\n";
    } catch (\Exception $e) {
        echo "Rekap Nilai Render Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
} else {
    echo "No admin user found\n";
}
