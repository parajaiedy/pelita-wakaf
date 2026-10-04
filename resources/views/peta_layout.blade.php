<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pelita Aset Parepare')</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    @include('partials.styles')
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        #map { height: 100vh; width: 100%; z-index: 1; }
        .search-box .input-group-text { background: #fff; border-right: none; border-radius: 11px 0 0 11px; }
        .search-box .form-control { border-left: none; border-radius: 0 11px 11px 0; }
        .search-box .form-control:focus { box-shadow: none; border-color: var(--brand-500); }
        .legend-box { display: flex; flex-direction: column; gap: 6px; }
        .legend-row { display: flex; align-items: center; gap: 9px; font-size: .82rem; color: var(--ink-soft); }
        .legend-dot { width: 14px; height: 14px; border-radius: 50% 50% 50% 0; flex-shrink: 0; transform: rotate(-45deg); box-shadow: 0 1px 2px rgba(0,0,0,.25); border: 2px solid #fff; }
        .marker-cluster-pelita { background: transparent; }
        .marker-cluster-pelita .cluster-badge { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, var(--brand-600), var(--brand-500)); color: #fff; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; border: 3px solid rgba(255,255,255,.9); box-shadow: 0 4px 12px rgba(13,148,136,.45); }
        .category-header { display: inline-flex; align-items: center; gap: 8px; padding: 5px 12px; border-radius: 999px; font-weight: 700; font-size: .85rem; margin-bottom: 10px; }
        .category-header.wakaf { background: #d1fae5; color: #047857; }
        .category-header.aset { background: #dbeafe; color: #1d4ed8; }
        .category-header.gabungan { background: #f3f4f6; color: #374151; }
    </style>
    @yield('head')
</head>
<body>
    <div class="sidebar-panel">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="navbar-brand-mark"><i class="fa-solid fa-map-location-dot"></i></span>
            <div>
                <div class="sidebar-brand">Pelita Aset Parepare</div>
                <small class="text-muted">BPN Kota Parepare</small>
            </div>
        </div>

        @yield('category_badge')

        <div class="search-box mb-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" id="cari-masjid" class="form-control" placeholder="Cari nama aset / kelurahan..." autocomplete="off">
            </div>
            <div id="hasil-cari" class="small text-muted mt-1 d-none"></div>
        </div>

        <hr class="my-3">

        <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-chart-pie me-1"></i> Ringkasan</h6>
        <div class="stat-card">
            <div><small class="text-muted d-block">Total Aset</small><span class="fw-bold fs-5" id="total-aset">0</span></div>
            <span class="stat-icon-wrap"><i class="fa-solid fa-map-location-dot"></i></span>
        </div>
        <div class="stat-card hijau">
            <div><small class="text-muted d-block">Sudah Bersertipikat</small><span class="fw-bold fs-5 text-success" id="jml-sertipikat">0</span></div>
            <span class="stat-icon-wrap"><i class="fa-solid fa-circle-check"></i></span>
        </div>
        <div class="stat-card merah">
            <div><small class="text-muted d-block">Belum Bersertipikat</small><span class="fw-bold fs-5 text-danger" id="jml-belum">0</span></div>
            <span class="stat-icon-wrap"><i class="fa-solid fa-circle-exclamation"></i></span>
        </div>

        <h6 class="fw-bold text-secondary mt-3 mb-2"><i class="fa-solid fa-palette me-1"></i> Legenda</h6>
        <div class="legend-box">
            <div class="legend-row"><span class="legend-dot" style="background:#198754"></span> Hak Wakaf</div>
            <div class="legend-row"><span class="legend-dot" style="background:#ffc107"></span> Hak Milik</div>
            <div class="legend-row"><span class="legend-dot" style="background:#d63384"></span> Hak Guna Bangunan</div>
            <div class="legend-row"><span class="legend-dot" style="background:#8B4513"></span> Hak Pakai</div>
            <div class="legend-row"><span class="legend-dot" style="background:#dc3545"></span> Belum Bersertipikat</div>
        </div>

        <div class="mt-3 d-grid gap-2">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Beranda</a>
            <a href="{{ route('admin.index') }}" class="btn-admin-floating"><i class="fa-solid fa-user-shield me-1"></i> Dashboard Admin</a>
        </div>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
