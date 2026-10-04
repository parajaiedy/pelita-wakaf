<!-- ============================================================
     PELITA ASET PAREPARE — Sistem Desain v2
     ============================================================ -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --brand-50: #ecfdf5; --brand-100: #d1fae5; --brand-200: #a7f3d0; --brand-400: #34d399;
        --brand-500: #10b981; --brand-600: #059669; --brand-700: #047857; --brand-900: #064e3b;
        --gold-400: #fbbf24; --gold-500: #f59e0b;
        --kategori-wakaf: #10b981;
        --kategori-aset:  #3b82f6;
        --ink: #0b1220; --ink-soft: #16213a; --muted: #64748b; --line: #e6ebf2;
        --surface: #ffffff; --surface-2: #f7f9fc; --bg: #eef2f7;
        --radius-sm: 10px; --radius: 16px; --radius-lg: 24px;
        --shadow-xs: 0 1px 2px rgba(11,18,32,.05);
        --shadow-sm: 0 1px 3px rgba(11,18,32,.07), 0 1px 2px rgba(11,18,32,.05);
        --shadow-md: 0 12px 32px -8px rgba(11,18,32,.16);
        --shadow-lg: 0 24px 60px -16px rgba(11,18,32,.28);
        --ring: 0 0 0 4px rgba(16,185,129,.18);
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--ink); background: var(--bg);
        -webkit-font-smoothing: antialiased; text-rendering: optimizeLegibility;
    }
    h1,h2,h3,h4,h5,h6 { font-weight: 700; letter-spacing: -0.022em; }
    a { text-decoration: none; }

    .brand-gradient-text {
        background: linear-gradient(115deg, var(--brand-700), var(--brand-400));
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }
    .brand-gradient { background: linear-gradient(135deg, var(--brand-700), var(--brand-500)); }
    .text-gradient-light {
        background: linear-gradient(115deg, #ffffff 20%, #a7f3d0 90%);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent;
    }

    .btn { border-radius: 11px; font-weight: 600; transition: all .18s ease; }
    .btn:active { transform: translateY(0) scale(.99); }
    .btn-brand {
        background: linear-gradient(135deg, var(--brand-700), var(--brand-500));
        color: #fff; border: none; box-shadow: 0 8px 20px -6px rgba(5,150,105,.5);
    }
    .btn-brand:hover, .btn-brand:focus { color: #fff; filter: brightness(1.07); transform: translateY(-1px); box-shadow: 0 12px 26px -6px rgba(5,150,105,.55); }
    .btn-outline-brand { color: var(--brand-700); border: 1.5px solid var(--brand-500); background: transparent; }
    .btn-outline-brand:hover { background: var(--brand-600); border-color: var(--brand-600); color: #fff; }
    .btn-soft { background: var(--brand-100); color: var(--brand-900); border: none; }
    .btn-soft:hover { background: var(--brand-200); color: var(--brand-900); }

    .navbar-custom {
        background: rgba(11,18,32,.82); backdrop-filter: saturate(180%) blur(14px);
        -webkit-backdrop-filter: saturate(180%) blur(14px);
        color: #fff; border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .navbar-custom .navbar-brand { font-weight: 800; letter-spacing: -.03em; color: #fff; }
    .navbar-brand-mark {
        display: inline-flex; align-items: center; justify-content: center;
        width: 38px; height: 38px; border-radius: 12px;
        background: linear-gradient(135deg, var(--brand-500), var(--gold-400));
        color: #fff; font-size: 18px; box-shadow: 0 8px 18px -6px rgba(16,185,129,.6);
    }
    .navbar-custom .nav-link { color: rgba(255,255,255,.75); font-weight: 600; font-size: .92rem; border-radius: 9px; padding: .45rem .85rem !important; transition: all .16s ease; }
    .navbar-custom .nav-link:hover { color: #fff; background: rgba(255,255,255,.08); }
    .navbar-custom .nav-link.active { color: #fff; background: rgba(255,255,255,.13); }

    .card { border: 1px solid var(--line); border-radius: var(--radius); background: var(--surface); }
    .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .chart-card { border: none; border-radius: var(--radius); background: var(--surface); box-shadow: var(--shadow-sm); }

    .card-stat { border: none; border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; position: relative; transition: transform .25s ease, box-shadow .25s ease; }
    .card-stat::after { content: ''; position: absolute; right: -28px; top: -28px; width: 96px; height: 96px; border-radius: 50%; background: rgba(255,255,255,.11); }
    .card-stat:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
    .card-stat .stat-icon { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 14px; background: rgba(255,255,255,.2); backdrop-filter: blur(4px); }
    .card-stat .stat-label { font-size: .76rem; font-weight: 600; opacity: .85; text-transform: uppercase; letter-spacing: .05em; }
    .card-stat .stat-value { font-size: 1.8rem; font-weight: 800; line-height: 1.05; }

    .kpi-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 18px 20px; height: 100%; box-shadow: var(--shadow-xs); transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); border-color: #d7e0ec; }
    .kpi-card .kpi-icon { width: 42px; height: 42px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.05rem; }
    .kpi-card .kpi-value { font-size: 1.7rem; font-weight: 800; line-height: 1.1; letter-spacing: -.03em; }
    .kpi-card .kpi-label { font-size: .8rem; color: var(--muted); font-weight: 600; }

    .table-modern { border-radius: var(--radius); overflow: hidden; }
    .table-modern thead th { background: var(--ink-soft); color: #fff; font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; padding: 14px 15px; border-bottom: none; white-space: nowrap; }
    .table-modern tbody td { padding: 13px 15px; vertical-align: middle; white-space: nowrap; border-color: var(--line); }
    .table-modern tbody tr { transition: background .15s ease; }
    .table-modern tbody tr:hover { background: var(--brand-50); }

    .filter-panel { padding: 18px; border-radius: var(--radius); background: var(--surface-2); border: 1px solid var(--line); }
    .status-inline-select { min-width: 172px; border-radius: 999px; font-size: .76rem; font-weight: 600; padding-top: .25rem; padding-bottom: .25rem; }

    .segmented { display: inline-flex; gap: 4px; padding: 4px; background: var(--surface-2); border: 1px solid var(--line); border-radius: 999px; }
    .segmented a, .segmented button { display: inline-flex; align-items: center; gap: 7px; padding: .5rem 1.05rem; border-radius: 999px; font-size: .88rem; font-weight: 700; color: var(--muted); transition: all .18s ease; border: none; background: transparent; }
    .segmented a:hover { color: var(--ink); }
    .segmented a.active, .segmented button.active { background: var(--surface); color: var(--ink); box-shadow: var(--shadow-sm); }
    .segmented a.active.tone-wakaf { color: var(--kategori-wakaf); }
    .segmented a.active.tone-aset  { color: var(--kategori-aset); }

    .badge { font-weight: 600; border-radius: 999px; }
    .badge-soft-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-soft-danger  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .badge-soft-info    { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-soft-warn    { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .badge-soft-wakaf   { background: #d1fae5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-soft-aset    { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }

    .form-label { font-weight: 600; color: var(--ink-soft); font-size: .9rem; }
    .form-control, .form-select { border-radius: 11px; border: 1.5px solid var(--line); padding: .62rem .9rem; font-size: .95rem; transition: border-color .15s ease, box-shadow .15s ease; }
    .form-control:focus, .form-select:focus { border-color: var(--brand-500); box-shadow: var(--ring); }
    .input-group-text { border-radius: 11px; background: var(--surface-2); border: 1.5px solid var(--line); color: var(--muted); }
    .input-group .input-group-text { border-radius: 11px 0 0 11px; }
    .input-group .form-control { border-radius: 0 11px 11px 0; }

    .alert { border: none; border-radius: 12px; box-shadow: var(--shadow-sm); }

    .sidebar-panel { position: absolute; top: 18px; left: 18px; z-index: 1000; width: 348px; max-height: calc(100vh - 36px); overflow-y: auto; background: rgba(255,255,255,.92); backdrop-filter: blur(18px) saturate(180%); -webkit-backdrop-filter: blur(18px) saturate(180%); border-radius: var(--radius-lg); padding: 20px; box-shadow: var(--shadow-lg); border: 1px solid rgba(255,255,255,.6); scrollbar-width: thin; }
    .sidebar-panel::-webkit-scrollbar { width: 6px; }
    .sidebar-panel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
    .sidebar-brand { display: flex; align-items: center; gap: 4px; font-weight: 800; font-size: 1.08rem; color: var(--ink); letter-spacing: -.03em; }
    .sidebar-kicker { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; color: var(--muted); }

    .stat-card { background: var(--surface-2); border-radius: 14px; padding: 13px 15px; margin-bottom: 10px; border-left: 4px solid var(--brand-500); display: flex; align-items: center; justify-content: space-between; transition: transform .18s ease; }
    .stat-card:hover { transform: translateX(3px); }
    .stat-card.hijau { border-left-color: #16a34a; }
    .stat-card.merah { border-left-color: #dc2626; }
    .stat-card .stat-icon-wrap { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; background: #fff; box-shadow: var(--shadow-xs); }
    .stat-card.hijau .stat-icon-wrap { color: #16a34a; }
    .stat-card.merah  .stat-icon-wrap { color: #dc2626; }
    .stat-card:not(.hijau):not(.merah) .stat-icon-wrap { color: var(--brand-600); }

    .btn-admin-floating { background: linear-gradient(135deg, var(--ink-soft), var(--ink)); color: #fff !important; font-weight: 700; letter-spacing: .01em; border-radius: 12px; padding: 12px; text-align: center; display: block; transition: all .2s ease; box-shadow: 0 10px 22px -8px rgba(11,18,32,.6); }
    .btn-admin-floating:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 14px 28px -8px rgba(11,18,32,.65); }

    .custom-pin { display: flex; justify-content: center; align-items: flex-end; background: transparent; border: none; }
    .leaflet-popup-content-wrapper { border-radius: 16px; padding: 4px; box-shadow: 0 18px 44px -10px rgba(11,18,32,.34); }
    .leaflet-popup-content { margin: 14px 16px; }
    .popup-title { font-size: 15px; font-weight: 800; color: var(--ink); margin-bottom: 9px; border-bottom: 1.5px solid var(--line); padding-bottom: 8px; letter-spacing: -.02em; }
    .popup-info { font-size: 13px; color: #475569; margin-bottom: 5px; }
    .btn-route { font-size: 12px; padding: 8px 12px; border-radius: 9px; margin-top: 7px; display: inline-block; text-decoration: none; font-weight: 600; }
    .kec-label { background: rgba(255,255,255,.88); border: 1px solid rgba(11,18,32,.2); border-radius: 8px; padding: 3px 9px; font-size: 11.5px; font-weight: 700; box-shadow: var(--shadow-xs); text-align: center; color: var(--ink-soft); backdrop-filter: blur(3px); }

    .login-shell { background: radial-gradient(1100px 560px at 8% -12%, rgba(16,185,129,.32), transparent 58%), radial-gradient(900px 520px at 112% 112%, rgba(245,158,11,.22), transparent 55%), linear-gradient(150deg, #070c17 0%, #0b1220 45%, #064e3b 130%); min-height: 100vh; padding: 3rem 1rem; }
    .login-card { border-radius: var(--radius-lg); border: none; box-shadow: 0 40px 90px -30px rgba(0,0,0,.65); overflow: hidden; background: #fff; }
    .login-header { padding: 1.9rem 2rem 1rem; text-align: center; }
    .login-header h3 { margin-bottom: .35rem; letter-spacing: -.03em; }
    .login-header p { margin-bottom: 0; }
    .login-icon { display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 19px; font-size: 1.6rem; color: #fff; background: linear-gradient(135deg, var(--brand-500), var(--gold-400)); box-shadow: 0 16px 34px -10px rgba(16,185,129,.6); margin-bottom: .9rem; }
    .btn-login { border-radius: 13px; padding: .82rem 1rem; font-weight: 700; font-size: 1.04rem; letter-spacing: .3px; border: none; background: linear-gradient(135deg, var(--brand-700), var(--brand-500)); color: #fff; box-shadow: 0 14px 28px -10px rgba(5,150,105,.6); transition: all .22s ease; }
    .btn-login:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 18px 34px -10px rgba(5,150,105,.65); }

    #map-picker { height: 320px; width: 100%; border-radius: 14px; border: 1.5px solid var(--line); box-shadow: var(--shadow-xs); }
    .card-header-brand { background: linear-gradient(135deg, var(--brand-700), var(--brand-500)); color: #fff; border-bottom: none; }

    .bg-pink  { background-color: #db2777 !important; color: #fff; }
    .bg-brown { background-color: #8B4513 !important; color: #fff; }
    .bg-yellow{ background-color: #f59e0b !important; color: #212529; }

    .hero { position: relative; overflow: hidden; background: radial-gradient(900px 460px at 12% -10%, rgba(16,185,129,.30), transparent 55%), radial-gradient(760px 420px at 96% 8%, rgba(59,130,246,.22), transparent 55%), linear-gradient(150deg, #070c17 0%, #0b1220 55%, #062e22 140%); color: #fff; padding: 4.5rem 0 5rem; }
    .hero::after { content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.055) 1px, transparent 1px); background-size: 26px 26px; mask-image: linear-gradient(to bottom, #000 0%, transparent 78%); -webkit-mask-image: linear-gradient(to bottom, #000 0%, transparent 78%); pointer-events: none; }
    .hero-badge { display: inline-flex; align-items: center; gap: 8px; padding: .42rem .95rem; border-radius: 999px; background: rgba(16,185,129,.14); border: 1px solid rgba(16,185,129,.35); color: #6ee7b7; font-size: .78rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .hero h1 { font-size: clamp(2rem, 4.6vw, 3.4rem); font-weight: 800; letter-spacing: -.035em; line-height: 1.06; }
    .hero p.lead { color: rgba(255,255,255,.72); font-size: 1.06rem; max-width: 46rem; }

    .portal-card { position: relative; overflow: hidden; border-radius: var(--radius-lg); border: 1px solid var(--line); background: var(--surface); padding: 26px; height: 100%; box-shadow: var(--shadow-sm); transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease; display: flex; flex-direction: column; }
    .portal-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: transparent; }
    .portal-card .portal-top { position: absolute; top: 0; left: 0; right: 0; height: 5px; }
    .portal-card.wakaf .portal-top { background: linear-gradient(90deg, var(--kategori-wakaf), #34d399); }
    .portal-card.aset  .portal-top { background: linear-gradient(90deg, var(--kategori-aset), #60a5fa); }
    .portal-icon { width: 58px; height: 58px; border-radius: 17px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.45rem; color: #fff; margin-bottom: 1rem; }
    .portal-card.wakaf .portal-icon { background: linear-gradient(135deg, #059669, #34d399); box-shadow: 0 14px 30px -12px rgba(16,185,129,.75); }
    .portal-card.aset  .portal-icon { background: linear-gradient(135deg, #2563eb, #60a5fa); box-shadow: 0 14px 30px -12px rgba(59,130,246,.75); }
    .portal-card h3 { font-size: 1.22rem; font-weight: 800; margin-bottom: .3rem; letter-spacing: -.03em; }
    .portal-card .portal-count { font-size: 2.4rem; font-weight: 800; line-height: 1; letter-spacing: -.04em; }
    .portal-card.wakaf .portal-count { color: var(--kategori-wakaf); }
    .portal-card.aset  .portal-count { color: var(--kategori-aset); }
    .portal-meter { height: 7px; border-radius: 999px; background: var(--surface-2); overflow: hidden; }
    .portal-meter > span { display: block; height: 100%; border-radius: 999px; }

    .section-title { font-size: 1.5rem; font-weight: 800; letter-spacing: -.03em; }
    .feature-tile { background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 22px; height: 100%; box-shadow: var(--shadow-xs); transition: transform .2s ease, box-shadow .2s ease; }
    .feature-tile:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .feature-tile .ft-icon { width: 46px; height: 46px; border-radius: 13px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; background: var(--brand-50); color: var(--brand-700); margin-bottom: .85rem; }
    .site-footer { background: #070c17; color: rgba(255,255,255,.62); padding: 2.6rem 0 1.6rem; font-size: .9rem; }
    .site-footer a { color: rgba(255,255,255,.72); }
    .site-footer a:hover { color: #fff; }

    .bottom-nav {
        display: none; position: fixed; bottom: 0; left: 0; right: 0;
        background: rgba(255,255,255,.96); backdrop-filter: blur(18px) saturate(180%);
        -webkit-backdrop-filter: blur(18px) saturate(180%);
        border-top: 1px solid var(--line); padding: 8px 0 calc(8px + env(safe-area-inset-bottom));
        box-shadow: 0 -8px 24px rgba(11,18,32,.14); z-index: 2000;
    }
    .bottom-nav .bn-item {
        flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 3px; color: var(--muted); font-size: .68rem; font-weight: 700;
        text-decoration: none; padding: 5px 2px; border-radius: 12px; transition: all .15s ease;
    }
    .bottom-nav .bn-item.active { color: var(--brand-700); background: var(--brand-50); }
    .bottom-nav .bn-item i { font-size: 1.25rem; }

    @media (max-width: 900px) {
        .sidebar-panel { top: auto; bottom: 78px; left: 14px; right: 14px; width: auto; max-height: 42vh; overflow-y: auto; padding: 16px; }
        .card-stat .stat-value { font-size: 1.45rem; }
        .hero { padding: 3rem 0 3.4rem; }
        .bottom-nav { display: flex; }
    }
    @media (max-width: 576px) {
        .card-stat .stat-value { font-size: 1.28rem; }
        .card-stat .stat-icon { width: 40px; height: 40px; font-size: 1rem; }
        .kpi-card .kpi-value { font-size: 1.4rem; }
    }
    /* ============================================================
       DARK MODE — diaktifkan lewat <html data-theme="dark">
       ============================================================ */
    [data-theme="dark"] {
        --brand-50:  #052e26;
        --brand-100: #064e3b;
        --brand-200: #065f46;
        --ink:        #e8eef7;
        --ink-soft:   #cbd5e1;
        --muted:      #94a3b8;
        --line:       #253248;
        --surface:    #131c2e;
        --surface-2:  #1a2438;
        --bg:         #0a1120;
        --shadow-xs: 0 1px 2px rgba(0,0,0,.4);
        --shadow-sm: 0 1px 3px rgba(0,0,0,.45), 0 1px 2px rgba(0,0,0,.4);
        --shadow-md: 0 12px 32px -8px rgba(0,0,0,.6);
        --shadow-lg: 0 24px 60px -16px rgba(0,0,0,.7);
        --ring: 0 0 0 4px rgba(16,185,129,.28);
    }
    [data-theme="dark"] body { background: var(--bg); color: var(--ink); }
    [data-theme="dark"] .card,
    [data-theme="dark"] .chart-card,
    [data-theme="dark"] .kpi-card,
    [data-theme="dark"] .feature-tile,
    [data-theme="dark"] .portal-card,
    [data-theme="dark"] .mini-card,
    [data-theme="dark"] .login-card { background: var(--surface); border-color: var(--line); }
    [data-theme="dark"] .navbar-custom { background: rgba(8,14,26,.86); }
    [data-theme="dark"] .sidebar-panel { background: rgba(19,28,46,.94); border-color: rgba(255,255,255,.08); }
    [data-theme="dark"] .stat-card { background: var(--surface-2); }
    [data-theme="dark"] .stat-card .stat-icon-wrap { background: var(--surface); }
    [data-theme="dark"] .filter-panel { background: var(--surface-2); border-color: var(--line); }
    [data-theme="dark"] .segmented { background: var(--surface-2); border-color: var(--line); }
    [data-theme="dark"] .segmented a.active, [data-theme="dark"] .segmented button.active { background: var(--surface); }
    [data-theme="dark"] .table-modern thead th { background: #0a1120; }
    [data-theme="dark"] .table-modern tbody td { border-color: var(--line); color: var(--ink); }
    [data-theme="dark"] .table-modern tbody tr:hover { background: rgba(16,185,129,.08); }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background: var(--surface-2); border-color: var(--line); color: var(--ink); }
    [data-theme="dark"] .input-group-text { background: var(--surface-2); border-color: var(--line); color: var(--muted); }
    [data-theme="dark"] .detail-panel { background: var(--surface); border-color: var(--line); }
    [data-theme="dark"] .detail-body { background: var(--surface); }
    [data-theme="dark"] .detail-row .dr-icon { background: var(--surface-2); color: var(--brand-400); }
    [data-theme="dark"] .detail-row { border-color: var(--line); }
    [data-theme="dark"] .bottom-nav { background: rgba(19,28,46,.96); border-color: var(--line); }
    [data-theme="dark"] .bottom-nav .bn-item.active { background: rgba(16,185,129,.14); color: var(--brand-400); }
    [data-theme="dark"] .bg-white { background: var(--surface) !important; }
    [data-theme="dark"] .text-dark { color: var(--ink) !important; }
    [data-theme="dark"] .border { border-color: var(--line) !important; }
    [data-theme="dark"] .badge.bg-light { background: var(--surface-2) !important; color: var(--ink) !important; }
    [data-theme="dark"] .peta-loading { background: rgba(10,17,32,.94); }
    [data-theme="dark"] .peta-loading .fw-bold { color: var(--ink) !important; }
    [data-theme="dark"] .leaflet-popup-content-wrapper,
    [data-theme="dark"] .leaflet-popup-tip { background: var(--surface); color: var(--ink); }
    [data-theme="dark"] .leaflet-bar a { background: var(--surface); color: var(--ink); border-color: var(--line); }
    [data-theme="dark"] .leaflet-control-layers { background: var(--surface); color: var(--ink); }
    [data-theme="dark"] .list-group-item { background: var(--surface); color: var(--ink); border-color: var(--line); }
    [data-theme="dark"] .list-group-item-action:hover { background: var(--surface-2); }
    [data-theme="dark"] .site-footer { background: #05090f; }

    /* Tombol ganti tema */
    .theme-toggle {
        width: 38px; height: 38px; border-radius: 11px; border: 1px solid rgba(255,255,255,.18);
        background: rgba(255,255,255,.08); color: #fff; font-size: .95rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all .18s ease; flex-shrink: 0;
    }
    .theme-toggle:hover { background: rgba(255,255,255,.18); transform: translateY(-1px); }
    [data-theme="dark"] .theme-toggle { border-color: var(--line); background: var(--surface-2); color: var(--gold-400); }

</style>
