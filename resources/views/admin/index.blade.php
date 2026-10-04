<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pelita Aset Parepare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('partials.styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" />
    <style>.btn-icon{height:38px;width:38px,display:inline-flex;align-items:center;justify-content:center;border-radius:10px;border:1px solid var(--line);background:#fff;color:var(--ink-soft);transition:all .15s}.btn-icon:hover{box-shadow:var(--shadow-sm);transform:translateY(-1px)}</style>
    @include('partials.theme')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top py-2">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="navbar-brand-mark"><i class="fa-solid fa-map-location-dot"></i></span>
            <span>Pelita Aset Parepare</span>
        </a>
            <button class="theme-toggle ms-2" type="button" onclick="toggleTema()" title="Ganti tema"><i id="themeIcon" class="fa-solid fa-moon"></i></button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
            <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>
        <div class="collapse navbar-collapse" id="navAdmin">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item"><a class="nav-link active" href="{{ route('admin.index') }}"><i class="fa-solid fa-table me-1"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('peta.publik') }}" target="_blank"><i class="fa-solid fa-map me-1"></i> Peta</a></li>
                <li class="nav-item ms-lg-2">
                    <form action="{{ route('logout') }}" method="POST">@csrf
                        <button type="submit" class="btn btn-outline-light btn-sm px-3"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div style="padding-top: 72px;">
    <div class="container pb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $totalAset = $semuaAset->count();
            $sudahSertipikat = $semuaAset->where('status_sertipikat', 'Sudah Bersertipikat')->count();
            $belumSertipikat = $semuaAset->where('status_sertipikat', 'Belum Bersertipikat')->count();
            $persenSertipikat = $totalAset ? round(($sudahSertipikat / $totalAset) * 100, 1) : 0;
            $jumlahWakaf = $semuaAset->where('kategori', 'Wakaf')->count();
            $jumlahAsetPemerintah = $semuaAset->where('kategori', 'Aset Pemerintah')->count();
            $activeTab = request('kategori', '');
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-primary text-white h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div><h6 class="text-white-50 small mb-1">Total Aset</h6><h3 class="fw-bold mb-0">{{ $totalAset }}</h3></div>
                        <i class="fa-solid fa-layer-group fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat bg-success text-white h-100">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div><h6 class="text-white-50 small mb-1">Sudah Sertipikat</h6><h3 class="fw-bold mb-0">{{ $sudahSertipikat }}</h3></div>
                        <i class="fa-solid fa-circle-check fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat text-white h-100" style="background: linear-gradient(135deg,#3b82f6,#60a5fa);">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div><h6 class="text-white-50 small mb-1">Aset Pemerintah</h6><h3 class="fw-bold mb-0">{{ $jumlahAsetPemerintah }}</h3></div>
                        <i class="fa-solid fa-building-columns fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-stat text-white h-100" style="background: linear-gradient(135deg,#10b981,#34d399);">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div><h6 class="text-white-50 small mb-1">Wakaf</h6><h3 class="fw-bold mb-0">{{ $jumlahWakaf }}</h3></div>
                        <i class="fa-solid fa-hand-holding-heart fa-2x opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card chart-card shadow-sm mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-2">
                    <div>
                        <h6 class="fw-bold mb-1"><i class="fa-solid fa-bullseye me-2 text-success"></i>Capaian Sertipikasi</h6>
                        <small class="text-muted">{{ $sudahSertipikat }} dari {{ $totalAset }} aset sudah bersertipikat.</small>
                    </div>
                    <span class="badge bg-success px-3 py-2 fs-6">{{ number_format($persenSertipikat, 1, ',', '.') }}%</span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 999px; background: #e2e8f0;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenSertipikat }}%" aria-valuenow="{{ $persenSertipikat }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="segmented">
                            <a href="{{ route('admin.index') }}" class="{{ $activeTab === '' ? 'active' : '' }}"><i class="fa-solid fa-layer-group"></i> Semua</a>
                            <a href="{{ route('admin.index', ['kategori' => 'Wakaf']) }}" class="tone-wakaf {{ $activeTab === 'Wakaf' ? 'active' : '' }}"><i class="fa-solid fa-hand-holding-heart"></i> Wakaf</a>
                            <a href="{{ route('admin.index', ['kategori' => 'Aset Pemerintah']) }}" class="tone-aset {{ $activeTab === 'Aset Pemerintah' ? 'active' : '' }}"><i class="fa-solid fa-building-columns"></i> Aset Pemerintah</a>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.laporanPdf') }}" class="btn btn-outline-brand" target="_blank"><i class="fa-solid fa-file-pdf me-1"></i> Laporan PDF</a>
                        <a href="{{ route('admin.backup') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-database me-1"></i> Backup</a>
                        <a href="{{ route('admin.exportExcel') }}" class="btn btn-soft"><i class="fa-solid fa-file-excel me-1"></i> Export</a>
                        <a href="{{ route('admin.create') }}" class="btn btn-brand"><i class="fa-solid fa-plus me-1"></i> Tambah Aset</a>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.index') }}" class="filter-panel mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-lg-4">
                            <label for="q" class="form-label small mb-1">Cari aset</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="search" name="q" id="q" class="form-control" value="{{ request('q') }}" placeholder="Nama, kelurahan, atau nomor hak">
                            </div>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="kecamatan" class="form-label small mb-1">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-select">
                                <option value="">Semua</option>
                                @foreach($kecamatanList as $kecamatan)
                                    <option value="{{ $kecamatan }}" @selected(request('kecamatan') === $kecamatan)>{{ $kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="status" class="form-label small mb-1">Sertipikat</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="Sudah Bersertipikat" @selected(request('status') === 'Sudah Bersertipikat')>Sudah</option>
                                <option value="Belum Bersertipikat" @selected(request('status') === 'Belum Bersertipikat')>Belum</option>
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="jenis_hak" class="form-label small mb-1">Jenis hak</label>
                            <select name="jenis_hak" id="jenis_hak" class="form-select">
                                <option value="">Semua</option>
                                @foreach($jenisHakList as $jenisHak)
                                    <option value="{{ $jenisHak }}" @selected(request('jenis_hak') === $jenisHak)>{{ $jenisHak }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label for="tindak_lanjut" class="form-label small mb-1">Tindak lanjut</label>
                            <select name="tindak_lanjut" id="tindak_lanjut" class="form-select">
                                <option value="">Semua</option>
                                @foreach($tindakLanjutList as $statusTindakLanjut)
                                    <option value="{{ $statusTindakLanjut }}" @selected(request('tindak_lanjut') === $statusTindakLanjut)>{{ $statusTindakLanjut }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn btn-brand flex-grow-1"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                            @if(request()->hasAny(['q', 'kecamatan', 'status', 'jenis_hak', 'tindak_lanjut', 'kategori']))
                                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary" title="Reset filter"><i class="fa-solid fa-rotate-left"></i></a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <small class="text-muted">Total <b>{{ $asetWakaf->count() }}</b> aset @if(request('kategori'))<span class="badge {{ request('kategori') === 'Wakaf' ? 'badge-soft-wakaf' : 'badge-soft-aset' }} ms-1">{{ request('kategori') }}</span>@endif</small>
                </div>

                <div class="border rounded">
                    <div class="table-responsive">
                        <table id="tabelAset" class="table table-modern align-middle mb-0">
                            <thead>
                                <tr class="text-center text-nowrap">
                                    <th width="4%">No</th>
                                    <th class="text-start">Nama Aset</th>
                                    <th class="text-start">Wilayah</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tindak Lanjut</th>
                                    <th>Jenis & No. Hak</th>
                                    <th>Luas (m²)</th>
                                    <th width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asetWakaf as $index => $item)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                        <td class="text-start">
                                            <div class="fw-bold text-dark text-nowrap">{{ $item->nama_masjid }}</div>
                                            @if((float) $item->latitude == 0 && (float) $item->longitude == 0)
                                                <small class="text-danger"><i class="fa-solid fa-location-dot me-1"></i>Belum terpetakan</small>
                                            @else
                                                <small class="text-muted"><i class="fa-regular fa-compass me-1"></i>{{ $item->latitude }}, {{ $item->longitude }}</small>
                                            @endif
                                        </td>
                                        <td class="text-start text-nowrap">
                                            <small class="text-muted"><i class="fa-solid fa-map-pin me-1"></i>Kec. {{ $item->kecamatan }} / Kel. {{ $item->kelurahan }}</small>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            @if(($item->kategori ?? 'Wakaf') === 'Wakaf')
                                                <span class="badge badge-soft-wakaf"><i class="fa-solid fa-hand-holding-heart me-1"></i>Wakaf</span>
                                            @else
                                                <span class="badge badge-soft-aset"><i class="fa-solid fa-building-columns me-1"></i>Aset Pemerintah</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            @if($item->status_sertipikat == 'Sudah Bersertipikat')
                                                <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Sudah</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i>Belum</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            @php
                                                $warnaTindakLanjut = match($item->status_tindak_lanjut ?? 'Belum Ditindaklanjuti') {
                                                    'Selesai' => 'bg-success-subtle text-success border-success',
                                                    'Proses Sertipikasi', 'Pengukuran' => 'bg-warning-subtle text-warning-emphasis border-warning',
                                                    'Pengumpulan Berkas' => 'bg-info-subtle text-info-emphasis border-info',
                                                    default => 'bg-secondary-subtle text-secondary border-secondary',
                                                };
                                            @endphp
                                            <form action="{{ route('admin.updateStatus', $item->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <select name="status_tindak_lanjut" class="form-select form-select-sm status-inline-select {{ $warnaTindakLanjut }}" onchange="this.form.submit()">
                                                    @foreach(['Belum Ditindaklanjuti', 'Pengumpulan Berkas', 'Pengukuran', 'Proses Sertipikasi', 'Selesai'] as $statusTindakLanjut)
                                                        <option value="{{ $statusTindakLanjut }}" @selected(($item->status_tindak_lanjut ?? 'Belum Ditindaklanjuti') === $statusTindakLanjut)>{{ $statusTindakLanjut }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            @if($item->jenis_hak || $item->nomor_hak)
                                                <span class="badge bg-info-subtle text-info-emphasis border">{{ $item->jenis_hak ?? '-' }} No. {{ $item->nomor_hak ?? '-' }}</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-semibold">{{ number_format($item->luas_tanah) }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.edit', $item->id) }}" class="btn-icon" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <form action="{{ route('admin.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn-icon" style="color:#dc2626" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-folder-open fa-3x mb-3 d-block opacity-50"></i>
                                            <h5 class="fw-semibold">Data Kosong</h5>
                                            <p class="mb-0">Belum ada data aset yang terdaftar.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelAset').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
                ordering: true,
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excel', text: '<i class="fa-solid fa-file-excel me-1"></i> Excel', className: 'btn btn-soft btn-sm' },
                    { extend: 'pdf', text: '<i class="fa-solid fa-file-pdf me-1"></i> PDF', className: 'btn btn-outline-brand btn-sm' },
                    { extend: 'print', text: '<i class="fa-solid fa-print me-1"></i> Print', className: 'btn btn-outline-secondary btn-sm' }
                ]
            });
        });
    </script>

</body>
</html>
