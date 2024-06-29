<?php

use App\Http\Controllers\VerficationController;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt.auth')->prefix('verification')->name('verification.')->group(function(){
    Route::redirect('/api/verification', '/api/verification/nasabah');
    Route::get('/nasabah', [VerficationController::class,'nasabah'])->name('nasabah');
    Route::get('/fund', [VerficationController::class,'fund'])->name('fund');

    Route::post('/akad/{id}', [VerficationController::class,'akad'])->name('akad');
});
