<?php

use App\Http\Controllers\AngsuranController;
use Illuminate\Support\Facades\Route;

Route::middleware('jwt.auth')->prefix('installments')->name('installments.')->group(function(){
    Route::get('/', [AngsuranController::class,'index'])->name('index');
    Route::post('/payment/{id}', [AngsuranController::class,'payment'])->name('payment');
});
