<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_lapangan = [
            [
                'nama_lapangan' => 'Wameo Futsal Arena',
                'jenis_rumput' => 'Vinyl Premium',
                'harga_per_jam' => 150000,
                'foto_lapangan' => 'lapangan_wameo.png', // Diubah ke .png
            ],
            [
                'nama_lapangan' => 'Lipu Sport Center',
                'jenis_rumput' => 'Sintetis',
                'harga_per_jam' => 130000,
                'foto_lapangan' => 'lapangan_lipu.png', // Diubah ke .png
            ],
            [
                'nama_lapangan' => 'Batulo Futsal Stadium',
                'jenis_rumput' => 'Interlock',
                'harga_per_jam' => 140000,
                'foto_lapangan' => 'lapangan_batulo.png', // Diubah ke .png
            ],
            [
                'nama_lapangan' => 'Katobengke Match Arena',
                'jenis_rumput' => 'Sintetis',
                'harga_per_jam' => 120000,
                'foto_lapangan' => 'lapangan_katobengke.png', // Diubah ke .png
            ],
            [
                'nama_lapangan' => 'Tanganapada Futsal',
                'jenis_rumput' => 'Vinyl',
                'harga_per_jam' => 135000,
                'foto_lapangan' => 'lapangan_tanganapada.png', // Diubah ke .png
            ],
            [
                'nama_lapangan' => 'Wolio Champion Arena',
                'jenis_rumput' => 'Interlock Premium',
                'harga_per_jam' => 160000,
                'foto_lapangan' => 'lapangan_wolio.png', // Diubah ke .png
            ],
        ];

        foreach ($data_lapangan as $lapangan) {
            Lapangan::updateOrCreate(
                ['nama_lapangan' => $lapangan['nama_lapangan']],
                $lapangan
            );
        }
    }
}