<?php

use App\Http\Controllers\BeasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('beasiswa.index');
});

Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa.index');
Route::post('/beasiswa/check', [BeasiswaController::class, 'check'])->name('beasiswa.check');