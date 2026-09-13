<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsetWakaf;

class WakafController extends Controller
{
    // Halaman Peta Utama
    public function index()
    {
        $asetWakaf = AsetWakaf::all();
        return view('welcome', compact('asetWakaf'));
    }

    // Halaman Tabel Admin
    public function admin()
    {
        $asetWakaf = AsetWakaf::all();
        return view('admin.index', compact('asetWakaf'));
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
            'jenis_hak'         => 'nullable|string',
            'nomor_hak'         => 'nullable|string',
            'luas_tanah'        => 'required|numeric',
        ]);

        AsetWakaf::create($request->all());

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
            'luas_tanah'        => 'required|numeric',
        ]);

        $aset = AsetWakaf::findOrFail($id);
        $aset->update($request->all());

        return redirect()->route('admin.index')->with('success', 'Data aset wakaf berhasil diperbarui!');
    }

    // Hapus Data
    public function destroy($id)
    {
        $aset = AsetWakaf::findOrFail($id);
        $aset->delete();

        return redirect()->route('admin.index')->with('success', 'Data aset wakaf berhasil dihapus!');
    }
}