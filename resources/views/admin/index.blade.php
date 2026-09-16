<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pelita Wakaf Parepare</title>
    <!-- Bootstrap 5 CSS, FontAwesome Icons & Chart.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-custom { background-color: #1e293b; color: white; }
        .card-stat { border: none; border-radius: 12px; transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-3px); }
        .table-custom { border-radius: 10px; overflow: hidden; }
        .table-custom thead { background-color: #0f172a; color: white; }
        .chart-card { border: none; border-radius: 12px; }
        /* Tambahan agar badge tidak pecah di HP */
        .badge { white-space: normal; text-align: center; }
        /* Memperlebar kolom agar tidak terlalu berdempetan saat digeser */
        table th, table td { white-space: nowrap; }
    </style>
</head>
<body>

    <!-- Navbar Header -->
    <nav class="navbar navbar-expand-lg navbar-custom px-4 py-3 shadow-sm mb-4">
        <div class="container-fluid flex-wrap gap-2">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="#">
                <i class="fa-solid fa-mosque me-1"></i> <span>Pelita Wakaf Parepare</span>
            </a>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-user me-1"></i> {{ Auth::user()->name }}
                </span>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm px-3">
                    <i class="fa-solid fa-map me-1"></i> Peta Publik
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm px-3">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">

        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Ringkasan Kartu Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-primary text-white shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small mb-1">Total Aset Wakaf</h6>
                            <h3 class="fw-bold mb-0">{{ $asetWakaf->count() }}</h3>
                        </div>
                        <i class="fa-solid fa-mosque fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-success text-white shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small mb-1">Sudah Sertipikat</h6>
                            <h3 class="fw-bold mb-0">{{ $asetWakaf->where('status_sertipikat', 'Sudah Bersertipikat')->count() }}</h3>
                        </div>
                        <i class="fa-solid fa-certificate fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-danger text-white shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small mb-1">Belum Sertipikat</h6>
                            <h3 class="fw-bold mb-0">{{ $asetWakaf->where('status_sertipikat', 'Belum Bersertipikat')->count() }}</h3>
                        </div>
                        <i class="fa-solid fa-file-circle-exclamation fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-warning text-dark shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50 small mb-1">Total Luas (m²)</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($asetWakaf->sum('luas_tanah')) }}</h3>
                        </div>
                        <i class="fa-solid fa-ruler-combined fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Diagram Visualisasi Statistik -->
        <div class="row g-3 mb-4">
            <!-- Diagram Lingkaran Status Sertipikat -->
            <div class="col-12 col-lg-5">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Status Sertipikasi</h5>
                        <div style="height: 250px;" class="d-flex align-items-center justify-content-center">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagram Batang Per Kecamatan -->
            <div class="col-12 col-lg-7">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-chart-column me-2 text-success"></i>Sebaran Aset per Kecamatan</h5>
                        <div style="height: 250px;">
                            <canvas id="kecamatanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Tabel & Tombol Tambah + Export -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
                    <h4 class="fw-bold text-dark mb-0">Daftar Aset Wakaf Parepare</h4>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.exportExcel') }}" class="btn btn-success shadow-sm flex-fill">
                            <i class="fa-solid fa-file-excel me-1"></i> Export
                        </a>
                        <a href="{{ route('admin.create') }}" class="btn btn-primary shadow-sm flex-fill">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Aset
                        </a>
                    </div>
                </div>

                <!-- Bagian Kunci: Tabel Responsif -->
                <div class="table-responsive table-custom shadow-sm border">
                    <table class="table table-hover table-striped align-middle mb-0 text-nowrap">
                        <thead>
                            <tr class="text-center">
                                <th width="4%">No</th>
                                <th class="text-start">Nama Masjid / Tanah</th>
                                <th class="text-start">Wilayah</th>
                                <th>Koordinat</th>
                                <th>Status Sertipikat</th>
                                <th>Jenis & No. Hak</th>
                                <th>Luas (m²)</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asetWakaf as $index => $item)
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td class="text-start">
                                        <div class="fw-bold text-dark">{{ $item->nama_masjid }}</div>
                                    </td>
                                    <td class="text-start">
                                        <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> Kec. {{ $item->kecamatan }} / Kel. {{ $item->kelurahan }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border"><i class="fa-regular fa-compass me-1"></i> {{ $item->latitude }}, {{ $item->longitude }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status_sertipikat == 'Sudah Bersertipikat')
                                            <span class="badge bg-success-subtle text-success border border-success px-3 py-1 rounded-pill">
                                                <i class="fa-solid fa-check-circle me-1"></i> Sudah Bersertipikat
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1 rounded-pill">
                                                <i class="fa-solid fa-xmark-circle me-1"></i> Belum Bersertipikat
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->jenis_hak || $item->nomor_hak)
                                            <span class="badge bg-info-subtle text-info-emphasis border px-2 py-1">
                                                {{ $item->jenis_hak ?? '-' }} No. {{ $item->nomor_hak ?? '-' }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-semibold">{{ number_format($item->luas_tanah) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('admin.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-folder-open fa-3x mb-3 d-block opacity-50"></i>
                                        <h5 class="fw-semibold">Data Kosong</h5>
                                        <p class="mb-0">Belum ada data aset wakaf yang terdaftar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Akhir Tabel Responsif -->
                
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Chart.js -->
    <script>
        // Data dari PHP/Database
        const countSudah = {{ $asetWakaf->where('status_sertipikat', 'Sudah Bersertipikat')->count() }};
        const countBelum = {{ $asetWakaf->where('status_sertipikat', 'Belum Bersertipikat')->count() }};

        // 1. Chart Status Sertipikat (Doughnut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Bersertipikat', 'Belum Bersertipikat'],
                datasets: [{
                    data: [countSudah, countBelum],
                    backgroundColor: ['#198754', '#dc3545'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Rekapitulasi Data Per Kecamatan (PHP Aggregation)
        @php
            $kecamatanData = $asetWakaf->groupBy('kecamatan')->map->count();
            $kecamatanLabels = $kecamatanData->keys();
            $kecamatanValues = $kecamatanData->values();
        @endphp

        const kecLabels = @json($kecamatanLabels);
        const kecValues = @json($kecamatanValues);

        // 2. Chart Sebaran Kecamatan (Bar Chart)
        const ctxKecamatan = document.getElementById('kecamatanChart').getContext('2d');
        new Chart(ctxKecamatan, {
            type: 'bar',
            data: {
                labels: kecLabels,
                datasets: [{
                    label: 'Jumlah Aset Wakaf',
                    data: kecValues,
                    backgroundColor: '#0d6efd',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>