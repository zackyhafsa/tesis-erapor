<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaporController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cetak-rapor/{id}', [RaporController::class, 'cetakPdf'])->name('cetak.rapor');
