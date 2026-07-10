<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            // Menambahkan kolom deskripsi setelah harga_per_jam dan bersifat opsional (nullable)
            $table->text('deskripsi')->nullable()->after('harga_per_jam');
        });
    }

    public function down(): void
    {
        Schema::table('lapangans', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
    }
};