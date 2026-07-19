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
// 🔔 1a. MIDTRANS SERVER-TO-SERVER WEBHOOK
// ==========================================
// PENTING #1: route ini SENGAJA publik (tanpa middleware 'auth') — yang memanggilnya
// adalah server Midtrans, bukan user yang login lewat browser, jadi tidak ada
// sesi/cookie sama sekali. Keamanannya ditangani di dalam handleNotification()
// sendiri lewat verifikasi signature_key + kecocokan gross_amount.
//
// PENTING #2: route POST publik dari luar seperti ini WAJIB dikecualikan dari
// verifikasi CSRF, karena Midtrans tidak mengirim token CSRF Laravel. Tambahkan
// di bootstrap/app.php:
//
//     ->withMiddleware(function (Middleware $middleware) {
//         $middleware->validateCsrfTokens(except: [
//             'midtrans/notification',
//         ]);
//     })
//
// Daftarkan URL lengkap https://domainmu.com/midtrans/notification sebagai
// "Payment Notification URL" di Midtrans Dashboard → Settings → Configuration.
// (Untuk development lokal, URL ini tidak akan pernah kepanggil oleh Midtrans
// karena localhost tidak reachable dari internet — makanya endpoint
// confirmPayment() di bawah dibuat sebagai jalur konfirmasi sisi klien.)
Route::post('/midtrans/notification', [ReservasiController::class, 'handleNotification'])
    ->name('midtrans.notification');

// ==========================================
// 🛡️ 1b. PORTAL AUTHENTICATION ADMIN GATEWAY
// ==========================================
// Diletakkan secara publik agar halaman login admin bisa diakses sebelum masuk dashboard
// PENTING: rute ini SENGAJA tidak diberi middleware 'admin' — orang yang belum
// login justru butuh akses ke halaman login ini.
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

    // 🔴 PEMBATALAN INSTAN saat user menutup popup Midtrans (onClose di create.blade.php).
    // PENTING: nama route ini HARUS 'reservasi.cancelInstant' — itu yang dipanggil oleh
    // route('reservasi.cancelInstant', ['nomor_reservasi' => ...]) di create.blade.php.
    Route::post('/reservasi/{nomor_reservasi}/batal-instan', [ReservasiController::class, 'cancelPendingInstant'])
        ->name('reservasi.cancelInstant');

    // 🟢 KONFIRMASI PEMBAYARAN INSTAN dari sisi klien (dipanggil dari onSuccess Snap.js).
    // Jalur ini yang membuat status langsung "lunas" di dashboard tanpa menunggu
    // webhook/admin — lihat komentar lengkap di ReservasiController::confirmPayment().
    Route::post('/reservasi/{nomor_reservasi}/confirm-payment', [ReservasiController::class, 'confirmPayment'])
        ->name('reservasi.confirmPayment');

    // 🔍 Polling status reservasi (dipakai frontend setelah onSuccess/onPending
    // untuk memastikan status terbaru sebelum redirect final ke dashboard).
    Route::get('/reservasi/{nomor_reservasi}/status', [ReservasiController::class, 'checkStatus'])
        ->name('reservasi.checkStatus');

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
// 4. ADMIN PANEL ROUTES (DIAMANKAN)
// ==========================================
// PENTING: middleware 'admin' ditambahkan di sini — sebelumnya cuma 'auth',
// sehingga siapa pun yang login (bukan hanya admin) bisa mengakses grup ini.
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // 📊 Dashboard Utama
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 📅 Reservasi
    Route::get('/reservasi/export-excel', [AdminDashboardController::class, 'exportExcel'])->name('reservasi.exportExcel');
    Route::get('/reservasi', [AdminDashboardController::class, 'reservasi'])->name('reservasi.index');
    Route::patch('/reservasi/{id}/update-status', [AdminDashboardController::class, 'updateStatus'])->name('reservasi.updateStatus');
    Route::delete('/reservasi/{id}/delete', [AdminDashboardController::class, 'deleteReservasi'])->name('reservasi.delete');
Route::delete('/reservasi/delete-massal', [AdminDashboardController::class, 'deleteReservasiMassal'])
    ->name('reservasi.deleteMassal');
    
    // ❌ DIHAPUS: route ini sebelumnya menabrak nama 'reservasi.cancelInstant' (jadi
    // 'admin.reservasi.cancelInstant' karena prefix grup ini) dan menunjuk ke method
    // cancelInstant() yang TIDAK ADA di ReservasiController. Route yang benar untuk
    // pembatalan instan customer sudah dipindahkan ke grup 'auth' di atas, memakai
    // nama 'reservasi.cancelInstant' dan method cancelPendingInstant() yang memang ada.
    //
    // Route::post('/reservasi/cancel-instant/{nomor_reservasi}', [App\Http\Controllers\ReservasiController::class, 'cancelInstant'])
    //     ->name('reservasi.cancelInstant');

    // 🏟️ Kelola Lapangan
    Route::resource('kelola-lapangan', LapanganController::class)->names([
        'index' => 'lapangan.index', 'create' => 'lapangan.create', 'store' => 'lapangan.store',
        'edit' => 'lapangan.edit', 'update' => 'lapangan.update', 'destroy' => 'lapangan.destroy',
    ])->parameters(['kelola-lapangan' => 'lapangan']);

    // 👥 Manajemen Member & Loyalitas
    Route::get('/member', [AdminDashboardController::class, 'member'])->name('member.index');
    Route::get('/member/{id}/edit', [AdminDashboardController::class, 'editMember'])->name('member.edit');
    Route::put('/member/{id}/update', [AdminDashboardController::class, 'updateMember'])->name('member.update');
    Route::delete('/member/{id}/delete', [AdminDashboardController::class, 'deleteMember'])->name('member.delete');

    // 🚀 MODUL MANAJEMEN ROLE
    Route::get('/role', [AdminDashboardController::class, 'role'])->name('role.index');
    Route::put('/role/{id}', [AdminDashboardController::class, 'updateRole'])->name('role.update');
});

// ==========================================
// 5. INJECT AUTHENTICATION ROUTES (Breeze Fix)
// ==========================================
// 🌟 BARIS SAKTI PENYELAMAT ERROR LOGIN:
require __DIR__.'/auth.php';