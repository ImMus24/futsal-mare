<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // Mengubah kolom status menjadi string biasa agar muat teks panjang apa saja
            $table->string('status', 50)->default('Waiting Payment')->change();
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // Kembalikan ke pengaturan awal jika diperlukan
        });
    }
};