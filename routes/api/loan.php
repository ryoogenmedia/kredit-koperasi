<?php

use App\Http\Controllers\PinjamanController;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt.auth')->prefix('loan')->name('loan.')->group(function(){
    Route::get('/', [PinjamanController::class,'index'])->name('index');
    Route::post('/', [PinjamanController::class,'store'])->name('store');
    Route::put('/{id}', [PinjamanController::class, 'update'])->name('update');
    Route::delete('/{id}', [PinjamanController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [PinjamanController::class,'show'])->name('show');
    Route::post('/confirmation/{id}', [PinjamanController::class,'confirmation'])->name('confirmation');
});
