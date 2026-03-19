<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Professional Portal — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal: #0d6e6e; --teal-mid: #0f8080; --teal-lt: #e6f4f4;
            --ink: #1a1f2e; --muted: #5a6275; --border: #dde4e4;
            --blue: #2563eb; --blue-lt: #eff6ff;
            --green: #10b981; --green-lt: #ecfdf5;
            --purple: #8b5cf6; --purple-lt: #f5f3ff;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #0a5555 0%, #0d6e6e 100%);
            min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 24px;
        }
        .logo { display: flex; align-items: center; gap: 10px; justify-content: center; margin-bottom: 40px; }
        .logo-mark { width: 48px; height: 48px; background: linear-gradient(135deg, var(--teal), var(--teal-mid)); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .logo-mark svg { width: 24px; height: 24px; fill: white; }
        .logo-text { font-family: 'DM Serif Display', serif; font-size: 1.8rem; color: white; }
        .logo-text span { color: #10b981; }
        h1 { font-family: 'DM Serif Display', serif; font-size: 2rem; color: white; margin-bottom: 8px; text-align: center; }
        .subtitle { font-size: 1rem; color: rgba(255,255,255,0.7); text-align: center; margin-bottom: 40px; }

        .portals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 900px;
            width: 100%;
        }
        .portal-card {
            background: white; border-radius: 20px;
            padding: 32px 24px; text-align: center;
            box-shadow: 0 16px 48px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex; flex-direction: column; align-items: center;
        }
        .portal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 64px rgba(0,0,0,0.2);
        }
        .portal-icon {
            width: 72px; height: 72px; border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }
        .portal-icon svg { width: 32px; height: 32px; }

        .portal-card.doctor .portal-icon { background: var(--green-lt); }
        .portal-card.doctor .portal-icon svg { stroke: var(--green); fill: none; stroke-width: 2; }
        .portal-card.doctor:hover { border: 2px solid var(--green); }

        .portal-card.hospital .portal-icon { background: var(--purple-lt); }
        .portal-card.hospital .portal-icon svg { stroke: var(--purple); fill: none; stroke-width: 2; }
        .portal-card.hospital:hover { border: 2px solid var(--purple); }

        .portal-card.admin .portal-icon { background: var(--blue-lt); }
        .portal-card.admin .portal-icon svg { stroke: var(--blue); fill: none; stroke-width: 2; }
        .portal-card.admin:hover { border: 2px solid var(--blue); }

        .portal-title { font-weight: 700; font-size: 1.25rem; color: var(--ink); margin-bottom: 8px; }
        .portal-desc { font-size: 0.85rem; color: var(--muted); line-height: 1.5; }

        .back-link {
            margin-top: 40px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
            display: flex; align-items: center; gap: 6px;
            transition: color 0.2s;
        }
        .back-link:hover { color: white; }
        .back-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }

        @media(max-width: 768px) {
            .portals-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="logo">
        <div class="logo-mark">
            <svg viewBox="0 0 24 24"><path d="M12 21C12 21 3 15.5 3 9a5 5 0 0110 0 5 5 0 0110 0c0 6.5-9 12-11 12z"/><line x1="12" y1="6" x2="12" y2="14"/><line x1="8" y1="10" x2="16" y2="10"/></svg>
        </div>
        <span class="logo-text">Afya<span>Link</span></span>
    </div>

    <h1>Professional Portal</h1>
    <p class="subtitle">Select your portal to continue</p>

    <div class="portals-grid">
        <!-- Doctor Portal -->
        <a href="{{ route('doctor.login') }}" class="portal-card doctor">
            <div class="portal-icon">
                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="portal-title">Doctor Portal</div>
            <div class="portal-desc">Access patient records, create referrals & manage medical records</div>
        </a>

        <!-- Hospital Portal -->
        <a href="{{ route('hospital.login') }}" class="portal-card hospital">
            <div class="portal-icon">
                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="portal-title">Hospital Portal</div>
            <div class="portal-desc">Manage facility, handle referrals & coordinate with staff</div>
        </a>

        <!-- Admin Portal -->
        <a href="{{ route('admin.login') }}" class="portal-card admin">
            <div class="portal-icon">
                <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div class="portal-title">Admin Portal</div>
            <div class="portal-desc">Verify doctors, manage facilities & oversee system operations</div>
        </a>
    </div>

    <a href="{{ url('/') }}" class="back-link">
        <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Home
    </a>
</body>
</html>
