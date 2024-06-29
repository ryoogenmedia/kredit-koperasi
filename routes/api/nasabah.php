<?php

use App\Http\Controllers\NasabahController;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt.auth')->prefix('nasabah')->name('nasabah.')->group(function(){
    Route::get('/', [NasabahController::class,'index'])->name('index');
    Route::post('/', [NasabahController::class,'store'])->name('store');
    Route::put('/{id}', [NasabahController::class, 'update'])->name('update');
    Route::delete('/{id}', [NasabahController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [NasabahController::class,'show'])->name('show');
});
