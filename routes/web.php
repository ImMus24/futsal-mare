<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\LapanganController;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES
Route::get('/', [ReservasiController::class, 'landingPage'])->name('landingPage');
Route::get('/lapangan/{id}', [ReservasiController::class, 'showLapangan'])->name('lapangan.detail');

// ==========================================
// 🛡️ 1b. PORTAL AUTHENTICATION ADMIN GATEWAY
// ==========================================
// Diletakkan secara publik agar halaman login admin bisa diakses sebelum masuk dashboard
Route::get('/admin/login', [AdminDashboardController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminDashboardController::class, 'login'])->name('admin.login.submit');

// 2. GOOGLE OAUTH
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// 3. PROTECTED ROUTES (Member)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Member & Riwayat Reservasi
    Route::get('/dashboard', [ReservasiController::class, 'dashboard'])->name('dashboard');

    // Dashboard & Reservasi
    Route::get('/dashboard', [ReservasiController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservasi/lapangan/{id}', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi/store', [ReservasiController::class, 'store'])->name('reservasi.store');
    
    // Rute untuk mengakses cetak E-Tiket QR Code Futsal Mare
    Route::get('/reservasi/tiket/{id}', [ReservasiController::class, 'cetakTiket'])->name('reservasi.tiket');

    Route::post('/reservasi/batal/{id}', [ReservasiController::class, 'batalkanReservasi'])->name('reservasi.batal');
    
    // 👑 MANAJEMEN PEMBERSIHAN RIWAYAT: Massal & Tunggal
    Route::delete('/reservasi/destroy-massal', [ReservasiController::class, 'destroyMassal'])->name('reservasi.destroyMassal');
    Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
    
    // Manajemen Profil Pengguna (Bawaan Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==========================================
// 4. ADMIN PANEL ROUTES (Versi Sempurna & Kompleks)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // 📊 Modul 1: Overview Dashboard Utama Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // 📅 Modul 2: Data Seluruh Reservasi Masuk & Fitur Ekspor Excel
    // 📝 Amankan Urutan: Taruh rute statis excel di atas agar tidak termakan parameter dinamis resource
    Route::get('/reservasi/export-excel', [AdminDashboardController::class, 'exportExcel'])->name('reservasi.exportExcel');
    Route::get('/reservasi', [AdminDashboardController::class, 'reservasi'])->name('reservasi.index');
    Route::get('/reservasi/export-excel', [AdminDashboardController::class, 'exportExcel'])->name('reservasi.exportExcel');
    Route::patch('/reservasi/{id}/update-status', [AdminDashboardController::class, 'updateStatus'])->name('reservasi.updateStatus');
    Route::delete('/reservasi/{id}/delete', [AdminDashboardController::class, 'deleteReservasi'])->name('reservasi.delete');
    
    // 👥 Modul 2b: Manajemen Aksi Modifikasi & Poin Member
    Route::get('/member/{id}/edit', [AdminDashboardController::class, 'editMember'])->name('member.edit');
    Route::put('/member/{id}/update', [AdminDashboardController::class, 'updateMember'])->name('member.update');
    Route::delete('/member/{id}/delete', [AdminDashboardController::class, 'deleteMember'])->name('member.delete');
    
    // 🏟️ Modul 3: Kelola Lapangan / Arena Inventaris
    Route::resource('kelola-lapangan', LapanganController::class)->names([
        'index'   => 'lapangan.index',
        'create'  => 'lapangan.create',
        'store'   => 'lapangan.store',
        'edit'    => 'lapangan.edit',
        'update'  => 'lapangan.update',
        'destroy' => 'lapangan.destroy',
    ])->parameters([
        'kelola-lapangan' => 'lapangan'
    ]);

    // 👥 Modul 4: Data Member & Loyalitas Poin Gamifikasi
    Route::get('/member', [AdminDashboardController::class, 'member'])->name('member.index');
});

require __DIR__.'/auth.php';