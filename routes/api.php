<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function(){
    return "RYOOGEN MEDIA - API KOPERASI SETIA KARYA";
});

# AUTHENTICATION
require 'api/auth.php';

#VERFICATION / VERIFICATION
require 'api/verification.php';

#NASABAH
require 'api/nasabah.php';

#LOAN / PINJAMAN
require 'api/loan.php';
