<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.seo_meta', ['siteTitle' => 'Pelita Aset Parepare - Portal Aset Wakaf & Aset Pemerintah', 'siteDesc' => 'Portal geospasial aset wakaf dan aset pemerintah Kota Parepare. Lihat peta sebaran lokasi, status sertipikat, dan data administratif aset kota secara interaktif.'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    @include('partials.styles')
    <style>
        .hero a { text-decoration: none; }
        #miniMap { width: 100%; height: 420px; border-radius: var(--radius-lg); border: 1px solid var(--line); box-shadow: var(--shadow-md); }
        .mini-card { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 18px; box-shadow: var(--shadow-xs); transition: transform .2s ease, box-shadow .2s ease; }
        .mini-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    </style>
    @include('partials.theme')
</head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelita Aset Parepare - Portal Aset Wilayah Kota Parepare</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    @include('partials.styles')
    <style>
        .hero a { text-decoration: none; }
        #miniMap { width: 100%; height: 420px; border-radius: var(--radius-lg); border: 1px solid var(--line); box-shadow: var(--shadow-md); }
        .mini-card { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 18px; box-shadow: var(--shadow-xs); transition: transform .2s ease, box-shadow .2s ease; }
        .mini-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    </style>
    @include('partials.theme')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="navbar-brand-mark"><i class="fa-solid fa-map-location-dot"></i></span>
            <span>Pelita Aset Parepare</span>
        </a>
            <button class="theme-toggle ms-2" type="button" onclick="toggleTema()" title="Ganti tema"><i id="themeIcon" class="fa-solid fa-moon"></i></button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navPublic">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('peta.wakaf') }}">Aset Wakaf</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('peta.aset-pemerintah') }}">Aset Pemerintah</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('peta.publik') }}">Peta Gabungan</a></li>
                <li class="nav-item ms-lg-2"><a href="{{ route('login') }}" class="btn btn-light btn-sm px-3 fw-bold"><i class="fa-solid fa-user-shield me-1"></i> Admin</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero" style="padding-top: 100px;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <span class="hero-badge"><i class="fa-solid fa-satellite-dish"></i> Portal Geospasial Aset Parepare</span>
                <h1 class="mt-3 mb-3">Pemetaan Aset <span class="text-gradient-light">Wakaf & Pemerintah</span> Kota Parepare</h1>
                <p class="lead mx-auto">Akses visualisasi lokasi, status sertipikat, dan data administratif aset kota secara terintegrasi dan terpisah sesuai kategorinya.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <a href="{{ route('peta.publik') }}" class="btn btn-light btn-lg fw-bold px-4 shadow-sm"><i class="fa-solid fa-map me-2"></i>Lihat Peta Utama</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg fw-bold px-4"><i class="fa-solid fa-lock me-2"></i>Login Admin</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="margin-top: -48px;">
    <div class="container position-relative" style="z-index: 3;">
        @php
            $pctAset = $total ? round(($totalAset / $total) * 100, 1) : 0;
            $pctWakaf = $total ? round(($totalWakaf / $total) * 100, 1) : 0;
        @endphp
        <div class="row g-4">
            <div class="col-md-6">
                <a href="{{ route('peta.wakaf') }}" class="d-block portal-card wakaf">
                    <div class="portal-top"></div>
                    <div class="portal-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                    <h3>Aset Wakaf</h3>
                    <p class="text-muted small mb-2">Tanah wakaf, masjid, dan aset keagamaan.</p>
                    <div class="portal-count">{{ number_format($totalWakaf, 0, ',', '.') }}</div>
                    <span class="badge badge-soft-wakaf mb-2">Sudah Bersertipikat</span>
                    <div class="portal-meter mt-2"><span style="width: {{ $pctWakaf }}%; background: linear-gradient(90deg, #059669, #34d399);"></span></div>
                    <small class="text-muted mt-2 d-block">{{ $pctWakaf }}% dari total aset</small>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('peta.aset-pemerintah') }}" class="d-block portal-card aset">
                    <div class="portal-top"></div>
                    <div class="portal-icon"><i class="fa-solid fa-building-columns"></i></div>
                    <h3>Aset Pemerintah</h3>
                    <p class="text-muted small mb-2">Aset Kota Parepare (Pemkot) yang telah bersertipikat.</p>
                    <div class="portal-count">{{ number_format($totalAset, 0, ',', '.') }}</div>
                    <span class="badge badge-soft-aset mb-2">Sudah Bersertipikat</span>
                    <div class="portal-meter mt-2"><span style="width: {{ $pctAset }}%; background: linear-gradient(90deg, #2563eb, #60a5fa);"></span></div>
                    <small class="text-muted mt-2 d-block">{{ $pctAset }}% dari total aset</small>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="hero-badge"><i class="fa-solid fa-map"></i> Mini Peta</span>
            <h2 class="section-title mt-2 mb-2">Lihat Sebaran Aset Secara Langsung</h2>
            <p class="text-muted">Peta interaktif ringkas di beranda, peta lengkap ada di menu atas.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-8">
                <div id="miniMap"></div>
            </div>
            <div class="col-lg-4">
                <div class="mini-card mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="portal-icon" style="width:46px;height:46px;font-size:1.1rem;background:linear-gradient(135deg,#10b981,#34d399);box-shadow:none;"><i class="fa-solid fa-layer-group"></i></div>
                        <div><div class="text-muted small">Total Aset</div><div class="fw-bold fs-4">{{ number_format($total, 0, ',', '.') }}</div></div>
                    </div>
                </div>
                <div class="mini-card mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="portal-icon" style="width:46px;height:46px;font-size:1.1rem;background:linear-gradient(135deg,#059669,#34d399);box-shadow:none;"><i class="fa-solid fa-hand-holding-heart"></i></div>
                        <div><div class="text-muted small">Wakaf</div><div class="fw-bold fs-4">{{ number_format($totalWakaf, 0, ',', '.') }}</div></div>
                    </div>
                </div>
                <div class="mini-card mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="portal-icon" style="width:46px;height:46px;font-size:1.1rem;background:linear-gradient(135deg,#2563eb,#60a5fa);box-shadow:none;"><i class="fa-solid fa-building-columns"></i></div>
                        <div><div class="text-muted small">Aset Pemerintah</div><div class="fw-bold fs-4">{{ number_format($totalAset, 0, ',', '.') }}</div></div>
                    </div>
                </div>
                <div class="mini-card">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="portal-icon" style="width:46px;height:46px;font-size:1.1rem;background:linear-gradient(135deg,#0b1220,#3b82f6);box-shadow:none;"><i class="fa-solid fa-map-location-dot"></i></div>
                        <div><div class="text-muted small">Terpetakan</div><div class="fw-bold fs-4">{{ number_format($tersertifikat, 0, ',', '.') }}</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Mengapa Memilih Portal Ini?</h2>
            <p class="text-muted">Data terintegrasi, tampilan geospasial, dan akses publik yang mudah dipahami.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-tile">
                    <div class="ft-icon"><i class="fa-solid fa-map"></i></div>
                    <h5 class="fw-bold">Peta Interaktif</h5>
                    <p class="text-muted small mb-0">Zoom, cari, dan lihat rute langsung ke lokasi aset melalui Google Maps.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-tile">
                    <div class="ft-icon"><i class="fa-solid fa-filter"></i></div>
                    <h5 class="fw-bold">Pisah per Kategori</h5>
                    <p class="text-muted small mb-0">Aset Wakaf dan Aset Pemerintah dipisah secara visual agar tidak tercampur.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-tile">
                    <div class="ft-icon"><i class="fa-solid fa-file-pdf"></i></div>
                    <h5 class="fw-bold">Laporan & Export</h5>
                    <p class="text-muted small mb-0">Export Excel dan laporan PDF siap digunakan untuk keperluan administrasi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="navbar-brand-mark" style="width: 30px; height: 30px; font-size: 14px;"><i class="fa-solid fa-map-location-dot"></i></span>
                    <strong>Pelita Aset Parepare</strong>
                </div>
                <small>Sistem Informasi Geospasial Aset Kota Parepare</small>
            </div>
            <div class="col-md-6 text-md-end">
                <small>&copy; {{ date('Y') }} Kantor Pertanahan Kota Parepare. Hak Cipta Dilindungi.</small>
            </div>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
    var miniData = @json($asets ?? []);
    var miniMap = L.map('miniMap', { zoomControl: false, attributionControl: false }).setView([-4.00165, 119.64347], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(miniMap);
    var miniCluster = L.markerClusterGroup({ showCoverageOnHover: false });
    miniData.forEach(function(item){
        if (!parseFloat(item.latitude) && !parseFloat(item.longitude)) return;
        var pinColor = '#3b82f6';
        var hak = (item.jenis_hak || '').toLowerCase();
        if (hak.includes('wakaf')) pinColor = '#10b981';
        var icon = L.divIcon({
            className: 'custom-pin',
            html: '<i class="fa-solid fa-location-dot" style="color:' + pinColor + ';font-size:28px;-webkit-text-stroke:1px #fff;"></i>',
            iconSize: [24, 28], iconAnchor: [12, 28]
        });
        var m = L.marker([item.latitude, item.longitude], { icon: icon });
        m.bindTooltip(item.nama_masjid, { direction: 'top' });
        miniCluster.addLayer(m);
    });
    miniMap.addLayer(miniCluster);
</script>

</body>
</html>
