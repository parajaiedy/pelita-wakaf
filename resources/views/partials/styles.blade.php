<!-- ============================================================
     PELITA WAKAF — Sistem Desain Terpadu (shared across all views)
     Satu sumber gaya untuk semua halaman agar tampilan konsisten.
     ============================================================ -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        /* Palet Merek — Hijau Teal (Wakaf) + Accent Emas (Pelita) */
        --brand-50:  #f0fdfa;
        --brand-100: #ccfbf1;
        --brand-200: #99f6e4;
        --brand-500: #14b8a6;
        --brand-600: #0d9488;
        --brand-700: #0f766e;
        --brand-900: #134e4a;

        --gold-400: #fbbf24;
        --gold-500: #f59e0b;

        --ink:        #0f172a;
        --ink-soft:   #1e293b;
        --muted:      #64748b;
        --surface:    #ffffff;
        --surface-2:  #f8fafc;
        --bg:         #f1f5f9;

        --radius-sm: 10px;
        --radius:    16px;
        --radius-lg: 22px;

        --shadow-sm: 0 1px 2px rgba(15, 23, 42, .06), 0 1px 3px rgba(15, 23, 42, .08);
        --shadow-md: 0 10px 30px -6px rgba(15, 23, 42, .12);
        --shadow-lg: 0 20px 50px -12px rgba(15, 23, 42, .22);

        --ring: 0 0 0 4px rgba(13, 148, 136, .18);
    }

    body {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--ink);
        background: var(--bg);
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }
    h1,h2,h3,h4,h5,h6 { font-weight: 700; letter-spacing: -0.01em; }

    /* ---------- Gradien & Teks Merek ---------- */
    .brand-gradient-text {
        background: linear-gradient(120deg, var(--brand-700), var(--brand-500));
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }
    .brand-gradient {
        background: linear-gradient(135deg, var(--brand-700) 0%, var(--brand-500) 100%);
    }

    /* ---------- Tombol ---------- */
    .btn { border-radius: 10px; font-weight: 600; transition: all .18s ease; }
    .btn:active { transform: translateY(0); }
    .btn-brand {
        background: linear-gradient(135deg, var(--brand-700), var(--brand-500));
        color: #fff; border: none;
        box-shadow: 0 6px 16px -4px rgba(13, 148, 136, .45);
    }
    .btn-brand:hover, .btn-brand:focus {
        color: #fff; filter: brightness(1.06);
        transform: translateY(-1px);
        box-shadow: 0 10px 22px -4px rgba(13, 148, 136, .5);
    }
    .btn-outline-brand { color: var(--brand-700); border: 1.5px solid var(--brand-600); background: transparent; }
    .btn-outline-brand:hover { background: var(--brand-600); color: #fff; }
    .btn-soft { background: var(--brand-100); color: var(--brand-900); border: none; }
    .btn-soft:hover { background: var(--brand-200); color: var(--brand-900); }

    /* ---------- Navbar ---------- */
    .navbar-custom {
        background: linear-gradient(120deg, #0f172a 0%, #134e4a 100%);
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,.06);
    }
    .navbar-custom .navbar-brand { font-weight: 800; letter-spacing: -0.02em; }
    .navbar-brand-mark {
        display: inline-flex; align-items: center; justify-content: center;
        width: 38px; height: 38px; border-radius: 11px;
        background: linear-gradient(135deg, var(--brand-500), var(--gold-400));
        color: #fff; font-size: 18px;
        box-shadow: 0 6px 14px -4px rgba(20,184,166,.5);
    }

    /* ---------- Kartu ---------- */
    .card {
        border: 1px solid rgba(15, 23, 42, .05);
        border-radius: var(--radius);
    }
    .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }

    .chart-card { border: none; border-radius: var(--radius); background: var(--surface); box-shadow: var(--shadow-sm); }

    /* Kartu Statistik (dashboard) */
    .card-stat {
        border: none; border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden; position: relative;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .card-stat::after {
        content: ''; position: absolute; right: -28px; top: -28px;
        width: 92px; height: 92px; border-radius: 50%;
        background: rgba(255,255,255,.10);
    }
    .card-stat:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
    .card-stat .stat-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 48px; height: 48px; border-radius: 13px;
        background: rgba(255,255,255,.18);
        backdrop-filter: blur(4px);
    }
    .card-stat .stat-label { font-size: .78rem; font-weight: 600; opacity: .82; text-transform: uppercase; letter-spacing: .03em; }
    .card-stat .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1.1; }

    /* ---------- Tabel ---------- */
    .table-modern { border-radius: var(--radius); overflow: hidden; }
    .table-modern thead th {
        background: var(--ink-soft); color: #fff;
        font-size: .8rem; font-weight: 600; text-transform: uppercase; letter-spacing: .03em;
        padding: 13px 15px; border-bottom: none; white-space: nowrap;
    }
    .table-modern tbody td { padding: 13px 15px; vertical-align: middle; white-space: nowrap; }
    .table-modern tbody tr { transition: background .15s ease; }
    .table-modern tbody tr:hover { background: var(--brand-50); }
    .table-modern tbody tr:nth-child(even) { background: var(--surface-2); }
    .table-modern tbody tr:nth-child(even):hover { background: var(--brand-50); }

    /* ---------- Filter & Pagination Admin ---------- */
    .filter-panel {
        padding: 16px; border-radius: 14px;
        background: var(--surface-2); border: 1px solid #e2e8f0;
    }
    .status-inline-select { min-width: 172px; border-radius: 999px; font-size: .76rem; font-weight: 600; padding-top: .25rem; padding-bottom: .25rem; }
    .pagination-pelita .pagination { margin-bottom: 0; gap: 4px; }
    .pagination-pelita .page-link {
        border: none; border-radius: 9px; color: var(--brand-700);
        min-width: 38px; text-align: center; box-shadow: var(--shadow-sm);
    }
    .pagination-pelita .page-item.active .page-link {
        background: linear-gradient(135deg, var(--brand-700), var(--brand-500));
        color: #fff;
    }
    .pagination-pelita .page-item.disabled .page-link { color: #94a3b8; background: #f8fafc; }

    /* ---------- Badge ---------- */
    .badge { font-weight: 600; border-radius: 999px; }
    .badge-soft-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-soft-danger  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .badge-soft-info    { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

    /* ---------- Form ---------- */
    .form-label { font-weight: 600; color: var(--ink-soft); font-size: .9rem; }
    .form-control, .form-select {
        border-radius: 10px; border: 1.5px solid #e2e8f0;
        padding: .62rem .9rem; font-size: .95rem;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-500); box-shadow: var(--ring);
    }
    .input-group-text { border-radius: 10px; background: var(--surface-2); border: 1.5px solid #e2e8f0; color: var(--muted); }
    .input-group .input-group-text { border-radius: 10px 0 0 10px; }
    .input-group .form-control { border-radius: 0 10px 10px 0; }

    /* ---------- Alert ---------- */
    .alert { border: none; border-radius: 12px; box-shadow: var(--shadow-sm); }

    /* ---------- Panel Peta (sidebar statistik) ---------- */
    .sidebar-panel {
        position: absolute; top: 20px; left: 20px; z-index: 1000;
        width: 330px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        background: rgba(255, 255, 255, .9);
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        border-radius: var(--radius-lg);
        padding: 22px;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(255, 255, 255, .5);
    }
    .sidebar-brand {
        display: flex; align-items: center; gap: 4px;
        font-weight: 800; font-size: 1.15rem; color: var(--ink);
    }
    .stat-card {
        background: var(--surface-2);
        border-radius: 13px; padding: 13px 15px; margin-bottom: 10px;
        border-left: 4px solid var(--brand-500);
        display: flex; align-items: center; justify-content: space-between;
        transition: transform .18s ease;
    }
    .stat-card:hover { transform: translateX(3px); }
    .stat-card.hijau { border-left-color: #16a34a; }
    .stat-card.merah { border-left-color: #dc2626; }
    .stat-card .stat-icon-wrap {
        width: 40px; height: 40px; border-radius: 11px;
        display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem;
        background: #fff; box-shadow: var(--shadow-sm);
    }
    .stat-card.hijau .stat-icon-wrap { color: #16a34a; }
    .stat-card.merah  .stat-icon-wrap { color: #dc2626; }
    .stat-card:not(.hijau):not(.merah) .stat-icon-wrap { color: var(--brand-600); }

    .btn-admin-floating {
        background: linear-gradient(135deg, var(--brand-700), var(--brand-500));
        color: #fff !important; font-weight: 700; letter-spacing: .01em;
        border-radius: 12px; padding: 12px; text-align: center; display: block;
        text-decoration: none; transition: all .2s ease;
        box-shadow: 0 8px 18px -6px rgba(13, 148, 136, .5);
    }
    .btn-admin-floating:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 24px -6px rgba(13, 148, 136, .55); }

    /* ---------- Pin & Popup Peta ---------- */
    .custom-pin { display: flex; justify-content: center; align-items: flex-end; background: transparent; border: none; }
    .leaflet-popup-content-wrapper {
        border-radius: 14px; padding: 4px;
        box-shadow: 0 16px 40px -8px rgba(15,23,42,.28);
    }
    .popup-title {
        font-size: 15px; font-weight: 800; color: var(--ink);
        margin-bottom: 8px; border-bottom: 2px solid #e2e8f0; padding-bottom: 6px;
    }
    .popup-info { font-size: 13px; color: #475569; margin-bottom: 5px; }
    .btn-route { font-size: 12px; padding: 7px 12px; border-radius: 8px; margin-top: 6px; display: inline-block; text-decoration: none; }
    .kec-label {
        background: rgba(255,255,255,.86); border: 1px solid rgba(15,23,42,.25);
        border-radius: 7px; padding: 3px 8px; font-size: 12px; font-weight: 700;
        box-shadow: var(--shadow-sm); text-align: center; color: var(--ink-soft);
        backdrop-filter: blur(3px);
    }

    /* ---------- Halaman Login ---------- */
    .login-shell {
        background:
            radial-gradient(1000px 500px at 10% -10%, rgba(20,184,166,.35), transparent 60%),
            radial-gradient(800px 500px at 110% 110%, rgba(245,158,11,.25), transparent 55%),
            linear-gradient(135deg, #0f172a 0%, #134e4a 100%);
        min-height: 100vh;
        padding: 3rem 1rem;
    }
    .login-card {
        border-radius: var(--radius-lg); border: none;
        box-shadow: 0 30px 70px -20px rgba(0,0,0,.5);
        overflow: hidden; background: #fff;
    }
    .login-header { padding: 1.8rem 2rem 1rem; text-align: center; }
    .login-header h3 { margin-bottom: .35rem; }
    .login-header p { margin-bottom: 0; }
    .login-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 58px; height: 58px; border-radius: 18px; font-size: 1.55rem; color: #fff;
        background: linear-gradient(135deg, var(--brand-500), var(--gold-400));
        box-shadow: 0 14px 30px -8px rgba(20,184,166,.55); margin-bottom: .9rem;
    }
    .btn-login {
        border-radius: 12px; padding: .8rem 1rem; font-weight: 700; font-size: 1.05rem;
        letter-spacing: .3px; border: none;
        background: linear-gradient(135deg, var(--brand-700), var(--brand-500));
        box-shadow: 0 12px 24px -8px rgba(13,148,136,.55);
        transition: all .22s ease;
    }
    .btn-login:hover { transform: translateY(-2px); box-shadow: 0 16px 30px -8px rgba(13,148,136,.6); }

    /* ---------- Peta Picker (form tambah/edit) ---------- */
    #map-picker {
        height: 300px; width: 100%; border-radius: 12px;
        border: 1.5px solid #e2e8f0; box-shadow: var(--shadow-sm);
    }
    .card-header-brand {
        background: linear-gradient(135deg, var(--brand-700), var(--brand-500));
        color: #fff; border-bottom: none;
    }

    /* ---------- Warna Khusus Jenis Hak ---------- */
    .bg-pink  { background-color: #db2777 !important; color: #fff; }
    .bg-brown { background-color: #8B4513 !important; color: #fff; }
    .bg-yellow{ background-color: #f59e0b !important; color: #212529; }

    /* ---------- Responsif ---------- */
    @media (max-width: 900px) {
        .sidebar-panel {
            top: auto; bottom: 14px; left: 14px; right: 14px;
            width: auto; max-height: 42vh; overflow-y: auto; padding: 16px;
        }
        .card-stat .stat-value { font-size: 1.4rem; }
    }
    @media (max-width: 576px) {
        .card-stat .stat-value { font-size: 1.25rem; }
        .card-stat .stat-icon { width: 40px; height: 40px; font-size: 1rem; }
    }
</style>