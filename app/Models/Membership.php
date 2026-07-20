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
     * Mendapatkan persentase diskon berdasarkan tier membership.
     * Penggunaan: $membership->discount_percent
     */
    public function getDiscountPercentAttribute(): float
    {
        return match ($this->membership_type) {
            'Gold'   => 0.20, // 20%
            'Silver' => 0.10, // 10%
            'Bronze' => 0.05, // 5%
            default  => 0.00,
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}