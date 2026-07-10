<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('promo_code')->unique(); // Contoh: FUTSAL10 (tidak boleh kembar)
            $table->integer('discount_percent');   // Nilai persenan diskon, misal: 10 untuk 10%
            $table->date('expired_at');            // Tanggal kedaluwarsa voucher
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};