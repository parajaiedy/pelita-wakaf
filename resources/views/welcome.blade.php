<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Pelita Wakaf - Kota Parepare</title>
    
    <!-- Leaflet CSS & FontAwesome Icons -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    @include('partials.styles')
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        #map { height: 100vh; width: 100%; z-index: 1; }

        /* Kotak pencarian nama aset */
        .search-box .input-group-text {
            background: #fff; border-right: none; border-radius: 10px 0 0 10px;
        }
        .search-box .form-control {
            border-left: none; border-radius: 0 10px 10px 0;
        }
        .search-box .form-control:focus {
            box-shadow: none; border-color: var(--brand-500);
        }

        /* Legenda warna pin */
        .legend-box { display: flex; flex-direction: column; gap: 6px; }
        .legend-row { display: flex; align-items: center; gap: 9px; font-size: .82rem; color: var(--ink-soft); }
        .legend-dot {
            width: 14px; height: 14px; border-radius: 50% 50% 50% 0;
            flex-shrink: 0; transform: rotate(-45deg);
            box-shadow: 0 1px 2px rgba(0,0,0,.25); border: 2px solid #fff;
        }

        /* Cluster marker (titik yang bertumpuk dirapikan jadi angka) */
        .marker-cluster-pelita { background: transparent; }
        .marker-cluster-pelita .cluster-badge {
            width: 42px; height: 42px; border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-600), var(--brand-500));
            color: #fff; font-weight: 800; font-size: 14px;
            display: flex; align-items: center; justify-content: center;
            border: 3px solid rgba(255,255,255,.9);
            box-shadow: 0 4px 12px rgba(13,148,136,.45);
        }
    </style>
