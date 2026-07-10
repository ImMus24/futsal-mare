<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangans';

    protected $fillable = [
        'nama_lapangan',
        'jenis_rumput',
        'harga_per_jam',
        'foto_lapangan',
        'deskripsi' // 🌟 TAMBAHKAN INI
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'lapangan_id');
    }
}