<?php

use App\Http\Controllers\RaporController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cetak-rapor/{id}', [RaporController::class, 'cetakPdf'])->name('cetak.rapor');
