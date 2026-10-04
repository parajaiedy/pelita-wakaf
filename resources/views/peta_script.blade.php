<script>
    var map = L.map('map', { zoomControl: false }).setView([-4.00165, 119.64347], 13);
    L.control.zoom({ position: 'topright' }).addTo(map);

    var streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
    var satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });
    var darkModeMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles &copy; Esri' });
    var baseMaps = { "🗺️ Peta Jalan (Terang)": streetMap, "🛰️ Peta Satelit": satelliteMap, "🌙 Mode Gelap": darkModeMap };

    var batasWilayahLayer = L.layerGroup().addTo(map);
    var warnaKecamatan = { 'Bacukiki': '#059669', 'Bacukiki Barat': '#db2777', 'Soreang': '#2563eb', 'Ujung': '#7c3aed' };

    var batasKelurahanLayer = L.geoJSON(null, {
        style: function(feature) {
            var kecamatan = feature.properties.WADMKC || '';
            var warna = warnaKecamatan[kecamatan] || '#0d9488';
            return { color: warna, weight: 1.2, opacity: 0.9, fillColor: warna, fillOpacity: 0.08 };
        },
        onEachFeature: function(feature, layer) { layer.bindTooltip(feature.properties.NAMOBJ || 'Kelurahan', { sticky: true, direction: 'top' }); }
    });

    fetch(@json(asset('batas-kelurahan-parepare.geojson'))).then(r => r.json()).then(data => batasKelurahanLayer.addData(data)).catch(() => {});
    fetch(@json(asset('batas-kecamatan-parepare.geojson'))).then(r => r.json()).then(data => {
        L.geoJSON(data, {
            style: function(feature) { var w = warnaKecamatan[feature.properties.NAMOBJ] || '#0d9488'; return { color: w, weight: 2, opacity: 0.9, fillColor: w, fillOpacity: 0.18 }; },
            onEachFeature: function(feature, layer) { layer.bindTooltip('Kec. ' + (feature.properties.NAMOBJ || 'Kecamatan'), { permanent: true, direction: 'center', className: 'kec-label' }); }
        }).addTo(batasWilayahLayer);
    }).catch(() => {});

    var overlayMaps = { "🗺️ Batas Kecamatan": batasWilayahLayer, "🏘️ Batas Kelurahan": batasKelurahanLayer };
    L.control.layers(baseMaps, overlayMaps, { position: 'topright' }).addTo(map);

    var dataMasjid = @json($data);

    var clusterGroup = L.markerClusterGroup({
        maxClusterRadius: 55, showCoverageOnHover: false,
        iconCreateFunction: function(cluster) { return L.divIcon({ html: '<div class="cluster-badge">' + cluster.getChildCount() + '</div>', className: 'marker-cluster-pelita', iconSize: [42, 42] }); }
    }).addTo(map);

    var allMarkers = [];
    let total = dataMasjid.length;
    let bersertipikat = 0, belumSertipikat = 0;

    dataMasjid.forEach(function(item) {
        if (item.status_sertipikat === "Sudah Bersertipikat") bersertipikat++; else belumSertipikat++;
        if (!parseFloat(item.latitude) && !parseFloat(item.longitude)) return;

        var pinColor = '#dc3545';
        var hak = (item.jenis_hak || '').toLowerCase();
        if (hak.includes('wakaf')) pinColor = '#198754';
        else if (hak.includes('milik')) pinColor = '#ffc107';
        else if (hak.includes('bangunan') || hak.includes('hgb')) pinColor = '#d63384';
        else if (hak.includes('pakai')) pinColor = '#8B4513';

        var customIcon = L.divIcon({
            className: 'custom-pin',
            html: '<i class="fa-solid fa-location-dot" style="color: ' + pinColor + '; font-size: 36px; text-shadow: 2px 2px 4px rgba(0,0,0,0.6); -webkit-text-stroke: 1px #fff;"></i>',
            iconSize: [30, 36], iconAnchor: [15, 36], popupAnchor: [0, -36]
        });

        var statusBadge = (item.status_sertipikat === "Sudah Bersertipikat")
            ? '<span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Sudah Bersertipikat</span>'
            : '<span class="badge bg-danger"><i class="fa-solid fa-xmark me-1"></i>Belum Bersertipikat</span>';
        var googleMapsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' + item.latitude + ',' + item.longitude;

        var marker = L.marker([item.latitude, item.longitude], { icon: customIcon });
        marker.nama = item.nama_masjid; marker.kecamatan = item.kecamatan; marker.latlngVal = [item.latitude, item.longitude];
        allMarkers.push(marker); clusterGroup.addLayer(marker);
        marker.bindTooltip(item.nama_masjid, { permanent: false, direction: 'top' });
        marker.bindPopup(
            '<div class="popup-title">' + item.nama_masjid + '</div>' +
            '<div class="popup-info"><i class="fa-solid fa-map-pin me-1 text-secondary"></i><b>Kec. ' + item.kecamatan + '</b> / Kel. ' + item.kelurahan + '</div>' +
            '<div class="popup-info"><i class="fa-solid fa-file-contract me-1 text-secondary"></i>' + (item.jenis_hak || '-') + ' No. ' + (item.nomor_hak || '-') + '</div>' +
            '<div class="popup-info"><i class="fa-solid fa-ruler-combined me-1 text-secondary"></i>Luas: <b>' + item.luas_tanah + ' m²</b></div>' +
            '<div class="my-2">' + statusBadge + '</div>' +
            '<a href="' + googleMapsUrl + '" target="_blank" class="btn btn-primary btn-route text-white w-100 mt-1"><i class="fa-solid fa-diamond-turn-right me-1"></i> Rute Google Maps</a>'
        );
    });

    document.getElementById("total-aset").innerText = total;
    document.getElementById("jml-sertipikat").innerText = bersertipikat;
    document.getElementById("jml-belum").innerText = belumSertipikat;

    var inputCari = document.getElementById('cari-masjid');
    var hasilCari = document.getElementById('hasil-cari');
    var DEFAULT_VIEW = [-4.00165, 119.64347], DEFAULT_ZOOM = 13;
    inputCari.addEventListener('input', function() {
        var q = this.value.trim().toLowerCase();
        clusterGroup.clearLayers();
        var cocok = [];
        allMarkers.forEach(function(m) {
            var nama = (m.nama || '').toLowerCase(), kec = (m.kecamatan || '').toLowerCase();
            if (!q || nama.indexOf(q) !== -1 || kec.indexOf(q) !== -1) { clusterGroup.addLayer(m); if (q) cocok.push(L.latLng(m.latlngVal[0], m.latlngVal[1])); }
        });
        if (q && cocok.length) { map.fitBounds(L.latLngBounds(cocok).pad(0.25)); hasilCari.classList.remove('d-none'); hasilCari.innerHTML = '<i class="fa-solid fa-circle-check text-success me-1"></i>' + cocok.length + ' aset ditemukan'; }
        else if (q) { hasilCari.classList.remove('d-none'); hasilCari.innerHTML = '<i class="fa-solid fa-circle-exclamation text-danger me-1"></i>Tidak ada aset yang cocok'; map.setView(DEFAULT_VIEW, DEFAULT_ZOOM); }
        else { hasilCari.classList.add('d-none'); map.setView(DEFAULT_VIEW, DEFAULT_ZOOM); }
    });
</script>
