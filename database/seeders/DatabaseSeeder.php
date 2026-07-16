<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Mengubah Akun Penguji Bawaan menjadi updateOrCreate agar tidak memicu duplicate entry
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'), // Berikan password bawaan
            ]
        );

        // 🛡️ 2. MENYUNTIKKAN KREDENSIAL ADMINISTRATOR UTAMA FUTSAL MARE (Aman dari Duplikasi)
        User::updateOrCreate(
            ['email' => 'adminfutsal@gmail.com'],
            [
                'name'              => 'Super Admin Futsal Mare',
                'password'          => Hash::make('adminfutsal123'),
                'is_admin'          => true, // Flag hak akses penuh portal admin
                'email_verified_at' => now(),
            ]
        );

        // 3. Memanggil Data Lapangan Futsal Kota Baubau
        $this->call([
            LapanganSeeder::class,
        ]);
    }
}