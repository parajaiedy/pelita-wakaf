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
        /* CSS agar peta tampil satu layar penuh (Full Screen) */
        body, html { height: 100%; margin: 0; overflow: hidden; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); padding: 10px 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; }
        #map { height: calc(100vh - 60px); width: 100%; z-index: 1; }
        
        /* CSS untuk mempercantik kartu info (Pop-up) saat pin peta diklik */
        .popup-custom h6 { font-weight: 700; color: #0d6efd; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .popup-custom p { margin: 4px 0; font-size: 13.5px; }
    </style>
</head>
<body>

<!-- Bagian Header Atas -->
<nav class="navbar navbar-dark d-flex justify-content-between align-items-center">
    <div class="text-white d-flex align-items-center">
        <i class="fa-solid fa-map-location-dot fs-3 me-2"></i>
        <div>
            <h5 class="mb-0 fw-bold">Pelita Wakaf</h5>
            <small class="opacity-75 d-none d-sm-block" style="font-size: 12px;">Peta Persebaran Aset Wakaf Kota Parepare</small>
        </div>
    </div>
    <!-- Tombol menuju halaman Login Admin -->
    <a href="{{ route('login') }}" class="btn btn-light btn-sm fw-semibold text-primary rounded-pill px-3 shadow-sm">
        <i class="fa-solid fa-right-to-bracket me-1"></i> Login Admin
    </a>
</nav>

<!-- Area Peta Leaflet -->
<div id="map"></div>

<!-- Leaflet JS & Script -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // 1. Inisialisasi peta di tengah Parepare
    var map = L.map('map').setView([-4.00165, 119.64347], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // 2. Ambil data dari database yang dikirim lewat rute Laravel
    var asetData = [
        @foreach($asets as $aset)
        {
            nama: "{{ $aset->nama_masjid }}",
            lat: {{ $aset->latitude ?? 0 }},
            lng: {{ $aset->longitude ?? 0 }},
            kecamatan: "{{ $aset->kecamatan }}",
            kelurahan: "{{ $aset->kelurahan }}",
            status: "{{ $aset->status_sertipikat }}",
            luas: "{{ $aset->luas_tanah }}"
        },
        @endforeach
    ];

    // 3. Loop untuk menyebar titik/pin (marker) di peta secara otomatis
    asetData.forEach(function(data) {
        // Pastikan titik latitude longitude-nya valid
        if(data.lat !== 0 && data.lng !== 0) {
            var marker = L.marker([data.lat, data.lng]).addTo(map);
            
            // Bedakan icon ceklis (sudah) dan silang (belum sertipikat)
            var iconStatus = data.status == 'Sudah Bersertipikat' 
                             ? '<i class="fa-solid fa-check-circle text-success"></i>' 
                             : '<i class="fa-solid fa-circle-exclamation text-danger"></i>';
            
            // Isi kartu info saat diklik
            var popupContent = `
                <div class="popup-custom">
                    <h6><i class="fa-solid fa-mosque me-1"></i> ${data.nama}</h6>
                    <p><i class="fa-solid fa-map-pin text-secondary" style="width:15px;"></i> ${data.kelurahan}, Kec. ${data.kecamatan}</p>
                    <p>${iconStatus} <strong>${data.status}</strong></p>
                    <p><i class="fa-solid fa-maximize text-secondary" style="width:15px;"></i> Luas: ${data.luas} m²</p>
                </div>
            `;
            marker.bindPopup(popupContent);
        }
    });
</script>
</body>
</html>