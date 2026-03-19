<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-50:  #f0f6ff;
            --blue-100: #dbeafe;
            --blue-400: #3b82f6;
            --blue-500: #2563eb;
            --blue-600: #1d4ed8;
            --accent:   #2563eb;
            --green-400: #34d399;
            --green-500: #10b981;
            --coral-400: #f43f5e;
            --bg:        #f4f7fb;
            --surface:   #ffffff;
            --border:    #e2e8f0;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --sidebar-w: 220px;
            --header-h:  64px;
            --radius-xl:  16px;
            --radius-lg:  12px;
            --radius-md:  8px;
            --shadow-card:0 2px 12px rgba(0,0,0,0.06);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text-primary);min-height:100vh}
        a{text-decoration:none;color:inherit}

        /* Header */
        .header{height:var(--header-h);background:var(--surface);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 24px;gap:16px;position:fixed;top:0;left:0;right:0;z-index:300}
        .logo{display:flex;align-items:center;gap:10px}
        .logo-mark{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--blue-500),#0ea5e9);display:flex;align-items:center;justify-content:center}
        .logo-mark svg{width:18px;height:18px;fill:none;stroke:white;stroke-width:2}
        .logo-text{font-family:'DM Serif Display',serif;font-size:1.35rem}
        .logo-text span{color:var(--accent)}
        .nav-back{margin-left:auto;display:flex;align-items:center;gap:8px;color:var(--text-secondary);font-size:.9rem}
        .nav-back:hover{color:var(--accent)}

        /* Main */
        .main{margin-left:var(--sidebar-w);padding:32px;max-width:800px;margin-top:var(--header-h)}
        .page-header{margin-bottom:32px}
        .page-title{font-family:'DM Serif Display',serif;font-size:2rem;margin-bottom:8px}
        .page-subtitle{color:var(--text-muted)}

        /* Cards */
        .card{background:var(--surface);border-radius:var(--radius-xl);box-shadow:var(--shadow-card);margin-bottom:24px;overflow:hidden}
        .card-header{padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .card-title{font-weight:600;font-size:1.1rem}
        .card-body{padding:24px}

        /* Forms */
        .form-group{margin-bottom:20px}
        .form-label{display:block;font-weight:500;margin-bottom:6px;font-size:.9rem}
        .form-input{width:100%;padding:12px 16px;border:1px solid var(--border);border-radius:var(--radius-md);font-size:.95rem;font-family:inherit;transition:border-color .2s}
        .form-input:focus{outline:none;border-color:var(--accent)}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:var(--radius-md);font-weight:600;cursor:pointer;border:none;transition:all .2s;font-size:.95rem}
        .btn-primary{background:var(--accent);color:#fff}
        .btn-primary:hover{background:var(--blue-600)}

        /* Alerts */
        .alert{padding:14px 18px;border-radius:var(--radius-md);margin-bottom:20px;font-size:.9rem}
        .alert-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
        .alert-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
        .error-text{color:#f43f5e;font-size:.85rem;margin-top:4px}

        /* Nav tabs */
        .settings-nav{display:flex;gap:8px;margin-bottom:24px}
        .settings-tab{padding:10px 20px;border-radius:var(--radius-md);font-weight:500;color:var(--text-secondary);background:var(--surface);border:1px solid var(--border)}
        .settings-tab.active{background:var(--accent);color:#fff;border-color:var(--accent)}
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('dashboard') }}" class="logo">
            <div class="logo-mark">
                <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div class="logo-text">Afya<span>Link</span></div>
        </a>
        <a href="{{ route('dashboard') }}" class="nav-back">
            ← Back to Dashboard
        </a>
    </header>

    <main class="main">
        <div class="page-header">
            <h1 class="page-title">Settings</h1>
            <p class="page-subtitle">Manage your account settings</p>
        </div>

        <div class="settings-nav">
            <a href="{{ route('profile') }}" class="settings-tab">Profile</a>
            <a href="{{ route('settings') }}" class="settings-tab active">Password</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <span class="card-title">Change Password</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-input" required>
                        @error('current_password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" required minlength="8">
                        @error('password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
