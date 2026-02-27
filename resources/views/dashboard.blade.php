<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal:      #0d6e6e;
            --teal-mid:  #0f8080;
            --teal-lt:   #e6f4f4;
            --teal-dark: #0a5555;
            --green:     #27ae60;
            --green-lt:  #e8f8ef;
            --amber:     #e67e22;
            --amber-lt:  #fef3e7;
            --red:       #e53e3e;
            --red-lt:    #fff5f5;
            --blue:      #3182ce;
            --blue-lt:   #ebf8ff;
            --cream:     #faf9f7;
            --ink:       #1a1f2e;
            --muted:     #5a6275;
            --border:    #dde4e4;
            --sidebar-w: 260px;
            --header-h:  64px;
            --shadow-sm: 0 1px 4px rgba(0,0,0,.06);
            --shadow-md: 0 4px 16px rgba(0,0,0,.08);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f1f5f5;
            color: var(--ink);
            min-height: 100vh;
            display: flex;
        }

        /* ══════════════ SIDEBAR ══════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--teal-dark);
            min-height: 100vh;
            position: fixed; left: 0; top: 0; bottom: 0;
            display: flex; flex-direction: column;
            z-index: 200;
            transition: transform .3s;
        }

        .sidebar-logo {
            padding: 22px 24px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .sidebar-logo-mark {
            width: 36px; height: 36px;
            background: rgba(255,255,255,.15);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-logo-mark svg { width: 20px; height: 20px; fill: white; }
        .sidebar-logo-text {
            font-family: 'DM Serif Display', serif;
            font-size: 1.25rem; color: white;
        }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }

        .nav-section-label {
            font-size: .65rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: rgba(255,255,255,.35);
            padding: 12px 12px 6px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 9px;
            text-decoration: none;
            font-size: .875rem; font-weight: 500;
            color: rgba(255,255,255,.65);
            transition: all .2s;
            margin-bottom: 2px;
        }
        .nav-item:hover { background: rgba(255,255,255,.08); color: white; }
        .nav-item.active { background: rgba(255,255,255,.15); color: white; }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

        .nav-badge {
            margin-left: auto;
            background: var(--amber);
            color: white;
            font-size: .65rem; font-weight: 700;
            padding: 2px 7px; border-radius: 100px;
        }
        .nav-badge.green { background: var(--green); }

        .sidebar-user {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--teal-mid), var(--green));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; font-weight: 700; color: white; flex-shrink: 0;
        }
        .sidebar-user-info { flex: 1; overflow: hidden; }
        .sidebar-user-name { font-size: .82rem; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-role { font-size: .72rem; color: rgba(255,255,255,.45); }
        .sidebar-logout {
            width: 30px; height: 30px;
            border-radius: 7px;
            background: transparent;
            border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.4);
            transition: all .2s;
        }
        .sidebar-logout:hover { background: rgba(255,255,255,.1); color: white; }
        .sidebar-logout svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* ══════════════ OVERLAY (mobile) ══════════════ */
        /* FIX: Added overlay so tapping outside sidebar closes it on mobile */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.35);
            z-index: 199;
        }
        .sidebar.open ~ .sidebar-overlay { display: block; }

        /* ══════════════ MAIN ══════════════ */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── TOP BAR ── */
        .topbar {
            height: var(--header-h);
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 32px;
            gap: 16px;
            position: sticky; top: 0; z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .topbar-title { font-family: 'DM Serif Display', serif; font-size: 1.25rem; color: var(--ink); flex: 1; }
        .topbar-date { font-size: .82rem; color: var(--muted); }

        .topbar-actions { display: flex; align-items: center; gap: 10px; }

        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px;
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif;
            font-size: .85rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: all .2s; border: none;
        }
        .btn svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
        .btn-primary { background: var(--teal); color: white; box-shadow: 0 2px 8px rgba(13,110,110,.25); }
        .btn-primary:hover { background: var(--teal-mid); transform: translateY(-1px); }
        .btn-sm { padding: 6px 14px; font-size: .78rem; }

        /* ── PAGE CONTENT ── */
        .content { padding: 28px 32px; flex: 1; }

        /* ── ALERTS ── */
        .alert {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 10px;
            font-size: .875rem; margin-bottom: 20px;
        }
        .alert svg { width: 16px; height: 16px; flex-shrink: 0; }
        .alert-success { background: var(--green-lt); border: 1px solid rgba(39,174,96,.25); color: #276749; }
        .alert-error   { background: var(--red-lt);   border: 1px solid rgba(229,62,62,.25);  color: #c53030; }

        /* ── STATS GRID ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            position: relative; overflow: hidden;
            transition: box-shadow .2s, transform .2s;
        }
        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

        .stat-card-accent {
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .accent-teal   { background: linear-gradient(90deg, var(--teal), var(--teal-mid)); }
        .accent-green  { background: linear-gradient(90deg, var(--green), #2ecc71); }
        .accent-amber  { background: linear-gradient(90deg, var(--amber), #f39c12); }
        .accent-blue   { background: linear-gradient(90deg, var(--blue), #4299e1); }

        .stat-card-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
        .stat-card-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-card-icon svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .icon-teal  { background: var(--teal-lt); color: var(--teal); }
        .icon-green { background: var(--green-lt); color: var(--green); }
        .icon-amber { background: var(--amber-lt); color: var(--amber); }
        .icon-blue  { background: var(--blue-lt);  color: var(--blue); }

        .stat-card-delta {
            font-size: .72rem; font-weight: 600; padding: 3px 8px; border-radius: 100px;
        }
        .delta-up   { background: var(--green-lt); color: var(--green); }
        .delta-down { background: var(--red-lt);   color: var(--red); }

        .stat-card-num {
            font-family: 'DM Serif Display', serif;
            font-size: 2.1rem; color: var(--ink);
            line-height: 1; display: block; margin-bottom: 4px;
        }
        .stat-card-label { font-size: .82rem; color: var(--muted); font-weight: 500; }
        .stat-card-sub { font-size: .72rem; color: var(--muted); margin-top: 4px; }

        /* ── CONTENT GRID ── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }
        .content-grid.thirds {
            grid-template-columns: 2fr 1fr;
        }

        /* ── CARDS ── */
        .card {
            background: white;
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
        }
        .card-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1rem; color: var(--ink);
        }
        .card-body { padding: 0; }

        /* ── TABLE ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
            color: var(--muted);
            padding: 10px 22px;
            text-align: left;
            background: #f8fafa;
            border-bottom: 1px solid var(--border);
        }
        .data-table td {
            padding: 13px 22px;
            font-size: .85rem;
            border-bottom: 1px solid #f0f4f4;
            vertical-align: middle;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #f8fafa; }

        .td-name { font-weight: 600; color: var(--ink); }
        .td-sub  { font-size: .75rem; color: var(--muted); margin-top: 2px; }

        .avatar-sm {
            width: 30px; height: 30px;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .72rem; font-weight: 700; color: white;
            margin-right: 8px; flex-shrink: 0; vertical-align: middle;
        }

        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 100px;
            font-size: .72rem; font-weight: 700;
        }
        .badge-green  { background: var(--green-lt); color: #276749; }
        .badge-amber  { background: var(--amber-lt); color: #9c5a0a; }
        .badge-red    { background: var(--red-lt);   color: #c53030; }
        .badge-blue   { background: var(--blue-lt);  color: #1a5276; }
        .badge-gray   { background: #f0f2f5;         color: var(--muted); }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center; padding: 48px 24px;
        }
        .empty-state svg { width: 40px; height: 40px; stroke: var(--border); fill: none; stroke-width: 1.5; margin-bottom: 12px; }
        .empty-state p { font-size: .875rem; color: var(--muted); }

        /* ── QUICK ACTIONS ── */
        .quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 16px; }
        .quick-action {
            display: flex; align-items: center; gap: 10px;
            padding: 14px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            text-decoration: none; color: var(--ink);
            transition: all .2s; background: white;
        }
        .quick-action:hover { border-color: var(--teal); background: var(--teal-lt); color: var(--teal); transform: translateY(-1px); }
        .quick-action-icon {
            width: 34px; height: 34px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .quick-action-icon svg { width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .quick-action-text strong { display: block; font-size: .82rem; font-weight: 600; }
        .quick-action-text span   { font-size: .72rem; color: var(--muted); }
        .quick-action:hover .quick-action-text span { color: var(--teal); }

        /* ── ACTIVITY FEED ── */
        .activity-feed { padding: 8px 0; }
        .activity-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 12px 22px;
            border-bottom: 1px solid #f0f4f4;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-dot {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            margin-top: 2px;
        }
        .activity-dot svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .activity-body { flex: 1; }
        .activity-body strong { font-size: .83rem; font-weight: 600; color: var(--ink); }
        .activity-body p { font-size: .78rem; color: var(--muted); margin-top: 2px; }
        .activity-time { font-size: .72rem; color: var(--muted); white-space: nowrap; margin-top: 3px; }

        /* ── MOBILE TOGGLE ── */
        .mobile-toggle {
            display: none;
            position: fixed; top: 14px; left: 14px; z-index: 300;
            width: 38px; height: 38px;
            background: var(--teal);
            border: none; border-radius: 9px; cursor: pointer;
            align-items: center; justify-content: center;
        }
        .mobile-toggle svg { width: 20px; height: 20px; stroke: white; fill: none; stroke-width: 2.2; stroke-linecap: round; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .content-grid, .content-grid.thirds { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: none; }
            .main { margin-left: 0; }
            .mobile-toggle { display: flex; }
            .content { padding: 20px 16px; }
            .topbar { padding: 0 16px 0 56px; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- MOBILE TOGGLE -->
<button class="mobile-toggle" id="sidebarToggle">
    <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
</button>

<!-- ══════════ SIDEBAR ══════════ -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-mark">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
        </div>
        <span class="sidebar-logo-text">AfyaLink</span>
    </a>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>

        <div class="nav-section-label">Clinical</div>

        <a href="{{ route('patients.index') }}" class="nav-item {{ request()->routeIs('patients.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            Patients
            {{-- FIX: Wrapped in isset() to prevent "Undefined variable $stats" errors --}}
            @if(isset($stats) && ($stats['patients_today'] ?? 0) > 0)
                <span class="nav-badge green">{{ $stats['patients_today'] }}</span>
            @endif
        </a>

        <a href="{{ route('referrals.index') }}" class="nav-item {{ request()->routeIs('referrals.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Referrals
            @if(isset($stats) && ($stats['pending_referrals'] ?? 0) > 0)
                <span class="nav-badge">{{ $stats['pending_referrals'] }}</span>
            @endif
        </a>

        <a href="{{ route('records.index') }}" class="nav-item {{ request()->routeIs('records.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
            Medical Records
        </a>

        <div class="nav-section-label">Admin</div>

        <a href="{{ route('facilities.index') }}" class="nav-item {{ request()->routeIs('facilities.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Facilities
        </a>
    </nav>

    <!-- User info + logout -->
    <div class="sidebar-user">
        <div class="sidebar-avatar">
            {{-- FIX: Removed stray whitespace/newline between the two initials to prevent "J " avatar display --}}
            {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? '', 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? '', 0, 1)) }}
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->first_name ?? Auth::user()->name }} {{ Auth::user()->last_name ?? '' }}</div>
            {{-- FIX: Added null-safe fallback so str_replace doesn't crash when role is null --}}
            <div class="sidebar-user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'user')) }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout" title="Sign out">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
        </form>
    </div>
</aside>

{{-- FIX: Added mobile overlay element (closes sidebar on outside tap) --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ══════════ MAIN CONTENT ══════════ -->
<div class="main">

    <!-- TOP BAR -->
    <header class="topbar">
        <div class="topbar-title">Dashboard</div>
        <span class="topbar-date">{{ now()->format('l, d F Y') }}</span>
        <div class="topbar-actions">
            <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Patient
            </a>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="content">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- ── STAT CARDS ── -->
        {{-- FIX: All $stats accesses now use isset() + null-coalescing (?? 0) to prevent crashes --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-accent accent-teal"></div>
                <div class="stat-card-top">
                    <div class="stat-card-icon icon-teal">
                        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    @if(isset($stats) && ($stats['patients_today'] ?? 0) > 0)
                        <span class="stat-card-delta delta-up">+{{ $stats['patients_today'] }} today</span>
                    @endif
                </div>
                <span class="stat-card-num">{{ number_format($stats['total_patients'] ?? 0) }}</span>
                <div class="stat-card-label">Total Patients</div>
                <div class="stat-card-sub">{{ $stats['patients_today'] ?? 0 }} registered today</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-accent accent-amber"></div>
                <div class="stat-card-top">
                    <div class="stat-card-icon icon-amber">
                        <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    @if(isset($stats) && ($stats['pending_referrals'] ?? 0) > 0)
                        <span class="stat-card-delta delta-down">{{ $stats['pending_referrals'] }} pending</span>
                    @endif
                </div>
                <span class="stat-card-num">{{ number_format($stats['total_referrals'] ?? 0) }}</span>
                <div class="stat-card-label">Total Referrals</div>
                <div class="stat-card-sub">{{ $stats['active_referrals'] ?? 0 }} active referrals</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-accent accent-green"></div>
                <div class="stat-card-top">
                    <div class="stat-card-icon icon-green">
                        <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    @if(isset($stats) && ($stats['records_today'] ?? 0) > 0)
                        <span class="stat-card-delta delta-up">+{{ $stats['records_today'] }} today</span>
                    @endif
                </div>
                <span class="stat-card-num">{{ number_format($stats['total_records'] ?? 0) }}</span>
                <div class="stat-card-label">Medical Records</div>
                <div class="stat-card-sub">{{ $stats['records_today'] ?? 0 }} added today</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-accent accent-blue"></div>
                <div class="stat-card-top">
                    <div class="stat-card-icon icon-blue">
                        <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <span class="stat-card-num">{{ number_format($stats['total_facilities'] ?? 0) }}</span>
                <div class="stat-card-label">Facilities</div>
                <div class="stat-card-sub">Connected to network</div>
            </div>
        </div>

        <!-- ── RECENT PATIENTS + QUICK ACTIONS ── -->
        <div class="content-grid thirds">

            <!-- Recent Patients -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Recent Patients</span>
                    <a href="{{ route('patients.index') }}" class="btn btn-sm" style="background:var(--teal-lt);color:var(--teal);font-weight:600;">View all</a>
                </div>
                <div class="card-body">
                    {{-- FIX: Guard against $recent_patients being undefined --}}
                    @if(!isset($recent_patients) || $recent_patients->isEmpty())
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <p>No patients registered yet</p>
                        </div>
                    @else
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>ID</th>
                                    <th>Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_patients as $patient)
                                @php
                                    $colors = ['#0d6e6e','#27ae60','#3182ce','#e67e22','#8e44ad'];
                                    $color  = $colors[$loop->index % 5];
                                @endphp
                                <tr>
                                    <td>
                                        {{-- FIX 1: Corrected broken style attribute (was style="background":{{ ... }}) --}}
                                        {{-- FIX 2: Added null-coalescing fallback to avoid undefined property errors --}}
                                        <span class="avatar-sm" style="background: {{ $color }};">{{ strtoupper(substr($patient->first_name ?? $patient->name ?? 'P', 0, 1)) }}</span>
                                        <div style="display:inline-block;vertical-align:middle">
                                            <div class="td-name">{{ trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? $patient->name ?? '')) ?: 'Unknown' }}</div>
                                            <div class="td-sub">{{ optional($patient->facility)->name ?? '&mdash;' }}</div>
                                        </div>
                                    </td>
                                    <td style="color:var(--muted);font-size:.78rem">#{{ str_pad($patient->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td style="font-size:.78rem;color:var(--muted)">{{ $patient->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Quick Actions</span>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('patients.create') }}" class="quick-action">
                        <div class="quick-action-icon icon-teal">
                            <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        </div>
                        <div class="quick-action-text"><strong>New Patient</strong><span>Register patient</span></div>
                    </a>
                    <a href="{{ route('referrals.create') }}" class="quick-action">
                        <div class="quick-action-icon icon-amber">
                            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <div class="quick-action-text"><strong>New Referral</strong><span>Refer a patient</span></div>
                    </a>
                    <a href="{{ route('records.create') }}" class="quick-action">
                        <div class="quick-action-icon icon-green">
                            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                        </div>
                        <div class="quick-action-text"><strong>Add Record</strong><span>New medical record</span></div>
                    </a>
                    <a href="{{ route('facilities.create') }}" class="quick-action">
                        <div class="quick-action-icon icon-blue">
                            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div class="quick-action-text"><strong>Add Facility</strong><span>Register facility</span></div>
                    </a>
                </div>
            </div>
        </div>

        <!-- ── REFERRALS + ACTIVITY ── -->
        <div class="content-grid">

            <!-- Recent Referrals -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Recent Referrals</span>
                    <a href="{{ route('referrals.index') }}" class="btn btn-sm" style="background:var(--teal-lt);color:var(--teal);font-weight:600;">View all</a>
                </div>
                <div class="card-body">
                    {{-- FIX: Guard against $recent_referrals being undefined --}}
                    @if(!isset($recent_referrals) || $recent_referrals->isEmpty())
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            <p>No referrals yet</p>
                        </div>
                    @else
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>From &rarr; To</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_referrals as $ref)
                                <tr>
                                    <td class="td-name">
                                        {{ optional($ref->patient)->first_name ?? '&mdash;' }}
                                        {{ optional($ref->patient)->last_name ?? '' }}
                                    </td>
                                    <td style="font-size:.78rem;color:var(--muted)">
                                        {{ optional($ref->fromFacility)->name ?? '&mdash;' }}
                                        <br>&rarr; {{ optional($ref->toFacility)->name ?? '&mdash;' }}
                                    </td>
                                    <td>
                                        @php
                                            $s = $ref->status ?? 'pending';
                                            if ($s === 'completed')      $badgeClass = 'badge-green';
                                            elseif ($s === 'accepted')   $badgeClass = 'badge-blue';
                                            elseif ($s === 'rejected')   $badgeClass = 'badge-red';
                                            else                         $badgeClass = 'badge-amber';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($ref->status ?? 'pending') }}</span>
                                    </td>
                                    <td style="font-size:.75rem;color:var(--muted)">{{ $ref->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Recent Medical Records -->
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Recent Medical Records</span>
                    <a href="{{ route('records.index') }}" class="btn btn-sm" style="background:var(--teal-lt);color:var(--teal);font-weight:600;">View all</a>
                </div>
                <div class="card-body">
                    {{-- FIX: Guard against $recent_records being undefined --}}
                    @if(!isset($recent_records) || $recent_records->isEmpty())
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                            <p>No medical records yet</p>
                        </div>
                    @else
                        <div class="activity-feed">
                            @foreach($recent_records as $record)
                            <div class="activity-item">
                                <div class="activity-dot icon-green" style="background:var(--green-lt)">
                                    <svg viewBox="0 0 24 24" style="stroke:#27ae60"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                                <div class="activity-body">
                                    <strong>{{ $record->title ?? 'Medical Record #' . $record->id }}</strong>
                                    <p>Patient: {{ optional($record->patient)->first_name ?? '&mdash;' }} {{ optional($record->patient)->last_name ?? '' }}</p>
                                </div>
                                <div class="activity-time">{{ $record->created_at->diffForHumans() }}</div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </main>
</div>

<script>
    // Auto-dismiss alerts after 5s
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 5000);
    });

    // FIX: Replaced inline onclick with proper JS to handle both toggle button AND overlay close
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const toggle   = document.getElementById('sidebarToggle');

    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
    });
</script>

</body>
</html>