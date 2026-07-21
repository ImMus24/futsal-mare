<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    use HasFactory;

    // Konstanta Tier untuk menghindari typo / magic strings
    public const TIER_GOLD   = 'Gold';
    public const TIER_SILVER = 'Silver';
    public const TIER_BRONZE = 'Bronze';

    // Ambang batas poin untuk upgrade tier
    public const POINTS_GOLD   = 300;
    public const POINTS_SILVER = 100;

    protected $fillable = [
        'user_id',
        'membership_type',
        'points',
    ];

    /**
     * Casting tipe data bawaan Eloquent.
     */
    protected $casts = [
        'points' => 'integer',
    ];

    /**
     * Mendapatkan persentase diskon bulat (misal: 10 untuk 10%) berdasarkan tier.
     * Akses via Eloquent: $membership->discount_percent
     */
    public function getDiscountPercentAttribute(): int
    {
        return self::getDiskonByTier($this->membership_type);
    }

    /**
     * Helper Static untuk mendapatkan persentase diskon langsung dari string tier.
     * Contoh: Membership::getDiskonByTier('Gold') -> returns 10
     */
    public static function getDiskonByTier(?string $membershipType): int
    {
        return match ($membershipType) {
            self::TIER_GOLD   => 10,
            self::TIER_SILVER => 5,
            default           => 0, // Default Bronze atau null = 0%
        };
    }

    /**
     * Helper Static untuk menentukan Tier berdasarkan jumlah poin.
     * Contoh: Membership::determineTierByPoints(150) -> returns 'Silver'
     */
    public static function determineTierByPoints(int $points): string
    {
        return match (true) {
            $points >= self::POINTS_GOLD   => self::TIER_GOLD,
            $points >= self::POINTS_SILVER => self::TIER_SILVER,
            default                        => self::TIER_BRONZE,
        };
    }

    /**
     * Method bisnis untuk menambah poin & otomatis meng-update tier.
     * Penggunaan: $membership->addPointsAndEvaluateTier(15);
     */
    public function addPointsAndEvaluateTier(int $addedPoints): void
    {
        $this->points += $addedPoints;
        $this->membership_type = self::determineTierByPoints($this->points);
        $this->save();
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}