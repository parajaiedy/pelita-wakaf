<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aset Wakaf - Parepare</title>
    @include('partials.styles')
    <style>
        body { background: #fff; color: #111827; }
        .report-page { max-width: 1120px; margin: 0 auto; padding: 34px 28px; }
        .report-header { border-bottom: 3px solid var(--brand-700); padding-bottom: 18px; margin-bottom: 22px; }
        .report-logo { width: 52px; height: 52px; border-radius: 14px; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-size:24px; background:linear-gradient(135deg,var(--brand-500),var(--gold-400)); }
        .report-meta { color:#64748b; font-size: 12px; }
        .summary-card { border: 1px solid #dbe4e8; border-top: 4px solid var(--brand-600); border-radius: 12px; padding: 15px; height: 100%; }
        .summary-card.success { border-top-color: #16a34a; }
        .summary-card.danger { border-top-color: #dc2626; }
        .summary-card.gold { border-top-color: var(--gold-500); }
        .summary-label { color:#64748b; font-size:12px; }
        .summary-value { font-size:24px; font-weight:800; color:#0f172a; }
        .report-table { width:100%; border-collapse: collapse; font-size: 10px; margin-top: 16px; }
        .report-table th { background:#134e4a; color:#fff; padding:8px 6px; text-align:left; }
        .report-table td { border-bottom:1px solid #e2e8f0; padding:7px 6px; vertical-align:top; }
        .report-table tr:nth-child(even) td { background:#f8fafc; }
        .status-ok { color:#15803d; font-weight:700; }
        .status-no { color:#b91c1c; font-weight:700; }
        .report-footer { margin-top: 26px; border-top:1px solid #cbd5e1; padding-top:10px; color:#64748b; font-size:10px; }
        .print-actions { position:fixed; top:18px; right:20px; z-index:5; }
        @media print {
            @page { size: A4 landscape; margin: 12mm; }
            .print-actions { display:none !important; }
            .report-page { max-width:none; padding:0; }
            .report-table { font-size: 8.5px; }
            .report-table th, .report-table td { padding: 5px 4px; }
            .report-header { break-inside: avoid; }
        }
    </style>
</head>
<body>
    @php
        $total = $asets->count();
        $sudah = $asets->where('status_sertipikat', 'Sudah Bersertipikat')->count();
        $belum = $asets->where('status_sertipikat', 'Belum Bersertipikat')->count();
        $luas = $asets->sum('luas_tanah');
        $persentase = $total ? round(($sudah / $total) * 100, 1) : 0;
        $tahapTindakLanjut = ['Belum Ditindaklanjuti', 'Pengumpulan Berkas', 'Pengukuran', 'Proses Sertipikasi', 'Selesai'];
        $jumlahTindakLanjut = collect($tahapTindakLanjut)->mapWithKeys(fn ($tahap) => [$tahap => $asets->where('status_tindak_lanjut', $tahap)->count()]);
        $perKecamatan = $asets->groupBy('kecamatan');
    @endphp

    <div class="print-actions">
        <button type="button" class="btn btn-brand shadow-sm" onclick="window.print()">
            <i class="fa-solid fa-print me-1"></i> Cetak / Simpan PDF
        </button>
        <a href="{{ route('admin.index') }}" class="btn btn-light shadow-sm ms-1">Kembali</a>
    </div>

    <main class="report-page">
        <header class="report-header d-flex align-items-center gap-3">
            <span class="report-logo"><i class="fa-solid fa-mosque"></i></span>
            <div>
                <h1 class="h3 fw-bold mb-1">LAPORAN ASET WAKAF</h1>
                <h2 class="h6 text-secondary mb-1">Kota Parepare — Pelita Wakaf</h2>
                <div class="report-meta">BPN Kota Parepare | Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</div>
            </div>
        </header>

        <section class="row g-3 mb-4">
            <div class="col-3"><div class="summary-card"><div class="summary-label">TOTAL ASET</div><div class="summary-value">{{ number_format($total) }}</div><small class="text-muted">lokasi terpetakan</small></div></div>
            <div class="col-3"><div class="summary-card success"><div class="summary-label">SUDAH BERSERTIPIKAT</div><div class="summary-value text-success">{{ number_format($sudah) }}</div><small class="text-success fw-semibold">{{ number_format($persentase, 1, ',', '.') }}% dari total</small></div></div>
            <div class="col-3"><div class="summary-card danger"><div class="summary-label">BELUM BERSERTIPIKAT</div><div class="summary-value text-danger">{{ number_format($belum) }}</div><small class="text-danger fw-semibold">perlu ditindaklanjuti</small></div></div>
            <div class="col-3"><div class="summary-card gold"><div class="summary-label">TOTAL LUAS TANAH</div><div class="summary-value">{{ number_format($luas) }}</div><small class="text-muted">meter persegi (m²)</small></div></div>
        </section>

        <section class="mb-4">
            <h3 class="h6 fw-bold mb-2"><i class="fa-solid fa-route me-2 text-success"></i>Rekapitulasi Tindak Lanjut</h3>
            <table class="report-table" style="width:60%;">
                <thead><tr><th>Tahap</th><th>Jumlah Aset</th></tr></thead>
                <tbody>
                @foreach($tahapTindakLanjut as $tahap)
                    <tr><td>{{ $tahap }}</td><td>{{ $jumlahTindakLanjut[$tahap] }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </section>

        <section class="mb-4">
            <h3 class="h6 fw-bold mb-2"><i class="fa-solid fa-chart-column me-2 text-success"></i>Rekapitulasi per Kecamatan</h3>
            <table class="report-table" style="width:45%;">
                <thead><tr><th>Kecamatan</th><th>Total Aset</th><th>Sudah Sertipikat</th><th>Belum Sertipikat</th></tr></thead>
                <tbody>
                @foreach($perKecamatan as $kecamatan => $items)
                    <tr><td>{{ $kecamatan }}</td><td>{{ $items->count() }}</td><td class="status-ok">{{ $items->where('status_sertipikat', 'Sudah Bersertipikat')->count() }}</td><td class="status-no">{{ $items->where('status_sertipikat', 'Belum Bersertipikat')->count() }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </section>

        <section>
            <h3 class="h6 fw-bold mb-2"><i class="fa-solid fa-list me-2 text-success"></i>Daftar Aset Wakaf</h3>
            <table class="report-table">
                <thead><tr><th>No</th><th>Nama Masjid / Tanah</th><th>Kecamatan</th><th>Kelurahan</th><th>Status Sertipikat</th><th>Tindak Lanjut</th><th>Jenis Hak</th><th>Nomor Hak</th><th>Luas (m²)</th></tr></thead>
                <tbody>
                @foreach($asets as $index => $aset)
                    <tr>
                        <td>{{ $index + 1 }}</td><td>{{ $aset->nama_masjid }}</td><td>{{ $aset->kecamatan }}</td><td>{{ $aset->kelurahan }}</td>
                        <td class="{{ $aset->status_sertipikat === 'Sudah Bersertipikat' ? 'status-ok' : 'status-no' }}">{{ $aset->status_sertipikat }}</td>
                        <td>{{ $aset->status_tindak_lanjut ?? 'Belum Ditindaklanjuti' }}</td>
                        <td>{{ $aset->jenis_hak ?? '-' }}</td><td>{{ $aset->nomor_hak ?? '-' }}</td><td>{{ number_format($aset->luas_tanah) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>

        <footer class="report-footer d-flex justify-content-between">
            <span>Pelita Wakaf — BPN Kota Parepare</span>
            <span>Dokumen dihasilkan oleh sistem | {{ now()->format('Y') }}</span>
        </footer>
    </main>
</body>
</html>
