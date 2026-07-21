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
     * Mendapatkan persentase diskon bulat (misal: 10 untuk 10%) berdasarkan tier.
     * Penggunaan di Eloquent: $membership->discount_percent
     */
    public function getDiscountPercentAttribute(): int
    {
        return self::getDiskonByTier($this->membership_type);
    }

    /**
     * Helper Static untuk mendapatkan persentase diskon langsung dari string tier.
     * Penggunaan: Membership::getDiskonByTier('Gold') -> returns 10
     */
    public static function getDiskonByTier(?string $membershipType): int
    {
        return match ($membershipType) {
            'Gold'   => 10, // Ubah sesuai standar bisnis kamu (misal 10 atau 20)
            'Silver' => 5,  // Ubah sesuai standar bisnis kamu (misal 5 atau 10)
            'Bronze' => 0,  // Bronze default 0%
            default  => 0,
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}