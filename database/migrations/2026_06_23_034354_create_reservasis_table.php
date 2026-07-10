<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lapangan_id')->constrained()->onDelete('cascade');
            $table->string('nomor_reservasi')->unique(); 
            $table->date('tanggal_main');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('total_harga'); 
            
            // Ubah menjadi nullable karena baru terisi setelah callback Midtrans sukses lunas
            $table->string('metode_pembayaran')->nullable();
            $table->string('snap_token', 255)->nullable(); // <-- WAJIB TAMBAHKAN INI untuk menyimpan token Midtrans
            
            $table->string('bukti_pembayaran')->nullable(); // Biarkan untuk cadangan lama
            $table->enum('status', ['Pending', 'Waiting Payment', 'Confirmed', 'Completed', 'Cancelled'])->default('Waiting Payment');
            $table->boolean('is_checked_in')->default(false); 
            $table->string('qr_code_path')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};