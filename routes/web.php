<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [ReservasiController::class, 'landingPage'])->name('landingPage');
Route::get('/lapangan/{id}', [ReservasiController::class, 'showLapangan'])->name('lapangan.detail');

/*
|--------------------------------------------------------------------------
| 🛡️ 1b. PORTAL AUTHENTICATION ADMIN GATEWAY
|--------------------------------------------------------------------------
| Diletakkan publik agar halaman login admin bisa diakses sebelum masuk ke dashboard.
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminDashboardController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminDashboardController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| 2. GOOGLE OAUTH
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| 3. PROTECTED ROUTES (Member / User Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Pembatalan & Konfirmasi Pembayaran
    Route::post('/reservasi/{nomor_reservasi}/batal-instan', [ReservasiController::class, 'cancelPendingInstant'])->name('reservasi.cancelInstant');
    Route::post('/reservasi/confirm-payment/{nomor_reservasi}', [ReservasiController::class, 'confirmPayment'])->name('reservasi.confirmPayment');

    // Dashboard User & Transaksi Reservasi
    Route::get('/dashboard', [ReservasiController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservasi/lapangan/{id}', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi/store', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/tiket/{id}', [ReservasiController::class, 'cetakTiket'])->name('reservasi.tiket');

    // Manajemen Hapus / Batal Reservasi
    Route::post('/reservasi/batal/{id}', [ReservasiController::class, 'batalkanReservasi'])->name('reservasi.batal');
    Route::delete('/reservasi/destroy-massal', [ReservasiController::class, 'destroyMassal'])->name('reservasi.destroyMassal');
    Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');

    // Pengaturan Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| 4. ADMIN PANEL ROUTES (Hak Akses Administrator)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Auth Admin Logout
    Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('logout');

    // Overview Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Modul Log & Pengelolaan Reservasi
    Route::prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'reservasi'])->name('index');
        Route::get('/export-excel', [AdminDashboardController::class, 'exportExcel'])->name('exportExcel');
        Route::patch('/{id}/update-status', [AdminDashboardController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/delete-massal', [AdminDashboardController::class, 'deleteReservasiMassal'])->name('deleteMassal');
        Route::delete('/{id}/delete', [AdminDashboardController::class, 'deleteReservasi'])->name('delete');
    });

    // Modul Arena Lapangan
    Route::resource('kelola-lapangan', LapanganController::class)->names([
        'index'   => 'lapangan.index', 
        'create'  => 'lapangan.create', 
        'store'   => 'lapangan.store',
        'edit'    => 'lapangan.edit', 
        'update'  => 'lapangan.update', 
        'destroy' => 'lapangan.destroy',
    ])->parameters(['kelola-lapangan' => 'lapangan']);

    // Modul Data Member
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'member'])->name('index');
        Route::get('/{id}/edit', [AdminDashboardController::class, 'editMember'])->name('edit');
        Route::put('/{id}/update', [AdminDashboardController::class, 'updateMember'])->name('update');
        Route::delete('/{id}/delete', [AdminDashboardController::class, 'deleteMember'])->name('delete');
    });

    // Modul Hak Akses / Role Admin
    Route::get('/role', [AdminDashboardController::class, 'role'])->name('role.index');
    Route::put('/role/{id}', [AdminDashboardController::class, 'updateRole'])->name('role.update');

    // 🛡️ Terminal Gate Scanner
    Route::get('/staff/scan', function () { 
        return view('staff.scan'); 
    })->name('staff.scan');
    
    Route::post('/staff/checkin', [ReservasiController::class, 'processStaffCheckIn'])->name('staff.checkin');
});

require __DIR__.'/auth.php';