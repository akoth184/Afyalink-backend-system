<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #e07b1a; --orange-mid: #d4691a; --orange-lt: #fef3e6;
            --ink: #1a1f2e; --muted: #5a6275; --border: #dde4e4;
            --red: #e53e3e;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #c4610f 0%, #e07b1a 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
        }
        .card {
            background: white; border-radius: 20px;
            padding: 48px 44px; width: 100%; max-width: 460px;
            box-shadow: 0 24px 64px rgba(0,0,0,.2);
        }
        .logo { display: flex; align-items: center; gap: 10px; justify-content: center; margin-bottom: 32px; }
        .logo-mark { width: 40px; height: 40px; background: linear-gradient(135deg, var(--orange), var(--orange-mid)); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .logo-mark svg { width: 22px; height: 22px; fill: white; }
        .logo-text { font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: var(--ink); }
        h1 { font-family: 'DM Serif Display', serif; font-size: 1.6rem; color: var(--ink); margin-bottom: 6px; text-align: center; }
        .subtitle { font-size: .875rem; color: var(--muted); text-align: center; margin-bottom: 32px; }
        .field { margin-bottom: 16px; }
        label { display: block; font-size: .82rem; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-family: 'DM Sans', sans-serif; font-size: .9rem; color: var(--ink);
            transition: border-color .2s; outline: none; background: white;
        }
        input:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(224,123,26,.1); }
        .btn { width: 100%; padding: 13px; background: var(--orange); color: white; border: none; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: .95rem; font-weight: 600; cursor: pointer; transition: all .2s; margin-top: 8px; }
        .btn:hover { background: var(--orange-mid); transform: translateY(-1px); }
        .footer-link { text-align: center; font-size: .82rem; color: var(--muted); margin-top: 24px; }
        .footer-link a { color: var(--orange); font-weight: 600; text-decoration: none; }
        .field-error { font-size: .75rem; color: var(--red); margin-top: 4px; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: .875rem; }
        .alert-success { background: #e6f8ee; color: #22a85a; border: 1px solid #c5e8d3; }
        .alert-error { background: #fff5f5; color: #e53e3e; border: 1px solid #fecaca; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="logo-mark">
            <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
        </div>
        <span class="logo-text">AfyaLink</span>
    </div>

    <h1>Admin Portal</h1>
    <p class="subtitle">Sign in to access system administration</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="admin@afyalink.ke">
            @error('email') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
            @error('password') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn">Sign In</button>
    </form>

    <div class="footer-link">
        <a href="/">← Back to Home</a>
    </div>
</div>
</body>
</html>
