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

// 🛡️ ADMIN AUTH GATEWAY
Route::get('/admin/login', [AdminDashboardController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminDashboardController::class, 'login'])->name('admin.login.submit');

// 2. GOOGLE OAUTH
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// 3. PROTECTED ROUTES (Member)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // PEMBATALAN & KONFIRMASI (Fix Error 500)
    Route::post('/reservasi/{nomor_reservasi}/batal-instan', [ReservasiController::class, 'cancelPendingInstant'])->name('reservasi.cancelInstant');
    Route::post('/reservasi/confirm-payment/{nomor_reservasi}', [ReservasiController::class, 'confirmPayment'])->name('reservasi.confirmPayment');

    // Dashboard & Reservasi
    Route::get('/dashboard', [ReservasiController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservasi/lapangan/{id}', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi/store', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/tiket/{id}', [ReservasiController::class, 'cetakTiket'])->name('reservasi.tiket');
    
    // Staff & Kendali
    Route::get('/staff/scan', function() { return view('staff.scan'); })->name('staff.scan');
    Route::post('/staff/checkin', [ReservasiController::class, 'processStaffCheckIn'])->name('staff.checkin');
    Route::post('/reservasi/batal/{id}', [ReservasiController::class, 'batalkanReservasi'])->name('reservasi.batal');
    
    Route::delete('/reservasi/destroy-massal', [ReservasiController::class, 'destroyMassal'])->name('reservasi.destroyMassal');
    Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. ADMIN PANEL ROUTES
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Reservasi Admin
    Route::get('/reservasi', [AdminDashboardController::class, 'reservasi'])->name('reservasi.index');
    Route::get('/reservasi/export-excel', [AdminDashboardController::class, 'exportExcel'])->name('reservasi.exportExcel');
    Route::patch('/reservasi/{id}/update-status', [AdminDashboardController::class, 'updateStatus'])->name('reservasi.updateStatus');
    Route::delete('/reservasi/{id}/delete', [AdminDashboardController::class, 'deleteReservasi'])->name('reservasi.delete');
    Route::delete('/reservasi/delete-massal', [AdminDashboardController::class, 'deleteReservasiMassal'])->name('reservasi.deleteMassal');
    
    // Lapangan
    Route::resource('kelola-lapangan', LapanganController::class)->names([
        'index' => 'lapangan.index', 'create' => 'lapangan.create', 'store' => 'lapangan.store',
        'edit' => 'lapangan.edit', 'update' => 'lapangan.update', 'destroy' => 'lapangan.destroy',
    ])->parameters(['kelola-lapangan' => 'lapangan']);

    // Member
    Route::get('/member', [AdminDashboardController::class, 'member'])->name('member.index');
    Route::get('/member/{id}/edit', [AdminDashboardController::class, 'editMember'])->name('member.edit');
    Route::put('/member/{id}/update', [AdminDashboardController::class, 'updateMember'])->name('member.update');
    Route::delete('/member/{id}/delete', [AdminDashboardController::class, 'deleteMember'])->name('member.delete');

    // Role
    Route::get('/role', [AdminDashboardController::class, 'role'])->name('role.index');
    Route::put('/role/{id}', [AdminDashboardController::class, 'updateRole'])->name('role.update');
});

require __DIR__.'/auth.php';