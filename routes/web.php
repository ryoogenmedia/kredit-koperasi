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
        ->middleware('roles:admin,operator');

    /**
     * Pembayaran / Payment
     */
    Route::namespace('Payment')->prefix('pembayaran')->name('payment.')->middleware('roles:operator')->group(function(){
        Route::redirect('/pembayaran', '/pembayaran/pinjaman');
        Route::get('/pinjaman', Loan::class)->name('loan');
        Route::get('/angsuran', InstallMents::class)->name('installments');
    });

    /**
     * Laporan / Report
     */
    Route::namespace('Report')->prefix('laporan')->name('report.')->middleware('roles:operator')->group(function(){
        Route::redirect('/laporan', 'laporan/pinjaman');
        Route::get('/pinjaman', Loan::class)->name('loan');
        Route::get('/angsuran', Installments::class)->name('installments');
        Route::get('/nasabah', Nasabah::class)->name('nasabah');
    });

    /**
     * User / Pengguna
     */
    Route::namespace('Pengguna')->middleware('roles:admin')->prefix('pengguna')->name('pengguna.')->group(function () {
            Route::get('/', Index::class)->name('index');
            Route::get('/tambah', Create::class)->name('create');
            Route::get('/sunting/{id}', Edit::class)->name('edit');
    });

    /**
     * Nasabah
     */
    Route::namespace('Nasabah')->middleware('roles:admin')->prefix('nasabah')->name('nasabah.')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/verfikasi', Verification::class)->name('verification');
    });

    /**
     * Akad
     */
    Route::namespace('Akad')->middleware('roles:admin')->prefix('akad')->name('akad.')->group(function(){
        Route::prefix('pinjaman')->name('pinjaman.')->group(function(){
            Route::get('/', Index::class)->name('index');
            Route::get('/pemberian-akad/{id}', Agreement::class)->name('agreement');
        });

        Route::get('/pencairan', Funds::class)->name('funds');
    });

    /**
     * setting
     */
    Route::prefix('pengaturan')->middleware('roles:admin,operator')->name('setting.')->namespace('Setting')->group(function () {
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
