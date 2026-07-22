<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'membership_type', // Nilai: 'Gold', 'Silver', 'Bronze'
        'points',
    ];

    /**
     * Mendapatkan persentase diskon berdasarkan tier membership dalam bentuk desimal/persen.
     * Disinkronkan dengan aturan diskon utama aplikasi.
     */
    public function getDiscountPercentAttribute(): float
    {
        return match (strtolower($this->membership_type ?? 'bronze')) {
            'gold'   => 0.10, // 10%
            'silver' => 0.05, // 5%
            default  => 0.00, // 0% (Bronze / belum ada)
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}