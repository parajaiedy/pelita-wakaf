<script>
    function tutupDetail() {
        var d = document.getElementById('detailPanel');
        d.classList.remove('show'); d.setAttribute('aria-hidden','true');
    }
    function bukaDetail(item) {
        var d = document.getElementById('detailPanel');
        var h = document.getElementById('detailHero');
        document.getElementById('detailNama').textContent = item.nama_masjid || '—';
        document.getElementById('detailWilayah').textContent = 'Kec. ' + (item.kecamatan || '-') + ' / Kel. ' + (item.kelurahan || '-');
        document.getElementById('detailHak').textContent = (item.jenis_hak || '-') + (item.nomor_hak ? ' No. ' + item.nomor_hak : '');
        document.getElementById('detailLuas').textContent = (item.luas_tanah ? parseInt(item.luas_tanah).toLocaleString('id-ID') : '0') + ' m²';
        document.getElementById('detailStatus').innerHTML = item.status_sertipikat === "Sudah Bersertipikat"
            ? '<span class="badge bg-success">Sudah Bersertipikat</span>'
            : '<span class="badge bg-danger">Belum Bersertipikat</span>';
        document.getElementById('detailTindakLanjut').textContent = item.status_tindak_lanjut || 'Belum Ditindaklanjuti';
        document.getElementById('detailKoordinat').textContent = (item.latitude || '0') + ', ' + (item.longitude || '0');
        document.getElementById('detailRute').href = 'https://www.google.com/maps/dir/?api=1&destination=' + (item.latitude || 0) + ',' + (item.longitude || 0);
        var badge = document.getElementById('detailKategoriBadge');
        if (item.kategori === 'Aset Pemerintah') {
            badge.textContent = 'Aset Pemerintah'; badge.className = 'badge bg-light text-primary';
            h.className = 'detail-hero aset';
        } else {
            badge.textContent = 'Aset Wakaf'; badge.className = 'badge bg-light text-success';
            h.className = 'detail-hero wakaf';
        }
        d.classList.add('show'); d.setAttribute('aria-hidden','false');
    }

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

    Promise.all([
        fetch(@json(asset('batas-kelurahan-parepare.geojson'))).then(r => r.json()).then(data => batasKelurahanLayer.addData(data)).catch(() => {}),
        fetch(@json(asset('batas-kecamatan-parepare.geojson'))).then(r => r.json()).then(data => {
            L.geoJSON(data, {
                style: function(feature) { var w = warnaKecamatan[feature.properties.NAMOBJ] || '#0d9488'; return { color: w, weight: 2, opacity: 0.9, fillColor: w, fillOpacity: 0.18 }; },
                onEachFeature: function(feature, layer) { layer.bindTooltip('Kec. ' + (feature.properties.NAMOBJ || 'Kecamatan'), { permanent: true, direction: 'center', className: 'kec-label' }); }
            }).addTo(batasWilayahLayer);
        }).catch(() => {})
    ]).then(function(){
        document.getElementById('petaLoading').classList.add('hide');
    });

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

        var marker = L.marker([item.latitude, item.longitude], { icon: customIcon });
        marker.nama = item.nama_masjid; marker.kecamatan = item.kecamatan; marker.kelurahan = item.kelurahan; marker.kategori = item.kategori; marker.latlngVal = [item.latitude, item.longitude];
        allMarkers.push(marker); clusterGroup.addLayer(marker);
        marker.bindTooltip(item.nama_masjid, { permanent: false, direction: 'top' });
        marker.on('click', function() { bukaDetail(item); });
    });

    document.getElementById("total-aset").innerText = total;
    document.getElementById("jml-sertipikat").innerText = bersertipikat;
    document.getElementById("jml-belum").innerText = belumSertipikat;

    var inputCari = document.getElementById('cari-masjid');
    var hasilCari = document.getElementById('hasil-cari');
    var saranCari = document.getElementById('saran-cari');
    var DEFAULT_VIEW = [-4.00165, 119.64347], DEFAULT_ZOOM = 13;

    var kategoriFilter = { 'Wakaf': true, 'Aset Pemerintah': true };
    function applyCategoryFilter() {
        clusterGroup.clearLayers();
        allMarkers.forEach(function(m) { if (kategoriFilter[m.kategori]) clusterGroup.addLayer(m); });
    }
    document.querySelectorAll('.layer-toggle').forEach(function(cb){
        cb.addEventListener('change', function() {
            kategoriFilter[this.value] = this.checked;
            applyCategoryFilter();
        });
    });

    function renderSuggestions(q) {
        saranCari.innerHTML = '';
        if (!q) { saranCari.classList.add('d-none'); return; }
        var cocok = dataMasjid.filter(function(item) {
            return (item.nama_masjid || '').toLowerCase().indexOf(q) !== -1 || (item.kelurahan || '').toLowerCase().indexOf(q) !== -1 || (item.kecamatan || '').toLowerCase().indexOf(q) !== -1;
        }).slice(0, 8);
        if (!cocok.length) { saranCari.classList.add('d-none'); return; }
        cocok.forEach(function(item) {
            var el = document.createElement('button');
            el.type = 'button';
            el.className = 'list-group-item list-group-item-action py-2 px-3 small text-start';
            el.innerHTML = '<div class="fw-semibold">' + (item.nama_masjid || '') + '</div><div class="text-muted" style="font-size:.75rem">' + (item.kelurahan || '') + ', ' + (item.kecamatan || '') + '</div>';
            el.onclick = function() { inputCari.value = item.nama_masjid; saranCari.classList.add('d-none'); filterMarkers(item.nama_masjid.toLowerCase()); };
            saranCari.appendChild(el);
        });
        saranCari.classList.remove('d-none');
    }

    function filterMarkers(q) {
        clusterGroup.clearLayers();
        var cocok = [];
        allMarkers.forEach(function(m) {
            var nama = (m.nama || '').toLowerCase(), kec = (m.kecamatan || '').toLowerCase(), kel = (m.kelurahan || '').toLowerCase();
            var show = kategoriFilter[m.kategori] && (!q || nama.indexOf(q) !== -1 || kec.indexOf(q) !== -1 || kel.indexOf(q) !== -1);
            if (show) { clusterGroup.addLayer(m); if (q) cocok.push(L.latLng(m.latlngVal[0], m.latlngVal[1])); }
        });
        if (q && cocok.length) { map.fitBounds(L.latLngBounds(cocok).pad(0.25)); hasilCari.classList.remove('d-none'); hasilCari.innerHTML = '<i class="fa-solid fa-circle-check text-success me-1"></i>' + cocok.length + ' aset ditemukan'; }
        else if (q) { hasilCari.classList.remove('d-none'); hasilCari.innerHTML = '<i class="fa-solid fa-circle-exclamation text-danger me-1"></i>Tidak ada aset yang cocok'; map.setView(DEFAULT_VIEW, DEFAULT_ZOOM); }
        else { hasilCari.classList.add('d-none'); map.setView(DEFAULT_VIEW, DEFAULT_ZOOM); }
    }

    inputCari.addEventListener('input', function() {
        var q = this.value.trim().toLowerCase();
        renderSuggestions(q);
        filterMarkers(q);
    });
    inputCari.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var q = inputCari.value.trim();
            if (!q) return;
            // Fallback geocoding ke Nominatim
            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(q + ', Parepare, Sulawesi Selatan, Indonesia')).then(r=>r.json()).then(function(res){
                if (res && res.length) {
                    var lat = parseFloat(res[0].lat), lon = parseFloat(res[0].lon);
                    map.setView([lat, lon], 16);
                    if (window.geoMarker) map.removeLayer(window.geoMarker);
                    window.geoMarker = L.circleMarker([lat, lon], { radius: 8, color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: .6 }).addTo(map).bindPopup('Hasil pencarian: ' + q).openPopup();
                    hasilCari.classList.remove('d-none'); hasilCari.innerHTML = '<i class="fa-solid fa-circle-check text-success me-1"></i> Lokasi ditemukan';
                }
            }).catch(function(){});
        }
    });
    inputCari.addEventListener('focus', function() { if (this.value.trim()) renderSuggestions(this.value.trim().toLowerCase()); });
    document.addEventListener('click', function(e) { if (!inputCari.contains(e.target) && !saranCari.contains(e.target)) saranCari.classList.add('d-none'); });

    // Locate me
    document.getElementById('btn-locate').addEventListener('click', function() {
        if (!navigator.geolocation) { alert('Browser tidak mendukung geolokasi'); return; }
        navigator.geolocation.getCurrentPosition(function(pos){
            var lat = pos.coords.latitude, lng = pos.coords.longitude;
            map.setView([lat, lng], 15);
            L.circleMarker([lat, lng], { radius: 8, color: '#10b981', fillColor: '#10b981', fillOpacity: .6 }).addTo(map).bindPopup('Lokasi Anda').openPopup();
        }, function(){
            alert('Tidak dapat mengakses lokasi.');
        });
    });
</script>
