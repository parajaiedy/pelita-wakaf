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

        @include('partials.styles')
    </head>
<body>

    <!-- Navbar Header -->
    <nav class="navbar navbar-expand-lg navbar-custom px-4 py-3 shadow-sm mb-4">
        <div class="container-fluid flex-wrap gap-2">
            <a class="navbar-brand text-white d-flex align-items-center gap-2" href="#">
                            <span class="navbar-brand-mark"><i class="fa-solid fa-mosque"></i></span>
                            <span>Pelita Wakaf Parepare</span>
                        </a>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-user me-1"></i> {{ Auth::user()->name }}
                </span>
                <a href="{{ route('peta.publik') }}" class="btn btn-outline-light btn-sm px-3" target="_blank">
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

        @php
            // Statistik selalu memakai seluruh data, bukan hanya 15 baris halaman ini.
            $totalAset = $semuaAset->count();
            $sudahSertipikat = $semuaAset->where('status_sertipikat', 'Sudah Bersertipikat')->count();
            $belumSertipikat = $semuaAset->where('status_sertipikat', 'Belum Bersertipikat')->count();
            $persenSertipikat = $totalAset ? round(($sudahSertipikat / $totalAset) * 100, 1) : 0;

            $countWakaf = 0;
            $countMilik = 0;
            $countHGB = 0;
            $countPakai = 0;

            foreach($semuaAset as $item) {
                $hak = strtolower($item->jenis_hak ?? '');
                if (str_contains($hak, 'wakaf')) {
                    $countWakaf++;
                } elseif (str_contains($hak, 'milik')) {
                    $countMilik++;
                } elseif (str_contains($hak, 'bangunan') || str_contains($hak, 'hgb')) {
                    $countHGB++;
                } elseif (str_contains($hak, 'pakai')) {
                    $countPakai++;
                }
            }
        @endphp

        <!-- Ringkasan Kartu Statistik Utama -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-primary text-white shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small mb-1">Total Aset Wakaf</h6>
                            <h3 class="fw-bold mb-0">{{ $totalAset }}</h3>
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
                            <h3 class="fw-bold mb-0">{{ $sudahSertipikat }}</h3>
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
                            <h3 class="fw-bold mb-0">{{ $belumSertipikat }}</h3>
                        </div>
                        <i class="fa-solid fa-file-circle-exclamation fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-secondary text-white shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small mb-1">Total Luas (m²)</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($semuaAset->sum('luas_tanah')) }}</h3>
                        </div>
                        <i class="fa-solid fa-ruler-combined fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progres Capaian Sertipikasi -->
        <div class="card chart-card shadow-sm mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-2">
                    <div>
                        <h6 class="fw-bold mb-1"><i class="fa-solid fa-bullseye me-2 text-success"></i>Capaian Sertipikasi</h6>
                        <small class="text-muted">{{ $sudahSertipikat }} dari {{ $totalAset }} aset wakaf sudah bersertipikat.</small>
                    </div>
                    <span class="badge bg-success px-3 py-2 fs-6">{{ number_format($persenSertipikat, 1, ',', '.') }}%</span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 999px; background: #e2e8f0;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenSertipikat }}%" aria-valuenow="{{ $persenSertipikat }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Rincian Kartu Berdasarkan Jenis Hak -->
        <h6 class="fw-bold text-secondary mb-2 mt-2"><i class="fa-solid fa-layer-group me-1"></i> Rincian Berdasarkan Jenis Hak</h6>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card card-stat text-white shadow-sm h-100" style="background-color: #198754;">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small mb-1">Hak Wakaf</h6>
                            <h3 class="fw-bold mb-0">{{ $countWakaf }}</h3>
                        </div>
                        <i class="fa-solid fa-hand-holding-heart fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-yellow shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark-50 small mb-1 opacity-75">Hak Milik</h6>
                            <h3 class="fw-bold mb-0">{{ $countMilik }}</h3>
                        </div>
                        <i class="fa-solid fa-house-chimney-user fa-2x opacity-25 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-pink shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small mb-1">Hak Guna Bangunan</h6>
                            <h3 class="fw-bold mb-0">{{ $countHGB }}</h3>
                        </div>
                        <i class="fa-solid fa-building fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-brown shadow-sm h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 small mb-1">Hak Pakai</h6>
                            <h3 class="fw-bold mb-0">{{ $countPakai }}</h3>
                        </div>
                        <i class="fa-solid fa-tractor fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Diagram Visualisasi Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-5">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Status Sertipikasi</h5>
                        <div style="height: 280px;" class="d-flex align-items-center justify-content-center mt-3">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="fa-solid fa-chart-column me-2 text-success"></i>Sebaran Aset per Kecamatan</h5>
                        <div style="height: 280px;" class="mt-3">
                            <canvas id="kecamatanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Tabel & Tombol Tambah + Export -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Daftar Aset Wakaf Parepare</h4>
                        <small class="text-muted">Kelola, cari, dan filter data aset dengan lebih cepat.</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.exportExcel') }}" class="btn btn-soft flex-fill">
                            <i class="fa-solid fa-file-excel me-1"></i> Export Excel
                        </a>
                        <a href="{{ route('admin.create') }}" class="btn btn-brand flex-fill">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Aset
                        </a>
                    </div>
                </div>

                <!-- Pencarian dan Filter Data -->
                <form method="GET" action="{{ route('admin.index') }}" class="filter-panel mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-lg-4">
                            <label for="q" class="form-label small mb-1">Cari aset</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="search" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Nama masjid, kelurahan, atau nomor hak">
                            </div>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="kecamatan" class="form-label small mb-1">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-select">
                                <option value="">Semua kecamatan</option>
                                @foreach($kecamatanList as $kecamatan)
                                    <option value="{{ $kecamatan }}" @selected(request('kecamatan') === $kecamatan)>{{ $kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="status" class="form-label small mb-1">Sertipikat</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua status</option>
                                <option value="Sudah Bersertipikat" @selected(request('status') === 'Sudah Bersertipikat')>Sudah</option>
                                <option value="Belum Bersertipikat" @selected(request('status') === 'Belum Bersertipikat')>Belum</option>
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="jenis_hak" class="form-label small mb-1">Jenis hak</label>
                            <select name="jenis_hak" id="jenis_hak" class="form-select">
                                <option value="">Semua jenis hak</option>
                                @foreach($jenisHakList as $jenisHak)
                                    <option value="{{ $jenisHak }}" @selected(request('jenis_hak') === $jenisHak)>{{ $jenisHak }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn btn-brand flex-grow-1"><i class="fa-solid fa-filter me-1"></i> Terapkan</button>
                            @if(request()->hasAny(['q', 'kecamatan', 'status', 'jenis_hak']))
                                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary" title="Reset filter"><i class="fa-solid fa-rotate-left"></i></a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                    <small class="text-muted">
                        Menampilkan <b>{{ $asetWakaf->firstItem() ?? 0 }}–{{ $asetWakaf->lastItem() ?? 0 }}</b> dari <b>{{ $asetWakaf->total() }}</b> aset
                        @if(request()->hasAny(['q', 'kecamatan', 'status', 'jenis_hak'])) <span class="badge badge-soft-info ms-1">Hasil filter</span> @endif
                    </small>
                    <small class="text-muted">15 data per halaman</small>
                </div>

                <div class="border rounded">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle mb-0">
                            <thead>
                                <tr class="text-center text-nowrap">
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
                                        <td class="text-center fw-bold">{{ $asetWakaf->firstItem() + $index }}</td>
                                        <td class="text-start">
                                            <div class="fw-bold text-dark text-nowrap">{{ $item->nama_masjid }}</div>
                                        </td>
                                        <td class="text-start text-nowrap">
                                            <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> Kec. {{ $item->kecamatan }} / Kel. {{ $item->kelurahan }}</small>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <span class="badge bg-light text-dark border"><i class="fa-regular fa-compass me-1"></i> {{ $item->latitude }}, {{ $item->longitude }}</span>
                                        </td>
                                        <td class="text-center text-nowrap">
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
                                        <td class="text-center text-nowrap">
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
                </div>

                @if($asetWakaf->hasPages())
                    <div class="d-flex justify-content-center mt-4 pagination-pelita">
                        {{ $asetWakaf->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set Font Global untuk Chart.js agar terlihat lebih modern
        Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
        Chart.defaults.color = '#475569';

        // 1. DATA DOUGHNUT CHART (STATUS SERTIPIKAT)
        const countSudah = {{ $sudahSertipikat }};
                const countBelum = {{ $belumSertipikat }};

        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Bersertipikat', 'Belum Bersertipikat'],
                datasets: [{
                    data: [countSudah, countBelum],
                    backgroundColor: ['#198754', '#dc3545'], // Hijau & Merah
                    hoverBackgroundColor: ['#146c43', '#b02a37'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%', // Membuat lubang donat lebih elegan
                plugins: { 
                    legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) { label += ': '; }
                                label += context.parsed + ' Aset';
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // 2. DATA BAR CHART (SEBARAN KECAMATAN)
        @php
            $kecamatanData = $semuaAset->groupBy('kecamatan')->map->count();
            $kecamatanLabels = $kecamatanData->keys();
            $kecamatanValues = $kecamatanData->values();
        @endphp

        const kecLabels = @json($kecamatanLabels);
        const kecValues = @json($kecamatanValues);

        // Map Warna Kecamatan agar sinkron dengan Polygon di Peta Publik!
        const baseColors = {
            'Soreang': 'rgba(37, 99, 235, 0.85)',       // Biru
            'Ujung': 'rgba(124, 58, 237, 0.85)',        // Ungu
            'Bacukiki Barat': 'rgba(219, 39, 119, 0.85)', // Pink
            'Bacukiki': 'rgba(5, 150, 105, 0.85)'       // Hijau
        };
        const bgColors = kecLabels.map(label => baseColors[label] || 'rgba(13, 110, 253, 0.85)');

        const ctxKecamatan = document.getElementById('kecamatanChart').getContext('2d');
        new Chart(ctxKecamatan, {
            type: 'bar',
            data: {
                labels: kecLabels,
                datasets: [{
                    label: 'Jumlah Aset Wakaf',
                    data: kecValues,
                    backgroundColor: bgColors,
                    borderRadius: 8, // Ujung batang melengkung elegan
                    barThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5], color: '#e2e8f0' },
                        ticks: { stepSize: 10 }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Aset';
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });
    </script>
</body>
</html>