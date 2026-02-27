<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AfyaLink — Connected Healthcare</title>

    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --teal:      #0d6e6e;
            --teal-mid:  #0f8080;
            --teal-lt:   #e6f4f4;
            --green:     #27ae60;
            --amber:     #e67e22;
            --cream:     #faf9f7;
            --ink:       #1a1f2e;
            --muted:     #5a6275;
            --border:    #dde4e4;
            --white:     #ffffff;
            --shadow-sm: 0 2px 8px rgba(13,110,110,.08);
            --shadow-md: 0 8px 32px rgba(13,110,110,.12);
            --shadow-lg: 0 20px 60px rgba(13,110,110,.16);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── NAV ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%;
            height: 72px;
            background: rgba(250,249,247,.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            animation: slideDown .6s ease both;
        }

        .nav-logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .nav-logo-mark {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--teal), var(--teal-mid));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-sm);
        }
        .nav-logo-mark svg { width: 20px; height: 20px; fill: white; }
        .nav-logo-text {
            font-family: 'DM Serif Display', serif;
            font-size: 1.35rem;
            color: var(--ink);
            letter-spacing: -.01em;
        }
        .nav-logo-text span { color: var(--teal); }

        .nav-links {
            display: flex; align-items: center; gap: 8px;
        }
        .nav-links a {
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            color: var(--muted);
            padding: 8px 16px;
            border-radius: 8px;
            transition: color .2s, background .2s;
        }
        .nav-links a:hover { color: var(--teal); background: var(--teal-lt); }
        .btn-nav {
            background: var(--teal) !important;
            color: white !important;
            padding: 8px 20px !important;
            box-shadow: var(--shadow-sm);
        }
        .btn-nav:hover { background: var(--teal-mid) !important; transform: translateY(-1px); }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center;
            padding: 120px 5% 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 60% 50% at 75% 40%, rgba(13,110,110,.07) 0%, transparent 70%),
                radial-gradient(ellipse 40% 60% at 15% 70%, rgba(39,174,96,.05) 0%, transparent 60%);
        }

        .hero-grid-bg {
            position: absolute; inset: 0; z-index: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 48px 48px;
            opacity: .4;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%);
        }

        .hero-inner {
            position: relative; z-index: 1;
            max-width: 1200px; margin: 0 auto; width: 100%;
            display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--teal-lt);
            border: 1px solid rgba(13,110,110,.2);
            border-radius: 100px;
            padding: 6px 14px 6px 8px;
            font-size: .75rem; font-weight: 600;
            color: var(--teal);
            letter-spacing: .04em; text-transform: uppercase;
            margin-bottom: 24px;
            animation: fadeUp .7s .1s both;
        }
        .hero-badge-dot {
            width: 8px; height: 8px;
            background: var(--green);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .hero-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2.6rem, 4.5vw, 4rem);
            line-height: 1.1;
            letter-spacing: -.02em;
            color: var(--ink);
            margin-bottom: 20px;
            animation: fadeUp .7s .2s both;
        }
        .hero-title em { font-style: italic; color: var(--teal); }

        .hero-subtitle {
            font-size: 1.05rem;
            line-height: 1.7;
            color: var(--muted);
            max-width: 480px;
            margin-bottom: 40px;
            animation: fadeUp .7s .3s both;
        }

        .hero-actions {
            display: flex; align-items: center; gap: 14px;
            flex-wrap: wrap;
            animation: fadeUp .7s .4s both;
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--teal);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600; font-size: .95rem;
            box-shadow: 0 4px 20px rgba(13,110,110,.3);
            transition: all .25s;
        }
        .btn-primary:hover { background: var(--teal-mid); transform: translateY(-2px); box-shadow: 0 8px 28px rgba(13,110,110,.35); }
        .btn-primary svg { width: 16px; height: 16px; }

        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent;
            color: var(--ink);
            padding: 14px 24px;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            text-decoration: none;
            font-weight: 500; font-size: .95rem;
            transition: all .25s;
        }
        .btn-secondary:hover { border-color: var(--teal); color: var(--teal); background: var(--teal-lt); }

        /* ── HERO VISUAL ── */
        .hero-visual {
            animation: fadeUp .8s .35s both;
            position: relative;
        }

        .hero-card-main {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 28px;
            position: relative;
            overflow: hidden;
        }
        .hero-card-main::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--teal), var(--green));
        }

        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card-title { font-family: 'DM Serif Display', serif; font-size: 1.1rem; color: var(--ink); }
        .card-badge { background: #bd8f10; color: var(--green); font-size: .72rem; font-weight: 700; padding: 4px 10px; border-radius: 100px; text-transform: uppercase; letter-spacing: .05em; }

        .stat-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 20px; }
        .stat-box {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
            text-align: center;
        }
        .stat-num { font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: var(--teal); display: block; }
        .stat-label { font-size: .72rem; color: var(--muted); font-weight: 500; }

        .activity-list { display: flex; flex-direction: column; gap: 10px; }
        .activity-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            background: var(--cream);
            border: 1px solid var(--border);
        }
        .activity-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .activity-icon.green { background: #e8f8ef; }
        .activity-icon.amber { background: #fef3e7; }
        .activity-icon.teal  { background: var(--teal-lt); }
        .activity-icon svg { width: 16px; height: 16px; }
        .activity-info { flex: 1; }
        .activity-info strong { display: block; font-size: .82rem; font-weight: 600; color: var(--ink); }
        .activity-info span { font-size: .75rem; color: var(--muted); }
        .activity-time { font-size: .72rem; color: var(--muted); white-space: nowrap; }

        .float-chip {
            position: absolute;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            padding: 10px 14px;
            display: flex; align-items: center; gap: 8px;
            font-size: .8rem; font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
            animation: float 4s ease-in-out infinite;
        }
        .float-chip-1 { top: -16px; right: -16px; animation-delay: 0s; }
        .float-chip-2 { bottom: 20px; left: -20px; animation-delay: 2s; }
        .float-chip .dot { width: 8px; height: 8px; border-radius: 50%; }
        .dot-green { background: var(--green); }
        .dot-amber { background: var(--amber); }

        /* ── FEATURES ── */
        .features {
            padding: 100px 5%;
            background: white;
            position: relative;
        }
        .features::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }

        .section-label {
            font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            color: var(--teal); margin-bottom: 12px;
        }
        .section-title {
            font-family: 'DM Serif Display', serif;
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            line-height: 1.15;
            color: var(--ink);
            max-width: 520px;
            margin-bottom: 16px;
        }
        .section-sub {
            font-size: 1rem; color: var(--muted); line-height: 1.7;
            max-width: 480px; margin-bottom: 60px;
        }

        .features-grid {
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
        }

        .feature-card {
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            background: var(--cream);
            transition: all .3s;
            position: relative;
            overflow: hidden;
        }
        .feature-card::after {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--teal), var(--green));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s;
        }
        .feature-card:hover { border-color: transparent; box-shadow: var(--shadow-md); transform: translateY(-4px); }
        .feature-card:hover::after { transform: scaleX(1); }

        .feature-icon {
            width: 48px; height: 48px;
            background: var(--teal-lt);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
        }
        .feature-icon svg { width: 24px; height: 24px; stroke: var(--teal); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .feature-title { font-weight: 700; font-size: 1rem; color: var(--ink); margin-bottom: 8px; }
        .feature-desc { font-size: .875rem; color: var(--muted); line-height: 1.65; }

        /* ── STATS STRIP ── */
        .stats-strip {
            background: linear-gradient(135deg, var(--teal) 0%, #0a5555 100%);
            padding: 70px 5%;
            position: relative; overflow: hidden;
        }
        .stats-strip::before {
            content: '';
            position: absolute; top: -60px; right: -60px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
        }
        .stats-grid {
            max-width: 1000px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; text-align: center;
        }
        .stats-item-num {
            font-family: 'DM Serif Display', serif;
            font-size: 2.8rem; color: white; display: block;
        }
        .stats-item-label { font-size: .875rem; color: rgba(255,255,255,.65); margin-top: 4px; }

        /* ── CTA ── */
        .cta-section {
            padding: 100px 5%;
            text-align: center;
            background: var(--cream);
        }
        .cta-box {
            max-width: 680px; margin: 0 auto;
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 24px;
            padding: 64px 48px;
            box-shadow: var(--shadow-md);
            position: relative; overflow: hidden;
        }
        .cta-box::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--teal), var(--green), var(--amber));
        }
        .cta-title {
            font-family: 'DM Serif Display', serif;
            font-size: 2.2rem; color: var(--ink);
            margin-bottom: 14px; line-height: 1.2;
        }
        .cta-sub { font-size: .95rem; color: var(--muted); margin-bottom: 36px; line-height: 1.65; }
        .cta-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

        /* ── FOOTER ── */
        footer {
            background: var(--ink);
            padding: 32px 5%;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
        }
        .footer-logo { font-family: 'DM Serif Display', serif; font-size: 1.1rem; color: white; }
        .footer-logo span { color: rgba(13,110,110,.8); }
        .footer-meta { font-size: .8rem; color: rgba(255,255,255,.4); }

        /* ── ANIMATIONS ── */
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: none; } }
        @keyframes fadeUp    { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }
        @keyframes float     { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes pulse     { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .6; transform: scale(.85); } }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; gap: 48px; }
            .hero-visual { display: none; }
            .features-grid { grid-template-columns: 1fr 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 580px) {
            .features-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .cta-box { padding: 40px 24px; }
            .nav-links .btn-nav-text { display: none; }
        }
    </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav>
    <a class="nav-logo" href="#">
        <div class="nav-logo-mark">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 13H9V9h2v6zm4 0h-2V9h2v6z"/><path d="M11 7h2v2h-2z" opacity=".5"/></svg>
        </div>
        <span class="nav-logo-text">Afya<span>Link</span></span>
    </a>

    <div class="nav-links">
}
                <a href="{{ route('dashboard') }}">Dashboard</a>
          
                <a href="{{ route('login') }}">Log in</a>
               
                    <a href="{{ route('register') }}" class="btn-nav"><span class="btn-nav-text">Get Started</span></a>
       
    </div>
