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

        /* ---- Panel Detail Aset (muncul saat pin diklik) ---- */
        .detail-panel {
            position: absolute; top: 18px; right: 18px; z-index: 1500;
            width: 372px; max-width: calc(100vw - 36px);
            max-height: calc(100vh - 36px); overflow-y: auto;
            background: #fff; border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg); border: 1px solid var(--line);
            transform: translateX(calc(100% + 24px)); opacity: 0;
            transition: transform .28s cubic-bezier(.4,0,.2,1), opacity .28s ease;
            scrollbar-width: thin;
        }
        .detail-panel.show { transform: translateX(0); opacity: 1; }
        .detail-panel::-webkit-scrollbar { width: 6px; }
        .detail-panel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        .detail-hero {
            position: relative; padding: 22px 22px 18px; color: #fff;
            background: linear-gradient(135deg, #0b1220, #1e293b);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }
        .detail-hero.wakaf { background: linear-gradient(135deg, #047857, #10b981); }
        .detail-hero.aset  { background: linear-gradient(135deg, #1d4ed8, #3b82f6); }
        .detail-hero h5 { font-size: 1.05rem; font-weight: 800; margin: 0 0 4px; letter-spacing: -.02em; line-height: 1.28; }
        .detail-close {
            position: absolute; top: 12px; right: 12px;
            width: 32px; height: 32px; border-radius: 50%; border: none;
            background: rgba(255,255,255,.18); color: #fff; font-size: 1rem;
            display: inline-flex; align-items: center; justify-content: center;
            transition: background .15s ease;
        }
        .detail-close:hover { background: rgba(255,255,255,.32); }
        .detail-body { padding: 18px 22px 22px; }
        .detail-row { display: flex; align-items: flex-start; gap: 12px; padding: 11px 0; border-bottom: 1px solid var(--line); }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .dr-icon {
            width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--surface-2); color: var(--brand-700); font-size: .9rem;
        }
        .detail-row .dr-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); font-weight: 700; }
        .detail-row .dr-value { font-size: .92rem; font-weight: 600; color: var(--ink); line-height: 1.35; word-break: break-word; }

        /* ---- Skeleton loading ---- */
        .skeleton { background: linear-gradient(90deg, #eef2f7 25%, #e2e8f0 37%, #eef2f7 63%); background-size: 400% 100%; animation: shimmer 1.4s ease infinite; border-radius: 8px; }
        @keyframes shimmer { 0% { background-position: 100% 50%; } 100% { background-position: 0 50%; } }
        .peta-loading {
            position: absolute; inset: 0; z-index: 1800; background: rgba(238,242,247,.92);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 14px; transition: opacity .3s ease;
        }
        .peta-loading.hide { opacity: 0; pointer-events: none; }
        .spinner-ring {
            width: 46px; height: 46px; border-radius: 50%;
            border: 4px solid var(--brand-100); border-top-color: var(--brand-600);
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 900px) {
            .detail-panel {
                top: auto; bottom: 0; left: 0; right: 0; width: auto; max-width: none;
                max-height: 68vh; border-radius: var(--radius-lg) var(--radius-lg) 0 0;
                transform: translateY(100%);
            }
            .detail-panel.show { transform: translateY(0); }
            .detail-panel { margin-bottom: 62px; }
        }
    </style>
    @yield('head')
</head>
<body>

    <!-- Skeleton loading peta -->
    <div class="peta-loading" id="petaLoading">
        <div class="spinner-ring"></div>
        <div class="text-center">
            <div class="fw-bold" style="color: var(--ink-soft);">Memuat peta aset…</div>
            <small class="text-muted">Menyiapkan titik lokasi dan batas wilayah</small>
        </div>
    </div>

    <div class="sidebar-panel">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="navbar-brand-mark"><i class="fa-solid fa-map-location-dot"></i></span>
            <div>
                <div class="sidebar-brand">Pelita Aset Parepare</div>
                <small class="text-muted">BPN Kota Parepare</small>
            </div>
        </div>

        @yield('category_badge')

        <div class="search-box mb-3 position-relative">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" id="cari-masjid" class="form-control" placeholder="Cari nama aset / kelurahan..." autocomplete="off">
            </div>
            <div id="saran-cari" class="list-group position-absolute w-100 shadow-sm d-none" style="z-index: 1200; max-height: 240px; overflow-y: auto; top: 100%;"></div>
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

    <!-- Panel Detail Aset -->
    <aside class="detail-panel" id="detailPanel" aria-hidden="true">
        <div class="detail-hero" id="detailHero">
            <button class="detail-close" onclick="tutupDetail()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span id="detailKategoriBadge" class="badge bg-light text-dark"></span>
            </div>
            <h5 id="detailNama">—</h5>
            <small class="opacity-75" id="detailWilayah">—</small>
        </div>
        <div class="detail-body">
            <div class="detail-row">
                <span class="dr-icon"><i class="fa-solid fa-file-contract"></i></span>
                <div><div class="dr-label">Jenis & Nomor Hak</div><div class="dr-value" id="detailHak">—</div></div>
            </div>
            <div class="detail-row">
                <span class="dr-icon"><i class="fa-solid fa-ruler-combined"></i></span>
                <div><div class="dr-label">Luas Tanah</div><div class="dr-value" id="detailLuas">—</div></div>
            </div>
            <div class="detail-row">
                <span class="dr-icon"><i class="fa-solid fa-certificate"></i></span>
                <div><div class="dr-label">Status Sertipikat</div><div class="dr-value" id="detailStatus">—</div></div>
            </div>
            <div class="detail-row">
                <span class="dr-icon"><i class="fa-solid fa-route"></i></span>
                <div><div class="dr-label">Tindak Lanjut</div><div class="dr-value" id="detailTindakLanjut">—</div></div>
            </div>
            <div class="detail-row">
                <span class="dr-icon"><i class="fa-regular fa-compass"></i></span>
                <div><div class="dr-label">Koordinat</div><div class="dr-value" id="detailKoordinat">—</div></div>
            </div>
            <a href="#" target="_blank" class="btn btn-brand w-100 mt-3" id="detailRute">
                <i class="fa-solid fa-diamond-turn-right me-1"></i> Rute Navigasi Google Maps
            </a>
        </div>
    </aside>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')

<!-- Mobile Bottom Navigation -->
<nav class="bottom-nav d-lg-none">
    <a href="{{ route('home') }}" class="bn-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i><span>Beranda</span>
    </a>
    <a href="{{ route('peta.wakaf') }}" class="bn-item {{ request()->routeIs('peta.wakaf') ? 'active' : '' }}">
        <i class="fa-solid fa-hand-holding-heart"></i><span>Wakaf</span>
    </a>
    <a href="{{ route('peta.aset-pemerintah') }}" class="bn-item {{ request()->routeIs('peta.aset-pemerintah') ? 'active' : '' }}">
        <i class="fa-solid fa-building-columns"></i><span>Pemerintah</span>
    </a>
    <a href="{{ route('peta.publik') }}" class="bn-item {{ request()->routeIs('peta.publik') ? 'active' : '' }}">
        <i class="fa-solid fa-map"></i><span>Peta</span>
    </a>
</nav>

</body>
</html>
