<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\LapanganController; // 🏟️ Impor Kontroler Lapangan Baru
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. PUBLIC ROUTES (Bisa Diakses Tanpa Login)
// ==========================================

// Landing Page Utama Futsal Mare
Route::get('/', [ReservasiController::class, 'landingPage'])->name('landingPage');
Route::get('/lapangan/{id}', [ReservasiController::class, 'showLapangan'])->name('lapangan.detail');

// ==========================================
// 🛡️ 1b. PORTAL AUTHENTICATION ADMIN GATEWAY
// ==========================================
// Diletakkan secara publik agar halaman login admin bisa diakses sebelum masuk dashboard
Route::get('/admin/login', [AdminDashboardController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminDashboardController::class, 'login'])->name('admin.login.submit');


// ==========================================
// 2. GOOGLE OAUTH ROUTES (Proses Autentikasi)
// ==========================================
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


// ==========================================
// 3. PROTECTED ROUTES (Wajib Login / Auth)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Member & Riwayat Reservasi
    Route::get('/dashboard', [ReservasiController::class, 'dashboard'])->name('dashboard');

    // 🌟 KEMBALI KE SEMULA: Rute Alur Form Booking Member Berfungsi Sempurna
    Route::get('/reservasi/lapangan/{id}', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi/store', [ReservasiController::class, 'store'])->name('reservasi.store');
    
    // Rute untuk mengakses cetak E-Tiket QR Code Futsal Mare
    Route::get('/reservasi/tiket/{id}', [ReservasiController::class, 'cetakTiket'])->name('reservasi.tiket');

    // Alur Staff / Gate Scanner Real-Time
    Route::get('/staff/scan', function() { return view('staff.scan'); })->name('staff.scan');
    Route::post('/staff/checkin', [ReservasiController::class, 'processStaffCheckIn'])->name('staff.checkin');

    // ❌ KENDALI UTAMA PEMBATALAN (Diletakkan di atas rute wildcard {id} lainnya agar aman)
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

// ==========================================
// 5. INJECT AUTHENTICATION ROUTES (Breeze Fix)
// ==========================================
// 🌟 BARIS SAKTI PENYELAMAT ERROR LOGIN:
require __DIR__.'/auth.php';