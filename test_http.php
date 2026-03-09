<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('http://localhost', 'GET');
$kernel->handle($request); // Just to bootstrap

$admin = App\Models\User::where('role', 'admin')->first();
if ($admin) {
    Auth::login($admin);
    $url = '/admin/' . $admin->school_profile_id . '/analisis-kelas';
    echo "Hitting URL: " . $url . "\n";
    $request = Illuminate\Http\Request::create('http://localhost' . $url, 'GET');
    $response = $kernel->handle($request);
    if ($response->exception) {
        echo 'Exception: ' . $response->exception->getMessage() . " at " . $response->exception->getFile() . ":" . $response->exception->getLine() . "\n";
    } else {
        echo "Status Code: " . $response->getStatusCode() . "\n";
    }
}
