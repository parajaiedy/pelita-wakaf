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
                        <label class="form-label fw-semibold">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="{{ $aset->kecamatan }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kelurahan</label>
                        <input type="text" name="kelurahan" class="form-control" value="{{ $aset->kelurahan }}" required>
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
<script>
    // Ambil koordinat awal dari data database
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
</script>

</body>
</html>