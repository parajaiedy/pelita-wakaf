<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Publik - Pelita Wakaf Parepare</title>
    <!-- Bootstrap & Leaflet CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body, html { height: 100%; margin: 0; overflow: hidden; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); padding: 10px 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; }
        #map { height: calc(100vh - 60px); width: 100%; z-index: 1; }
        
        .popup-custom h6 { font-weight: 700; color: #0d6efd; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .popup-custom p { margin: 4px 0; font-size: 13.5px; }

        .custom-pin {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            background: transparent;
            border: none;
        }

        /* Label Kecamatan */
        .kec-label {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #333;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 1px 1px 3px rgba(0,0,0,0.3);
            text-align: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark d-flex justify-content-between align-items-center">
    <div class="text-white d-flex align-items-center">
        <i class="fa-solid fa-map-location-dot fs-3 me-2"></i>
        <div>
            <h5 class="mb-0 fw-bold">Pelita Wakaf</h5>
            <small class="opacity-75 d-none d-sm-block" style="font-size: 12px;">Peta Persebaran Aset Wakaf Kota Parepare</small>
        </div>
    </div>
    <a href="{{ route('login') }}" class="btn btn-light btn-sm fw-semibold text-primary rounded-pill px-3 shadow-sm">
        <i class="fa-solid fa-right-to-bracket me-1"></i> Login Admin
    </a>
</nav>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // 1. Inisialisasi Peta
    var map = L.map('map').setView([-4.015, 119.635], 13); // Digeser sedikit agar Parepare pas di tengah layar

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // 2. Data GeoJSON Kasar untuk Batas 4 Kecamatan di Parepare (Polygon)
    var dataKecamatan = [
        {
            "type": "Feature",
            "properties": { "kecamatan": "Kec. Bacukiki", "color": "#28a745" },
            "geometry": {
                "type": "Polygon",
                "coordinates": [[
                    [119.645, -4.010], [119.670, -3.990], [119.690, -4.000], [119.705, -4.030], 
                    [119.710, -4.060], [119.670, -4.070], [119.645, -4.045], [119.645, -4.010]
                ]]
            }
        },
        {
            "type": "Feature",
            "properties": { "kecamatan": "Kec. Bacukiki Barat", "color": "#dc3545" },
            "geometry": {
                "type": "Polygon",
                "coordinates": [[
                    [119.620, -4.012], [119.645, -4.010], [119.645, -4.045], 
                    [119.625, -4.060], [119.615, -4.050], [119.615, -4.020], [119.620, -4.012]
                ]]
            }
        },
        {
            "type": "Feature",
            "properties": { "kecamatan": "Kec. Soreang", "color": "#007bff" },
            "geometry": {
                "type": "Polygon",
                "coordinates": [[
                    [119.625, -4.002], [119.645, -3.970], [119.660, -3.960], 
                    [119.670, -3.990], [119.645, -4.010], [119.635, -4.002], [119.625, -4.002]
                ]]
            }
        },
        {
            "type": "Feature",
            "properties": { "kecamatan": "Kec. Ujung", "color": "#fd7e14" },
            "geometry": {
                "type": "Polygon",
                "coordinates": [[
                    [119.615, -4.015], [119.620, -4.012], [119.625, -4.002], 
                    [119.635, -4.002], [119.645, -4.010], [119.620, -4.025], [119.615, -4.015]
                ]]
            }
        }
    ];

    // 3. Menambahkan Polygon ke Peta
    L.geoJSON(dataKecamatan, {
        style: function (feature) {
            return {
                color: feature.properties.color,
                fillColor: feature.properties.color,
                weight: 2,
                opacity: 0.8,
                fillOpacity: 0.15 // Transparansi isi warna wilayah
            };
        },
        onEachFeature: function (feature, layer) {
            // Label Nama Kecamatan di tengah polygon
            var center = layer.getBounds().getCenter();
            var labelMarker = L.marker(center, {
                icon: L.divIcon({
                    className: 'kec-label',
                    html: feature.properties.kecamatan,
                    iconSize: [110, 25],
                    iconAnchor: [55, 12]
                }),
                interactive: false
            }).addTo(map);
        }
    }).addTo(map);


    // 4. Proses Menampilkan Data Aset (Titik Multi-warna)
    var asetData = [
        @foreach($asets as$aset)
        {
            nama: "{!! $aset->nama_masjid !!}",
            lat: {{ $aset->latitude ?? 0 }},
            lng: {{ $aset->longitude ?? 0 }},
            kecamatan: "{!! $aset->kecamatan !!}",
            kelurahan: "{!! $aset->kelurahan !!}",
            status: "{!! $aset->status_sertipikat !!}",
            jenis_hak: "{!! $aset->jenis_hak ?? '-' !!}",
            luas: "{{ $aset->luas_tanah }}"
        },
        @endforeach
    ];

    asetData.forEach(function(data) {
        if(data.lat !== 0 && data.lng !== 0) {
            
            // Default: Merah (Kosong / Belum Bersertipikat)
            var pinColor = '#dc3545'; 
            
            // KUNCI PERUBAHAN WARNA BERDASARKAN JENIS HAK
            if (data.jenis_hak === 'Hak Wakaf') {
                pinColor = '#198754'; // Hijau
            } else if (data.jenis_hak === 'Hak Milik') {
                pinColor = '#ffc107'; // Kuning
            } else if (data.jenis_hak === 'Hak Guna Bangunan' || data.jenis_hak === 'HGB') {
                pinColor = '#d63384'; // Pink
            } else if (data.jenis_hak === 'Hak Pakai') {
                pinColor = '#8B4513'; // Coklat
            }

            // Pembuatan Marker Vektor FontAwesome
            var customIcon = L.divIcon({
                className: 'custom-pin',
                html: `<i class="fa-solid fa-location-dot" style="color: ${pinColor}; font-size: 32px; text-shadow: 2px 2px 4px rgba(0,0,0,0.6); -webkit-text-stroke: 1px #fff;"></i>`,
                iconSize: [30, 36],
                iconAnchor: [15, 36],
                popupAnchor: [0, -36]
            });
            
            var marker = L.marker([data.lat, data.lng], {icon: customIcon}).addTo(map);
            
            var iconStatus = data.status.includes('Sudah') 
                             ? '<i class="fa-solid fa-check-circle text-success"></i>' 
                             : '<i class="fa-solid fa-circle-exclamation text-danger"></i>';
            
            var popupContent = `
                <div class="popup-custom">
                    <h6><i class="fa-solid fa-mosque me-1"></i> ${data.nama}</h6>
                    <p><i class="fa-solid fa-map-pin text-secondary" style="width:15px;"></i> ${data.kelurahan}, Kec. ${data.kecamatan}</p>
                    <p>${iconStatus} <strong>${data.status}</strong></p>
                    <p><i class="fa-solid fa-file-signature text-secondary" style="width:15px;"></i> Hak: <strong>${data.jenis_hak}</strong></p>
                    <p><i class="fa-solid fa-maximize text-secondary" style="width:15px;"></i> Luas: ${data.luas} m²</p>
                </div>
            `;
            marker.bindPopup(popupContent);
        }
    });
</script>
</body>
</html>