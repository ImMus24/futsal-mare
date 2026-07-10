<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('memberships', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel users secara cascade (jika user dihapus, membership ikut terhapus)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Menyimpan status tier: Bronze, Silver, atau Gold
        $table->string('membership_type')->default('Bronze');
        // Menyimpan akumulasi poin member
        $table->integer('points')->default(0);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};