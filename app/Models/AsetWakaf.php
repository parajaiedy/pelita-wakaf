<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetWakaf extends Model
{
    use HasFactory;

    protected $table = 'aset_wakaf';

    protected $fillable = [
        'nama_masjid',
        'kecamatan',
        'kelurahan',
        'latitude',
        'longitude',
        'status_sertipikat',
        'kategori',
        'status_tindak_lanjut',
        'jenis_hak',
        'nomor_hak',
        'luas_tanah',
    ];
}