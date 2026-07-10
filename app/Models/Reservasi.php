<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservasi extends Model
{
    use HasFactory;

    // Tambahkan kolom penunjang otomatisasi Midtrans & QR Code ke dalam $fillable
    protected $fillable = [
        'user_id',
        'lapangan_id',
        'nomor_reservasi', // <-- WAJIB MASUK
        'tanggal_main',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'status',
        'metode_pembayaran',
        'snap_token',      // <-- WAJIB MASUK
        'qr_code_path',    // <-- WAJIB MASUK
        'bukti_pembayaran', // (Opsional, biarkan saja jika kolomnya ada di DB)
    ];

    /**
     * Relasi Balik ke Model Lapangan
     */
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }

    /**
     * Relasi Balik ke Model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}