</nav>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid-bg"></div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Kenya's Connected Health Platform
            </div>
            <h1 class="hero-title">
                Healthcare that <em>works</em><br>for everyone
            </h1>
            <p class="hero-subtitle">
                AfyaLink connects patients, clinicians, and facilities across Kenya — enabling seamless records, referrals, and care coordination in one secure platform.
            </p>
            <div class="hero-actions">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        Get Started Free
                    </a>
                @endif
                <a href="#features" class="btn-secondary">Explore Features</a>
            </div>
        </div>

        <!-- Hero Visual -->
        <div class="hero-visual">
            <div class="float-chip float-chip-1">
                <span class="dot dot-green"></span> 3 new referrals
            </div>
            <div class="float-chip float-chip-2">
                <span class="dot dot-amber"></span> Lab result ready
            </div>

            <div class="hero-card-main">
                <div class="card-header">
                    <span class="card-title">Today's Overview</span>
                    <span class="card-badge">Live</span>
                </div>

                <div class="stat-row">
                    <div class="stat-box">
                        <span class="stat-num">148</span>
                        <span class="stat-label">Patients Seen</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-num">23</span>
                        <span class="stat-label">Referrals</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-num">97%</span>
                        <span class="stat-label">Records Synced</span>
                    </div>
                </div>

                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div class="activity-info">
                            <strong>Patient #4821 registered</strong>
                            <span>Kenyatta National Hospital</span>
                        </div>
                        <span class="activity-time">2m ago</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#e67e22" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="activity-info">
                            <strong>Lab results uploaded</strong>
                            <span>Nairobi West Hospital</span>
                        </div>
                        <span class="activity-time">8m ago</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon teal">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#0d6e6e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <div class="activity-info">
                            <strong>Referral completed</strong>
                            <span>Mater Hospital &rarr; Aga Khan</span>
                        </div>
                        <span class="activity-time">15m ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── FEATURES ── -->
