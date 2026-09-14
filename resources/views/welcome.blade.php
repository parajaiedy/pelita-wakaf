<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Pelita Wakaf - Kota Parepare</title>
    
    <!-- Leaflet CSS & FontAwesome Icons -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

    <style>
        body, html { margin: 0; padding: 0; height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        #map { height: 100vh; width: 100%; z-index: 1; }

        .sidebar-panel {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
            width: 320px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .stat-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #0d6efd;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card.hijau { border-left-color: #198754; }
        .stat-card.merah { border-left-color: #dc3545; }

        .btn-admin-floating {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            display: block;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .btn-admin-floating:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13, 110, 253, 0.4);
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 5px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        }
        .popup-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
        }
        .popup-info {
            font-size: 13px;
            color: #475569;
            margin-bottom: 4px;
        }
        .btn-route {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 6px;
            margin-top: 8px;
            display: inline-block;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- Panel Sidebar Statistik -->
    <div class="sidebar-panel">
<style>
        /* Perbaikan khusus layar HP untuk Kotak Ringkasan */
        @media (max-width: 768px) {
            .sidebar-panel {
                top: auto !important;
                bottom: 15px !important;
                left: 15px !important;
                right: 15px !important;
                width: auto !important;
                max-height: 40vh !important; /* Jangan lebih dari setengah layar */
                overflow-y: auto !important;
                padding: 15px !important;
            }
            .sidebar-panel hr {
                margin: 0.5rem 0 !important;
            }
            .sidebar-panel h6 {
                font-size: 0.9rem !important;
                margin-bottom: 0.5rem !important;
            }
            .stat-card {
                padding: 8px 12px !important;
                margin-bottom: 8px !important;
            }
            .stat-card span.fs-5 {
                font-size: 1.1rem !important;
            }
        }
    </style>
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fa-solid fa-mosque fa-2x text-primary"></i>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Pelita Wakaf</h5>
                <small class="text-muted">BPN Kota Parepare</small>
            </div>
        </div>

        <hr class="my-3">

        <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-chart-pie me-1"></i> Ringkasan Aset</h6>

        <div class="stat-card">
            <div>
                <small class="text-muted d-block">Total Aset Wakaf</small>
                <span class="fw-bold fs-5" id="total-aset">0</span>
            </div>
            <i class="fa-solid fa-map-location-dot fa-lg text-primary"></i>
        </div>

        <div class="stat-card hijau">
            <div>
                <small class="text-muted d-block">Sudah Bersertipikat</small>
                <span class="fw-bold fs-5 text-success" id="jml-sertipikat">0</span>
            </div>
            <i class="fa-solid fa-circle-check fa-lg text-success"></i>
        </div>

        <div class="stat-card merah">
            <div>
                <small class="text-muted d-block">Belum Bersertipikat</small>
                <span class="fw-bold fs-5 text-danger" id="jml-belum">0</span>
            </div>
            <i class="fa-solid fa-circle-exclamation fa-lg text-danger"></i>
        </div>

        <a href="{{ route('admin.index') }}" class="btn-admin-floating mt-3">
            <i class="fa-solid fa-user-shield me-1"></i> Dashboard Admin
        </a>
    </div>

    <!-- Container Peta -->
    <div id="map"></div>

    <!-- Leaflet JS & Bootstrap Bundle -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

        // Layer Group untuk Batas Wilayah
        var batasWilayahLayer = L.layerGroup().addTo(map);

        var overlayMaps = {
            "🗺️ Batas Kecamatan": batasWilayahLayer
        };

        L.control.layers(baseMaps, overlayMaps, { position: 'topright' }).addTo(map);

        // Data Spatial Batas Wilayah Presisi Kota Parepare
        var dataBatasParepare = {
          "type": "FeatureCollection",
          "features": [
            {
              "type": "Feature",
              "properties": { "nama_kecamatan": "Soreang", "warna": "#2563eb" },
              "geometry": {
                "type": "Polygon",
                "coordinates": [[
                  [119.6210, -3.9650], [119.6380, -3.9650], [119.6550, -3.9720], 
                  [119.6580, -3.9950], [119.6350, -3.9980], [119.6250, -3.9950], 
                  [119.6200, -3.9820], [119.6170, -3.9720], [119.6210, -3.9650]
                ]]
              }
            },
            {
              "type": "Feature",
              "properties": { "nama_kecamatan": "Ujung", "warna": "#7c3aed" },
              "geometry": {
                "type": "Polygon",
                "coordinates": [[
                  [119.6200, -3.9980], [119.6350, -3.9980], [119.6500, -4.0020], 
                  [119.6480, -4.0180], [119.6320, -4.0180], [119.6210, -4.0140], 
                  [119.6180, -4.0060], [119.6200, -3.9980]
                ]]
              }
            },
            {
              "type": "Feature",
              "properties": { "nama_kecamatan": "Bacukiki Barat", "warna": "#db2777" },
              "geometry": {
                "type": "Polygon",
                "coordinates": [[
                  [119.6210, -4.0140], [119.6320, -4.0180], [119.6480, -4.0180], 
                  [119.6520, -4.0350], [119.6450, -4.0520], [119.6280, -4.0500], 
                  [119.6220, -4.0380], [119.6190, -4.0250], [119.6210, -4.0140]
                ]]
              }
            },
            {
              "type": "Feature",
              "properties": { "nama_kecamatan": "Bacukiki", "warna": "#059669" },
              "geometry": {
                "type": "Polygon",
                "coordinates": [[
                  [119.6380, -3.9650], [119.6750, -3.9680], [119.6950, -3.9850], 
                  [119.6980, -4.0250], [119.6850, -4.0550], [119.6450, -4.0520], 
                  [119.6520, -4.0350], [119.6480, -4.0180], [119.6500, -4.0020], 
                  [119.6580, -3.9950], [119.6550, -3.9720], [119.6380, -3.9650]
                ]]
              }
            }
          ]
        };

        // Render Polygon ke Peta
        L.geoJSON(dataBatasParepare, {
            style: function(feature) {
                return {
                    color: feature.properties.warna,
                    weight: 2,
                    opacity: 0.8,
                    fillColor: feature.properties.warna,
                    fillOpacity: 0.25
                };
            },
            onEachFeature: function(feature, layer) {
                layer.bindTooltip("Kec. " + feature.properties.nama_kecamatan, {
                    permanent: true,
                    direction: 'center',
                    className: 'fw-bold text-dark bg-white px-2 py-1 rounded shadow-sm border-0'
                });
            }
        }).addTo(batasWilayahLayer);

        // Custom Marker Icons
        var ikonHijau = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });

        var ikonMerah = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });

        // Load data dari Controller
        var dataMasjid = @json($asetWakaf);

        let total = dataMasjid.length;
        let bersertipikat = 0;
        let belumSertipikat = 0;

        dataMasjid.forEach(function(item) {
            let pilihanIkon = (item.status_sertipikat === "Sudah Bersertipikat") ? ikonHijau : ikonMerah;

            if (item.status_sertipikat === "Sudah Bersertipikat") {
                bersertipikat++;
            } else {
                belumSertipikat++;
            }

            var statusBadge = (item.status_sertipikat === "Sudah Bersertipikat")
                ? '<span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Sudah Bersertipikat</span>'
                : '<span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i>Belum Bersertipikat</span>';

            var googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${item.latitude},${item.longitude}`;

            var marker = L.marker([item.latitude, item.longitude], {icon: pilihanIkon}).addTo(map);

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
    </script>
</body>
</html>