<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Aset Wakaf</title>
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
        <div class="card-header bg-warning text-dark py-3">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Data Aset Wakaf</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.update', $aset->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Tanah Wakaf / Masjid</label>
                    <input type="text" name="nama_masjid" class="form-control" value="{{ $aset->nama_masjid }}" required>
                </div>

                <div class="row">
                   <div class="col-md-6 mb-3">
                        <label for="kecamatan" class="form-label fw-semibold">Kecamatan</label>
                        <select name="kecamatan" id="kecamatan" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            <option value="Bacukiki" {{ $aset->kecamatan == 'Bacukiki' ? 'selected' : '' }}>Bacukiki</option>
                            <option value="Bacukiki Barat" {{ $aset->kecamatan == 'Bacukiki Barat' ? 'selected' : '' }}>Bacukiki Barat</option>
                            <option value="Soreang" {{ $aset->kecamatan == 'Soreang' ? 'selected' : '' }}>Soreang</option>
                            <option value="Ujung" {{ $aset->kecamatan == 'Ujung' ? 'selected' : '' }}>Ujung</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kelurahan" class="form-label fw-semibold">Kelurahan</label>
                        <select name="kelurahan" id="kelurahan" class="form-select" data-selected="{{ $aset->kelurahan }}" required>
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                    </div>
                </div>

                <!-- Interactive Map Picker -->
                <div class="mb-3">
                    <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-location-crosshairs text-danger me-1"></i> Sesuaikan Lokasi pada Peta</span>
                        <small class="text-muted font-monospace">Klik/Geser marker</small>
                    </label>
                    <div id="map-picker" class="border shadow-sm"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control bg-light" value="{{ $aset->latitude }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control bg-light" value="{{ $aset->longitude }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status Sertipikat</label>
                        <select name="status_sertipikat" class="form-select" required>
                            <option value="Sudah Bersertipikat" {{ $aset->status_sertipikat == 'Sudah Bersertipikat' ? 'selected' : '' }}>Sudah Bersertipikat</option>
                            <option value="Belum Bersertipikat" {{ $aset->status_sertipikat == 'Belum Bersertipikat' ? 'selected' : '' }}>Belum Bersertipikat</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Luas Tanah (m²)</label>
                        <input type="number" name="luas_tanah" class="form-control" value="{{ $aset->luas_tanah }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jenis Hak</label>
                        <select name="jenis_hak" class="form-select">
                            <option value="">-- Pilih Jenis Hak --</option>
                            <option value="Hak Wakaf" {{ $aset->jenis_hak == 'Hak Wakaf' ? 'selected' : '' }}>Hak Wakaf</option>
                            <option value="Hak Milik" {{ $aset->jenis_hak == 'Hak Milik' ? 'selected' : '' }}>Hak Milik</option>
                            <option value="Hak Guna Bangunan" {{ $aset->jenis_hak == 'Hak Guna Bangunan' ? 'selected' : '' }}>Hak Guna Bangunan (HGB)</option>
                            <option value="Hak Pakai" {{ $aset->jenis_hak == 'Hak Pakai' ? 'selected' : '' }}>Hak Pakai</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nomor Hak / Sertipikat</label>
                        <input type="text" name="nomor_hak" class="form-control" value="{{ $aset->nomor_hak }}" placeholder="Contoh: 00012 atau -">
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Perubahan</button>
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Script Peta -->
<script>
    var currentLat = parseFloat("{{ $aset->latitude }}");
    var currentLng = parseFloat("{{ $aset->longitude }}");

    var pickerMap = L.map('map-picker').setView([currentLat, currentLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(pickerMap);

    var marker = L.marker([currentLat, currentLng], { draggable: true }).addTo(pickerMap);

    function updateInputs(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
    }

    marker.on('dragend', function (e) {
        var position = marker.getLatLng();
        updateInputs(position.lat, position.lng);
    });

    pickerMap.on('click', function (e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        updateInputs(lat, lng);
    });

    const inputLat = document.querySelector('input[name="latitude"]');
    const inputLng = document.querySelector('input[name="longitude"]');

    function pindahPinSesuaiKetik() {
        let latTeks = parseFloat(inputLat.value);
        let lngTeks = parseFloat(inputLng.value);
        
        if (!isNaN(latTeks) && !isNaN(lngTeks)) {
            marker.setLatLng([latTeks, lngTeks]);
            pickerMap.setView([latTeks, lngTeks]); 
        }
    }

    if(inputLat && inputLng) {
        inputLat.addEventListener('input', pindahPinSesuaiKetik);
        inputLng.addEventListener('input', pindahPinSesuaiKetik);
    }
</script>

<!-- Script Kelurahan Otomatis (Anti-Macet) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            const dataWilayah = {
                "Bacukiki": ["Galung Maloang", "Lemoe", "Lompoe", "Watang Bacukiki"],
                "Bacukiki Barat": ["Bumi Harapan", "Cappa Galung", "Kampung Baru", "Lumpue", "Sumpang Minangae", "Tiro Sompe"],
                "Soreang": ["Bukit Harapan", "Bukit Indah", "Kampung Pisang", "Lakessi", "Ujung Baru", "Ujung Lare", "Watang Soreang"],
                "Ujung": ["Labukkang", "Lapadde", "Mallusetasi", "Ujung Bulu", "Ujung Sabbang"]
            };

            const kecSelect = document.getElementById('kecamatan');
            const kelSelect = document.getElementById('kelurahan');
            
            // Ambil data kelurahan lama yang tersimpan
            const selectedKelurahan = kelSelect.getAttribute('data-selected');

            function updateKelurahan() {
                const kec = kecSelect.value;
                kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                
                if (kec && dataWilayah[kec]) {
                    dataWilayah[kec].forEach(function(kel) {
                        const option = document.createElement('option');
                        option.value = kel;
                        option.textContent = kel;
                        
                        // Otomatis pilih kelurahan jika sama dengan data di database
                        if (kel === selectedKelurahan) {
                            option.selected = true;
                        }
                        
                        kelSelect.appendChild(option);
                    });
                }
            }

            if(kecSelect && kelSelect) {
                kecSelect.addEventListener('change', updateKelurahan);
                
                // Langsung jalankan saat halaman pertama dibuka supaya kelurahan lama muncul
                if (kecSelect.value) {
                    updateKelurahan();
                }
            }
        }, 500); // Jeda aman
    });
</script>
</body>
</html>