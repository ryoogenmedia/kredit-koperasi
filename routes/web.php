<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/login');

Route::middleware('auth', 'verified', 'force.logout')->namespace('App\Livewire')->group(function () {
    /**
     * beranda / home
     */
    Route::get('beranda', Home\Index::class)->name('home')
        ->middleware('roles:admin,user,operator');

    /**
     * User / Pengguna
     */
    Route::namespace('Pengguna')->prefix('pengguna')->name('pengguna.')->group(function () {
            Route::get('/', Index::class)->name('index');
            Route::get('/tambah', Create::class)->name('create');
            Route::get('/sunting/{id}', Edit::class)->name('edit');
    });

    /**
     * Nasabah
     */
    Route::namespace('Nasabah')->prefix('nasabah')->name('nasabah.')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/verfikasi', Verification::class)->name('verfication');
    });

    /**
     * setting
     */
    Route::prefix('pengaturan')->name('setting.')->middleware('roles:admin,user')->namespace('Setting')->group(function () {
        Route::redirect('/', 'pengaturan/aplikasi');

        /**
         * Profile
         */
        Route::prefix('profil')->name('profile.')->group(function () {
            Route::get('/', Profile\Index::class)->name('index');
        });

        /**
         * Account
         */
        Route::prefix('akun')->name('account.')->group(function () {
            Route::get('/', Account\Index::class)->name('index');
        });
    });
});
