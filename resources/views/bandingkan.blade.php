<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandingkan Peta - Pelita Aset Parepare</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    @include('partials.styles')
    @include('partials.theme')
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        .compare-wrap { position: relative; width: 100vw; height: 100vh; display: flex; }
        .compare-map { flex: 1; position: relative; min-width: 0; }
        .compare-map .map { width: 100%; height: 100%; }
        .compare-divider { width: 6px; background: #fff; border-left: 1px solid var(--line); border-right: 1px solid var(--line); box-shadow: 0 0 12px rgba(0,0,0,.2); cursor: ew-resize; z-index: 1000; display: flex; align-items: center; justify-content: center; }
        .compare-divider i { color: var(--muted); font-size: .7rem; }
        .compare-header { position: absolute; top: 18px; left: 18px; right: 18px; z-index: 1100; background: rgba(255,255,255,.92); backdrop-filter: blur(18px); border-radius: var(--radius); padding: 12px 18px; box-shadow: var(--shadow-md); border: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        [data-theme="dark"] .compare-header { background: rgba(19,28,46,.94); }
        .badge-wakaf { background: #d1fae5; color: #047857; }
        .badge-aset { background: #dbeafe; color: #1d4ed8; }
        @media (max-width: 768px) { .compare-wrap { flex-direction: column; } .compare-divider { width: 100%; height: 6px; cursor: ns-resize; } }
    </style>
</head>
<body>

<div class="compare-header">
    <div class="d-flex align-items-center gap-2">
        <span class="navbar-brand-mark" style="width:34px;height:34px;font-size:15px;"><i class="fa-solid fa-map-location-dot"></i></span>
        <div>
            <div class="fw-bold" style="font-size:.95rem;">Bandingkan Peta</div>
            <small class="text-muted">Kiri: Aset Wakaf | Kanan: Aset Pemerintah</small>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge badge-wakaf"><i class="fa-solid fa-hand-holding-heart me-1"></i> Wakaf</span>
        <span class="badge badge-aset"><i class="fa-solid fa-building-columns me-1"></i> Aset Pemerintah</span>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Beranda</a>
    </div>
</div>

<div class="compare-wrap">
    <div class="compare-map" id="mapW"></div>
    <div class="compare-divider" id="divider"><i class="fa-solid fa-grip-lines-vertical d-none d-md-inline"></i><i class="fa-solid fa-grip-lines d-md-none"></i></div>
    <div class="compare-map" id="mapA"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var wakafData = @json($wakaf ?? []);
    var asetData = @json($asetPemerintah ?? []);
    var center = [-4.00165, 119.64347], zoom = 13;

    var tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

    function makeMap(id, data, color) {
        var m = L.map(id, { zoomControl: false }).setView(center, zoom);
        L.tileLayer(tileUrl, { attribution: '&copy; OpenStreetMap' }).addTo(m);
        data.forEach(function(item){
            if (!parseFloat(item.latitude) && !parseFloat(item.longitude)) return;
            var icon = L.divIcon({ className: 'custom-pin', html: '<i class="fa-solid fa-location-dot" style="color:' + color + ';font-size:28px;-webkit-text-stroke:1px #fff;"></i>', iconSize: [22, 28], iconAnchor: [11, 28] });
            L.marker([item.latitude, item.longitude], { icon: icon }).addTo(m).bindPopup(item.nama_masjid);
        });
        return m;
    }

    var mapW = makeMap('mapW', wakafData, '#10b981');
    var mapA = makeMap('mapA', asetData, '#3b82f6');
    L.control.zoom({ position: 'topright' }).addTo(mapW);

    function sync() {
        var center = mapW.getCenter(), zoom = mapW.getZoom();
        mapA.setView(center, zoom, { animate: false });
    }
    mapW.on('move zoom', sync);

    // Responsive divider
    var divider = document.getElementById('divider');
    var wrap = document.querySelector('.compare-wrap');
    var isDragging = false;
    divider.addEventListener('mousedown', function() { isDragging = true; });
    document.addEventListener('mouseup', function() { isDragging = false; });
    document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        if (window.innerWidth > 768) {
            var pct = Math.max(20, Math.min(80, (e.clientX / window.innerWidth) * 100));
            wrap.children[0].style.flex = '0 0 ' + pct + '%';
            wrap.children[2].style.flex = '0 0 ' + (100 - pct) + '%';
        } else {
            var pct = Math.max(20, Math.min(80, (e.clientY / window.innerHeight) * 100));
            wrap.children[0].style.flex = '0 0 ' + pct + '%';
            wrap.children[2].style.flex = '0 0 ' + (100 - pct) + '%';
        }
        window.dispatchEvent(new Event('resize'));
    });
</script>
</body>
</html>
