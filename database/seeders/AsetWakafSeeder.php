<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AsetWakaf;

class AsetWakafSeeder extends Seeder
{
    public function run(): void
    {
        AsetWakaf::insert([
            [
                'nama_masjid' => 'Masjid Terapung B.J. Habibie',
                'kecamatan' => 'Bacukiki Barat',
                'kelurahan' => 'Cappa Galung',
                'latitude' => -4.03264100,
                'longitude' => 119.61795500,
                'status_sertipikat' => 'Sudah Bersertipikat',
                'luas_tanah' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_masjid' => 'Masjid Agung Parepare',
                'kecamatan' => 'Soreang',
                'kelurahan' => 'Ujung Baru',
                'latitude' => -3.99812000,
                'longitude' => 119.64210000,
                'status_sertipikat' => 'Sudah Bersertipikat',
                'luas_tanah' => 3500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_masjid' => 'Masjid Raya Parepare',
                'kecamatan' => 'Ujung',
                'kelurahan' => 'Ujung Sabbang',
                'latitude' => -4.01253400,
                'longitude' => 119.62638800,
                'status_sertipikat' => 'Belum Bersertipikat',
                'luas_tanah' => 1250,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_masjid' => 'Masjid At-Tauhid Parepare',
                'kecamatan' => 'Soreang',
                'kelurahan' => 'Watang Soreang',
                'latitude' => -3.99500000,
                'longitude' => 119.63500000,
                'status_sertipikat' => 'Belum Bersertipikat',
                'luas_tanah' => 450,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_masjid' => 'Masjid Besar Nurul Huda',
                'kecamatan' => 'Ujung',
                'kelurahan' => 'Mallusetasi',
                'latitude' => -4.02000000,
                'longitude' => 119.62800000,
                'status_sertipikat' => 'Sudah Bersertipikat',
                'luas_tanah' => 900,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}