<section class="features" id="features">
    <div style="max-width:1200px; margin:0 auto;">
        <div class="section-label">Features</div>
        <h2 class="section-title">Everything you need to deliver better care</h2>
        <p class="section-sub">Built for the realities of Kenya's health ecosystem — from county hospitals to community health workers.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="feature-title">Unified Patient Records</div>
                <div class="feature-desc">Secure, longitudinal health records accessible across facilities — so every provider sees the full picture.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div class="feature-title">Referral Management</div>
                <div class="feature-desc">Streamline patient referrals between facilities with real-time status tracking and acknowledgements.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="feature-title">Lab &amp; Diagnostics</div>
                <div class="feature-desc">Request, track, and receive lab results digitally — reducing delays and paper-based errors.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="feature-title">Appointment Scheduling</div>
                <div class="feature-desc">Manage bookings, send automated reminders, and reduce no-shows with intelligent scheduling tools.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div class="feature-title">Facility Management</div>
                <div class="feature-desc">Oversee bed capacity, staff assignments, and resource utilisation across your entire facility network.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="feature-title">Data Privacy &amp; Security</div>
                <div class="feature-desc">End-to-end encryption, role-based access control, and full compliance with Kenya's Data Protection Act.</div>
            </div>
        </div>
    </div>
</section>

<!-- ── STATS STRIP ── -->
<div class="stats-strip">
    <div class="stats-grid">
        <div>
            <span class="stats-item-num">500+</span>
            <div class="stats-item-label">Facilities Connected</div>
        </div>
        <div>
            <span class="stats-item-num">1.2M</span>
            <div class="stats-item-label">Patient Records</div>
        </div>
        <div>
            <span class="stats-item-num">47</span>
            <div class="stats-item-label">Counties Covered</div>
        </div>
        <div>
            <span class="stats-item-num">99.9%</span>
            <div class="stats-item-label">System Uptime</div>
        </div>
    </div>
</div>

<!-- ── CTA ── -->
<section class="cta-section">
    <div class="cta-box">
        <h2 class="cta-title">Ready to transform your facility?</h2>
        <p class="cta-sub">Join hundreds of healthcare facilities already using AfyaLink to deliver faster, safer, and more connected care across Kenya.</p>
        <div class="cta-actions">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Create Free Account
                </a>
            @endif
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn-secondary">Sign In</a>
            @endif
        </div>
    </div>
</section>


<footer>
    <div class="footer-logo">Afya<span>Link</span></div>
    <div class="footer-meta">
        Laravel v{{ Illuminate\Foundation\Application::VERSION }} &nbsp;&middot;&nbsp; PHP v{{ PHP_VERSION }}
        &nbsp;&middot;&nbsp; &copy; {{ date('Y') }} AfyaLink. All rights reserved.
    </div>
</footer>

</body>
</html>