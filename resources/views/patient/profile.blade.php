<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile — AfyaLink</title>
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
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:var(--radius-md);font-weight:600;cursor:pointer;border:none;transition:all .2s;font-size:.95rem}
        .btn-primary{background:var(--accent);color:#fff}
        .btn-primary:hover{background:var(--blue-600)}

        /* Alerts */
        .alert{padding:14px 18px;border-radius:var(--radius-md);margin-bottom:20px;font-size:.9rem}
        .alert-success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
        .alert-error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}

        /* Profile info */
        .profile-avatar{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--blue-400),#0ea5e9);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:700;color:#fff;margin-bottom:16px}
        .profile-name{font-size:1.5rem;font-weight:600;margin-bottom:4px}
        .profile-email{color:var(--text-muted);margin-bottom:16px}
        .profile-id{font-size:.85rem;color:var(--text-secondary);background:var(--blue-50);padding:4px 12px;border-radius:100px;display:inline-block}
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
            <h1 class="page-title">My Profile</h1>
            <p class="page-subtitle">Manage your personal information</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <span class="card-title">Profile Information</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                    </div>

                    <div class="profile-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                    @if($user->patient_id)
                        <div class="profile-id">Patient ID: {{ $user->patient_id }}</div>
                    @endif

                    <hr style="border:none;border-top:1px solid var(--border);margin:24px 0">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-input" value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')
                                <span style="color:#f43f5e;font-size:.85rem">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-input" value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <span style="color:#f43f5e;font-size:.85rem">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span style="color:#f43f5e;font-size:.85rem">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone ?? '') }}" placeholder="e.g. 0712345678">
                        @error('phone')
                            <span style="color:#f43f5e;font-size:.85rem">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- THEME SETTINGS -->
        <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-top:16px;">
          <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:4px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Appearance</div>
          <div style="font-size:12px;color:#64748b;margin-bottom:16px;">Customize how AfyaLink looks for you</div>
          <div style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:8px;text-transform:uppercase;letter-spacing:.06em;">Theme</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div id="theme-light" onclick="setTheme('light')" style="border:2px solid #2563eb;border-radius:10px;padding:16px;cursor:pointer;background:#f0f6ff;">
              <div style="width:100%;height:50px;background:white;border-radius:6px;border:1px solid #e2e8f0;margin-bottom:8px;display:flex;flex-direction:column;gap:4px;padding:8px;">
                <div style="width:60%;height:6px;background:#e2e8f0;border-radius:3px;"></div>
                <div style="width:40%;height:6px;background:#e2e8f0;border-radius:3px;"></div>
              </div>
              <div style="font-size:13px;font-weight:700;color:#0f172a;">Light</div>
              <div style="font-size:11px;color:#64748b;margin-top:2px;">Bright interface with dark text</div>
            </div>
            <div id="theme-dark" onclick="setTheme('dark')" style="border:2px solid #e2e8f0;border-radius:10px;padding:16px;cursor:pointer;background:white;">
              <div style="width:100%;height:50px;background:#1e3a5f;border-radius:6px;margin-bottom:8px;display:flex;flex-direction:column;gap:4px;padding:8px;">
                <div style="width:60%;height:6px;background:rgba(255,255,255,.2);border-radius:3px;"></div>
                <div style="width:40%;height:6px;background:rgba(255,255,255,.2);border-radius:3px;"></div>
              </div>
              <div style="font-size:13px;font-weight:700;color:#0f172a;">Dark</div>
              <div style="font-size:11px;color:#64748b;margin-top:2px;">Dark interface, easier on eyes</div>
            </div>
          </div>
          <div id="theme-saved" style="display:none;margin-top:10px;font-size:12px;color:#16a34a;font-weight:600;">✓ Theme saved successfully</div>
        </div>
        <script>
        function setTheme(theme) {
          localStorage.setItem('afyalink-theme', theme);
          document.getElementById('theme-light').style.borderColor = theme === 'light' ? '#2563eb' : '#e2e8f0';
          document.getElementById('theme-light').style.background = theme === 'light' ? '#f0f6ff' : 'white';
          document.getElementById('theme-dark').style.borderColor = theme === 'dark' ? '#2563eb' : '#e2e8f0';
          document.getElementById('theme-dark').style.background = theme === 'dark' ? '#f0f6ff' : 'white';
          if(theme === 'dark') {
            document.body.style.background = '#0f172a';
            document.body.style.color = 'white';
          } else {
            document.body.style.background = '#f0f6ff';
            document.body.style.color = '#0f172a';
          }
          document.getElementById('theme-saved').style.display = 'block';
          setTimeout(() => document.getElementById('theme-saved').style.display = 'none', 2000);
        }
        window.addEventListener('DOMContentLoaded', function() {
          var saved = localStorage.getItem('afyalink-theme') || 'light';
          setTheme(saved);
        });
        </script>
    </main>
</body>
</html>
