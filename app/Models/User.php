<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // Ditambahkan agar support fitur admin
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean', // Hasil resolusi conflict (dipertahankan)
        ];
    }

    /**
     * 🏆 Relasi One-to-One ke Model Membership
     * Menggunakan withDefault() agar tidak bertabrakan dengan null pointer jika data belum ada.
     */
    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class)->withDefault([
            'membership_type' => Membership::TIER_BRONZE,
            'points'          => 0,
        ]);
    }

    /**
     * 📅 Relasi One-to-Many ke Model Reservasi
     */
    public function reservasis(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods & Accessors (Kemudahan Integrasi)
    |--------------------------------------------------------------------------
    */

    /**
     * Mendapatkan persentase diskon user secara langsung.
     * Contoh: $user->discount_percent (menghasilkan 10, 5, atau 0)
     */
    public function getDiscountPercentAttribute(): int
    {
        return $this->membership->discount_percent;
    }

    /**
     * Mendapatkan instance membership aktif, atau membuat baru jika belum ada di database.
     * Sangat berguna di Controller saat memproses poin pembayaran.
     */
    public function getOrCreateMembership(): Membership
    {
        return $this->membership()->firstOrCreate(
            ['user_id' => $this->id],
            [
                'membership_type' => Membership::TIER_BRONZE,
                'points'          => 0,
            ]
        );
    }
}