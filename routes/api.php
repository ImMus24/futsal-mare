<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasiController; // Arahkan ke ReservasiController

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Jalur rute yang sinkron dengan dashboard Midtrans dan ReservasiController Anda
Route::post('/midtrans-callback', [ReservasiController::class, 'handleNotification']);