</head>
<body>

    <!-- Panel Sidebar Statistik -->
    <div class="sidebar-panel">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="navbar-brand-mark"><i class="fa-solid fa-mosque"></i></span>
            <div>
                <div class="sidebar-brand">Pelita Wakaf</div>
                <small class="text-muted">BPN Kota Parepare</small>
            </div>
        </div>

        <!-- Kotak Pencarian Nama Aset -->
        <div class="search-box mb-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" id="cari-masjid" class="form-control" placeholder="Cari nama masjid / tanah..." autocomplete="off">
            </div>
            <div id="hasil-cari" class="small text-muted mt-1 d-none"></div>
        </div>

        <hr class="my-3">

        <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-chart-pie me-1"></i> Ringkasan Aset</h6>

        <div class="stat-card">
            <div>
                <small class="text-muted d-block">Total Aset Wakaf</small>
                <span class="fw-bold fs-5" id="total-aset">0</span>
            </div>
            <span class="stat-icon-wrap"><i class="fa-solid fa-map-location-dot"></i></span>
        </div>

        <div class="stat-card hijau">
            <div>
                <small class="text-muted d-block">Sudah Bersertipikat</small>
                <span class="fw-bold fs-5 text-success" id="jml-sertipikat">0</span>
            </div>
            <span class="stat-icon-wrap"><i class="fa-solid fa-circle-check"></i></span>
        </div>

        <div class="stat-card merah">
            <div>
                <small class="text-muted d-block">Belum Bersertipikat</small>
                <span class="fw-bold fs-5 text-danger" id="jml-belum">0</span>
            </div>
            <span class="stat-icon-wrap"><i class="fa-solid fa-circle-exclamation"></i></span>
        </div>

        <!-- Legenda Warna Pin -->
        <h6 class="fw-bold text-secondary mt-3 mb-2"><i class="fa-solid fa-palette me-1"></i> Legenda Warna Pin</h6>
        <div class="legend-box">
            <div class="legend-row"><span class="legend-dot" style="background:#198754"></span> Hak Wakaf</div>
            <div class="legend-row"><span class="legend-dot" style="background:#ffc107"></span> Hak Milik</div>
            <div class="legend-row"><span class="legend-dot" style="background:#d63384"></span> Hak Guna Bangunan (HGB)</div>
            <div class="legend-row"><span class="legend-dot" style="background:#8B4513"></span> Hak Pakai</div>
            <div class="legend-row"><span class="legend-dot" style="background:#dc3545"></span> Belum Bersertipikat / Tanpa Hak</div>
        </div>

        <a href="{{ route('admin.index') }}" class="btn-admin-floating mt-3">
            <i class="fa-solid fa-user-shield me-1"></i> Dashboard Admin
        </a>
    </div>

    <!-- Container Peta -->
    <div id="map"></div>

    <!-- Leaflet JS & Bootstrap Bundle -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var map = L.map('map', {
            zoomControl: false
        }).setView([-4.00165, 119.64347], 13);

        L.control.zoom({ position: 'topright' }).addTo(map);

        // Base Maps
        var streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

        var darkModeMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

        var baseMaps = {
            "🗺️ Peta Jalan (Terang)": streetMap,
            "🛰️ Peta Satelit": satelliteMap,
            "🌙 Mode Gelap (Dark Mode)": darkModeMap
        };

        // Layer batas wilayah: kecamatan (ringkas) dan kelurahan asli dari BIG.
        var batasWilayahLayer = L.layerGroup().addTo(map);

        var warnaKecamatan = {
            'Bacukiki': '#059669',
            'Bacukiki Barat': '#db2777',
            'Soreang': '#2563eb',
            'Ujung': '#7c3aed'
        };

        // Tidak aktif saat halaman dibuka agar peta tetap ringan; aktifkan dari kontrol layer kanan atas.
        var batasKelurahanLayer = L.geoJSON(null, {
            style: function(feature) {
                var kecamatan = feature.properties.WADMKC || '';
                var warna = warnaKecamatan[kecamatan] || '#0d9488';
                return {
                    color: warna,
                    weight: 1.2,
                    opacity: 0.9,
                    fillColor: warna,
                    fillOpacity: 0.08
                };
            },
            onEachFeature: function(feature, layer) {
                var namaKelurahan = feature.properties.NAMOBJ || 'Kelurahan';
                var kecamatan = feature.properties.WADMKC || '-';
                layer.bindTooltip(namaKelurahan, { sticky: true, direction: 'top' });
                layer.bindPopup(
                    '<div class="popup-title">Kelurahan ' + namaKelurahan + '</div>' +
                    '<div class="popup-info"><i class="fa-solid fa-map-location-dot me-1 text-secondary"></i>Kecamatan: <b>' + kecamatan + '</b></div>' +
                    '<div class="popup-info"><i class="fa-solid fa-building-columns me-1 text-secondary"></i>Kota Parepare, Sulawesi Selatan</div>'
                );
            }
        });

        var overlayMaps = {
            "🗺️ Batas Kecamatan": batasWilayahLayer,
            "🏘️ Batas Kelurahan (BIG)": batasKelurahanLayer
        };

        L.control.layers(baseMaps, overlayMaps, { position: 'topright' }).addTo(map);

        // Muat 22 batas kelurahan resmi Kota Parepare dari file GeoJSON lokal.
        fetch(@json(asset('batas-kelurahan-parepare.geojson')))
            .then(function(response) {
                if (!response.ok) throw new Error('File batas kelurahan tidak dapat dimuat');
                return response.json();
            })
            .then(function(data) {
                batasKelurahanLayer.addData(data);
            })
            .catch(function(error) {
                console.warn('Batas kelurahan tidak dimuat:', error.message);
            });

        // Muat 4 batas kecamatan resmi Kota Parepare dari BIG.
        fetch(@json(asset('batas-kecamatan-parepare.geojson')))
            .then(function(response) {
                if (!response.ok) throw new Error('File batas kecamatan tidak dapat dimuat');
                return response.json();
            })
            .then(function(data) {
                L.geoJSON(data, {
                    style: function(feature) {
                        var warna = warnaKecamatan[feature.properties.NAMOBJ] || '#0d9488';
                        return {
                            color: warna,
                            weight: 2,
                            opacity: 0.9,
                            fillColor: warna,
                            fillOpacity: 0.18
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        var namaKecamatan = feature.properties.NAMOBJ || 'Kecamatan';
                        layer.bindTooltip('Kec. ' + namaKecamatan, {
                            permanent: true,
                            direction: 'center',
                            className: 'fw-bold text-dark bg-white px-2 py-1 rounded shadow-sm border-0'
                        });
                        layer.bindPopup(
                            '<div class="popup-title">Kecamatan ' + namaKecamatan + '</div>' +
                            '<div class="popup-info"><i class="fa-solid fa-building-columns me-1 text-secondary"></i>Kota Parepare, Sulawesi Selatan</div>'
                        );
                    }
                }).addTo(batasWilayahLayer);
            })
            .catch(function(error) {
                console.warn('Batas kecamatan tidak dimuat:', error.message);
            });

        // Load data dari Controller
        var dataMasjid = @json($asetWakaf);

        // Grup cluster marker (203 titik yang bertumpuk dirapikan jadi angka)
        var clusterGroup = L.markerClusterGroup({
            maxClusterRadius: 55,
            showCoverageOnHover: false,
            iconCreateFunction: function(cluster) {
                var count = cluster.getChildCount();
                return L.divIcon({
                    html: '<div class="cluster-badge">' + count + '</div>',
                    className: 'marker-cluster-pelita',
                    iconSize: [42, 42]
                });
            }
        }).addTo(map);

        var allMarkers = [];

        let total = dataMasjid.length;
        let bersertipikat = 0;
        let belumSertipikat = 0;

        dataMasjid.forEach(function(item) {
            
            // 1. Penghitungan Statistik Sidebar
            if (item.status_sertipikat === "Sudah Bersertipikat") {
                bersertipikat++;
            } else {
                belumSertipikat++;
            }

            // 2. Logika Vektor Warna-Warni Berdasarkan Hak (Kebal Spasi)
            var pinColor = '#dc3545'; // Default: Merah (Kosong / Belum Sertipikat)
            var hak = (item.jenis_hak || '').toLowerCase();

            if (hak.includes('wakaf')) {
                pinColor = '#198754'; // Hijau
            } else if (hak.includes('milik')) {
                pinColor = '#ffc107'; // Kuning
            } else if (hak.includes('bangunan') || hak.includes('hgb')) {
                pinColor = '#d63384'; // Pink
            } else if (hak.includes('pakai')) {
                pinColor = '#8B4513'; // Coklat
            }

            // Membuat Ikon Vektor FontAwesome
            var customIcon = L.divIcon({
                className: 'custom-pin',
                html: `<i class="fa-solid fa-location-dot" style="color: ${pinColor}; font-size: 36px; text-shadow: 2px 2px 4px rgba(0,0,0,0.6); -webkit-text-stroke: 1px #fff;"></i>`,
                iconSize: [30, 36],
                iconAnchor: [15, 36],
                popupAnchor: [0, -36]
            });

            // 3. Status dan Pembuatan Marker
            var statusBadge = (item.status_sertipikat === "Sudah Bersertipikat")
                ? '<span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Sudah Bersertipikat</span>'
                : '<span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i>Belum Bersertipikat</span>';

            var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${item.latitude},${item.longitude}`;

            var marker = L.marker([item.latitude, item.longitude], {icon: customIcon});

            // Simpan metadata untuk pencarian
            marker.nama = item.nama_masjid;
            marker.kecamatan = item.kecamatan;
            marker.latlngVal = [item.latitude, item.longitude];

            allMarkers.push(marker);
            clusterGroup.addLayer(marker);

            marker.bindTooltip(item.nama_masjid, { permanent: false, direction: 'top' });

            marker.bindPopup(`
                <div class="popup-title">${item.nama_masjid}</div>
                <div class="popup-info"><i class="fa-solid fa-map-pin me-1 text-secondary"></i> <b>Kec. ${item.kecamatan}</b> / Kel. ${item.kelurahan}</div>
                <div class="popup-info"><i class="fa-solid fa-file-contract me-1 text-secondary"></i> ${item.jenis_hak ? item.jenis_hak : '-'} No. ${item.nomor_hak ? item.nomor_hak : '-'}</div>
                <div class="popup-info"><i class="fa-solid fa-ruler-combined me-1 text-secondary"></i> Luas: <b>${item.luas_tanah} m²</b></div>
                <div class="my-2">${statusBadge}</div>
                <a href="${googleMapsUrl}" target="_blank" class="btn btn-primary btn-route text-white w-100 mt-1">
                    <i class="fa-solid fa-diamond-turn-right me-1"></i> Rute Navigasi Google Maps
                </a>
            `);
        });

        document.getElementById("total-aset").innerText = total;
        document.getElementById("jml-sertipikat").innerText = bersertipikat;
        document.getElementById("jml-belum").innerText = belumSertipikat;

        // ================= PENCARIAN NAMA ASET =================
        var inputCari = document.getElementById('cari-masjid');
        var hasilCari = document.getElementById('hasil-cari');
        var DEFAULT_VIEW = [-4.00165, 119.64347];
        var DEFAULT_ZOOM = 13;

        inputCari.addEventListener('input', function() {
            var q = this.value.trim().toLowerCase();
            clusterGroup.clearLayers();

            var cocok = [];
            allMarkers.forEach(function(m) {
                var nama = (m.nama || '').toLowerCase();
                var kec = (m.kecamatan || '').toLowerCase();
                if (!q || nama.indexOf(q) !== -1 || kec.indexOf(q) !== -1) {
                    clusterGroup.addLayer(m);
                    if (q) cocok.push(L.latLng(m.latlngVal[0], m.latlngVal[1]));
                }
            });

            if (q && cocok.length) {
                map.fitBounds(L.latLngBounds(cocok).pad(0.25));
                hasilCari.classList.remove('d-none');
                hasilCari.innerHTML = '<i class="fa-solid fa-circle-check text-success me-1"></i>' + cocok.length + ' aset ditemukan';
            } else if (q) {
                hasilCari.classList.remove('d-none');
                hasilCari.innerHTML = '<i class="fa-solid fa-circle-exclamation text-danger me-1"></i>Tidak ada aset yang cocok';
                map.setView(DEFAULT_VIEW, DEFAULT_ZOOM);
            } else {
                hasilCari.classList.add('d-none');
                map.setView(DEFAULT_VIEW, DEFAULT_ZOOM);
            }
        });
    </script>
</body>
</html>