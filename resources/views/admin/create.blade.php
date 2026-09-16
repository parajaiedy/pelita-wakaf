<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Aset Wakaf</title>
    <!-- Bootstrap 5 CSS, FontAwesome, Leaflet CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map-picker { height: 280px; width: 100%; border-radius: 8px; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4 mb-5" style="max-width: 750px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-plus-circle me-2"></i>Tambah Data Aset Wakaf Baru</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Tanah Wakaf / Masjid</label>
                    <input type="text" name="nama_masjid" class="form-control" placeholder="Contoh: Masjid Agung Parepare" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
    <label for="kecamatan" class="form-label fw-semibold">Kecamatan</label>
    <select name="kecamatan" id="kecamatan" class="form-select" required>
        <option value="">-- Pilih Kecamatan --</option>
        <option value="Bacukiki">Bacukiki</option>
        <option value="Bacukiki Barat">Bacukiki Barat</option>
        <option value="Soreang">Soreang</option>
        <option value="Ujung">Ujung</option>
    </select>
</div>

<div class="col-md-6 mb-3">
    <label for="kelurahan" class="form-label fw-semibold">Kelurahan</label>
    <select name="kelurahan" id="kelurahan" class="form-select" required>
        <option value="">-- Pilih Kelurahan --</option>
    </select>
</div>


                <!-- Interactive Map Picker -->
                <div class="mb-3">
                    <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-location-crosshairs text-danger me-1"></i> Pilih Lokasi pada Peta (Klik / Geser Marker)</span>
                        <small class="text-muted font-monospace">Parepare</small>
                    </label>
                    <div id="map-picker" class="border shadow-sm"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control bg-light" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control bg-light" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status Sertipikat</label>
                        <select name="status_sertipikat" class="form-select" required>
                            <option value="Sudah Bersertipikat">Sudah Bersertipikat</option>
                            <option value="Belum Bersertipikat">Belum Bersertipikat</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Luas Tanah (m²)</label>
                        <input type="number" name="luas_tanah" class="form-control" placeholder="1500" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jenis Hak</label>
                        <select name="jenis_hak" class="form-select">
                            <option value="">-- Pilih Jenis Hak --</option>
                            <option value="Hak Wakaf">Hak Wakaf</option>
                            <option value="Hak Milik">Hak Milik</option>
                            <option value="Hak Guna Bangunan">Hak Guna Bangunan (HGB)</option>
                            <option value="Hak Pakai">Hak Pakai</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nomor Hak / Sertipikat</label>
                        <input type="text" name="nomor_hak" class="form-control" placeholder="Contoh: 00012 atau -">
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-save me-1"></i> Simpan Data</button>
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inisialisasi Peta Picker (Default: Pusat Parepare)
    var defaultLat = -4.00165;
    var defaultLng = 119.64347;

    var pickerMap = L.map('map-picker').setView([defaultLat, defaultLng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(pickerMap);

    // Marker yang bisa digeser (Draggable)
    var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(pickerMap);

    // Function update nilai input form
    function updateInputs(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
    }

    // Set nilai awal
    updateInputs(defaultLat, defaultLng);

    // Event saat marker digeser
    marker.on('dragend', function (e) {
        var position = marker.getLatLng();
        updateInputs(position.lat, position.lng);
    });

    // Event saat peta diklik
    pickerMap.on('click', function (e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        updateInputs(lat, lng);
    });
</script>
<script>
    // --- Fitur geser pin otomatis saat kolom diketik manual ---
    const inputLat = document.querySelector('input[name="latitude"]');
    const inputLng = document.querySelector('input[name="longitude"]');

    function pindahPinSesuaiKetik() {
        let latTeks = parseFloat(inputLat.value);
        let lngTeks = parseFloat(inputLng.value);
        
        // Jika angkanya valid, geser pin dan pusatkan peta ke titik tersebut
        if (!isNaN(latTeks) && !isNaN(lngTeks)) {
            marker.setLatLng([latTeks, lngTeks]);
            pickerMap.setView([latTeks, lngTeks]); 
        }
    }

    // Pasang pendeteksi agar pin bergeser setiap kali Bapak mengetik
    if(inputLat && inputLng) {
        inputLat.addEventListener('input', pindahPinSesuaiKetik);
        inputLng.addEventListener('input', pindahPinSesuaiKetik);
    }
</script>
    const dataWilayah = {
        "Bacukiki": ["Galung Maloang", "Lemoe", "Lompoe", "Watang Bacukiki"],
        "Bacukiki Barat": ["Bumi Harapan", "Cappa Galung", "Kampung Baru", "Lumpue", "Sumpang Minangae", "Tiro Sompe"],
        "Soreang": ["Bukit Harapan", "Bukit Indah", "Kampung Pisang", "Lakessi", "Ujung Baru", "Ujung Lare", "Watang Soreang"],
        "Ujung": ["Labukkang", "Lapadde", "Mallusetasi", "Ujung Bulu", "Ujung Sabbang"]
    };

    const kecSelect = document.getElementById('kecamatan');
    const kelSelect = document.getElementById('kelurahan');

    kecSelect.addEventListener('change', function() {
        const kec = this.value;
        kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        
        if (kec && dataWilayah[kec]) {
            dataWilayah[kec].forEach(function(kel) {
                const option = document.createElement('option');
                option.value = kel;
                option.textContent = kel;
                kelSelect.appendChild(option);
            });
        }
    });
</script>
</body>
</html>