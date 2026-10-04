<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $path = database_path('data/aset_pemkot.json');

        if (! file_exists($path)) {
            return;
        }

        $rows = json_decode(file_get_contents($path), true);

        if (! is_array($rows)) {
            return;
        }

        $now = now();

        $records = array_map(function ($row) use ($now) {
            return [
                'nama_masjid'        => $row['nama_masjid'],
                'kecamatan'          => $row['kecamatan'],
                'kelurahan'          => $row['kelurahan'],
                'latitude'           => $row['latitude'],
                'longitude'          => $row['longitude'],
                'status_sertipikat'  => 'Sudah Bersertipikat',
                'kategori'           => 'Aset Pemerintah',
                'status_tindak_lanjut' => 'Selesai',
                'jenis_hak'          => 'Hak Pakai',
                'nomor_hak'          => $row['nomor_hak'],
                'luas_tanah'         => (int) $row['luas_tanah'],
                'created_at'         => $now,
                'updated_at'         => $now,
            ];
        }, $rows);

        foreach (array_chunk($records, 200) as $chunk) {
            DB::table('aset_wakaf')->insert($chunk);
        }
    }

    public function down(): void
    {
        DB::table('aset_wakaf')->where('kategori', 'Aset Pemerintah')->delete();
    }
};
