<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticationController::class,'login'])->name('login');
Route::post('/register', [AuthenticationController::class,'register'])->name('register');
