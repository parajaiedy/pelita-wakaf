<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsetWakaf;

class WakafController extends Controller
{
    // Halaman Beranda / Welcome
    public function index()
    {
        $asetWakaf = AsetWakaf::all();
        return view('welcome', compact('asetWakaf'));
    }

    // Halaman Peta Publik Full Screen (Ini fungsi baru kita, Pak!)
    public function petaPublik()
    {
        // Mengambil semua data dari model yang dijamin benar
        $asets = AsetWakaf::all();
        return view('peta_publik', compact('asets'));
    }

    // Halaman Tabel Admin: statistik lengkap + daftar aset yang dapat dicari/difilter
    public function admin(Request $request)
    {
        $semuaAset = AsetWakaf::orderBy('nama_masjid')->get();

        $query = AsetWakaf::query()->orderBy('nama_masjid');

        if ($request->filled('q')) {
            $keyword = trim($request->string('q')->toString());
            $query->where(function ($builder) use ($keyword) {
                $builder->where('nama_masjid', 'like', "%{$keyword}%")
                    ->orWhere('kelurahan', 'like', "%{$keyword}%")
                    ->orWhere('nomor_hak', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->string('kecamatan')->toString());
        }

        if ($request->filled('status')) {
            $query->where('status_sertipikat', $request->string('status')->toString());
        }

        if ($request->filled('jenis_hak')) {
            $query->where('jenis_hak', $request->string('jenis_hak')->toString());
        }

        if ($request->filled('tindak_lanjut')) {
            $query->where('status_tindak_lanjut', $request->string('tindak_lanjut')->toString());
        }

        $asetWakaf = $query->paginate(15)->withQueryString();
        $kecamatanList = $semuaAset->pluck('kecamatan')->filter()->unique()->sort()->values();
        $jenisHakList = $semuaAset->pluck('jenis_hak')->filter()->unique()->sort()->values();
        $tindakLanjutList = $semuaAset->pluck('status_tindak_lanjut')->filter()->unique()->sort()->values();

        return view('admin.index', compact('asetWakaf', 'semuaAset', 'kecamatanList', 'jenisHakList', 'tindakLanjutList'));
    }

    // Laporan resmi yang siap dicetak / disimpan sebagai PDF dari browser
    public function laporanPdf()
    {
        $asets = AsetWakaf::orderBy('kecamatan')->orderBy('kelurahan')->orderBy('nama_masjid')->get();

        return view('admin.laporan', compact('asets'));
    }

    // Halaman Form Tambah Data
    public function create()
    {
        return view('admin.create');
    }

    // Simpan Data Baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'nama_masjid'       => 'required',
            'kecamatan'         => 'required',
            'kelurahan'         => 'required',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'status_sertipikat' => 'required',
            'status_tindak_lanjut' => 'required|string|in:Belum Ditindaklanjuti,Pengumpulan Berkas,Pengukuran,Proses Sertipikasi,Selesai',
            'jenis_hak'         => 'nullable|string',
            'nomor_hak'         => 'nullable|string',
            'luas_tanah'        => 'required|numeric',
        ]);

        AsetWakaf::create($request->only([
            'nama_masjid',
            'kecamatan',
            'kelurahan',
            'latitude',
            'longitude',
            'status_sertipikat',
            'status_tindak_lanjut',
            'jenis_hak',
            'nomor_hak',
            'luas_tanah',
        ]));

        return redirect()->route('admin.index')->with('success', 'Data aset wakaf berhasil ditambahkan!');
    }

    // Halaman Form Edit Data
    public function edit($id)
    {
        $aset = AsetWakaf::findOrFail($id);
        return view('admin.edit', compact('aset'));
    }

    // Update Data di Database
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_masjid'       => 'required',
            'kecamatan'         => 'required',
            'kelurahan'         => 'required',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'status_sertipikat' => 'required',
            'status_tindak_lanjut' => 'required|string|in:Belum Ditindaklanjuti,Pengumpulan Berkas,Pengukuran,Proses Sertipikasi,Selesai',
            'jenis_hak'         => 'nullable|string',
            'nomor_hak'         => 'nullable|string',
            'luas_tanah'        => 'required|numeric',
        ]);

        $aset = AsetWakaf::findOrFail($id);
        $aset->update($request->only([
            'nama_masjid',
            'kecamatan',
            'kelurahan',
            'latitude',
            'longitude',
            'status_sertipikat',
            'status_tindak_lanjut',
            'jenis_hak',
            'nomor_hak',
            'luas_tanah',
        ]));

        return redirect()->route('admin.index')->with('success', 'Data aset wakaf berhasil diperbarui!');
    }

    // Hapus Data
    public function destroy($id)
    {
        $aset = AsetWakaf::findOrFail($id);
        $aset->delete();

        return redirect()->route('admin.index')->with('success', 'Data aset wakaf berhasil dihapus!');
    }

    // Export Data ke Excel (CSV + BOM UTF-8 agar karakter Indonesia terbaca rapi di Excel)
    public function exportExcel()
    {
        $asets = AsetWakaf::orderBy('kecamatan')->orderBy('kelurahan')->get();

        $filename = 'aset-wakaf-parepare-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
        ];

        $callback = function () use ($asets) {
            $output = fopen('php://output', 'w');

            // BOM UTF-8 agar Microsoft Excel menampilkan huruf Indonesia dengan benar
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'No',
                'Nama Masjid / Tanah Wakaf',
                'Kecamatan',
                'Kelurahan',
                'Latitude',
                'Longitude',
                'Status Sertipikat',
                'Status Tindak Lanjut',
                'Jenis Hak',
                'Nomor Hak',
                'Luas Tanah (m2)',
            ]);

            $no = 1;
            foreach ($asets as $aset) {
                fputcsv($output, [
                    $no++,
                    $aset->nama_masjid,
                    $aset->kecamatan,
                    $aset->kelurahan,
                    $aset->latitude,
                    $aset->longitude,
                    $aset->status_sertipikat,
                    $aset->status_tindak_lanjut ?? 'Belum Ditindaklanjuti',
                    $aset->jenis_hak ?? '-',
                    $aset->nomor_hak ?? '-',
                    $aset->luas_tanah,
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}