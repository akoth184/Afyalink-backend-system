<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal: #0d6e6e; --teal-mid: #0f8080; --teal-lt: #e6f4f4;
            --ink: #1a1f2e; --muted: #5a6275; --border: #dde4e4;
            --red: #e53e3e; --red-lt: #fff5f5; --cream: #faf9f7;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #0a5555 0%, #0d6e6e 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
        }
        .card {
            background: white; border-radius: 20px;
            padding: 48px 44px; width: 100%; max-width: 420px;
            box-shadow: 0 24px 64px rgba(0,0,0,.2);
        }
        .logo {
            display: flex; align-items: center; gap: 10px;
            justify-content: center; margin-bottom: 32px;
        }
        .logo-mark {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--teal), var(--teal-mid));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-mark svg { width: 22px; height: 22px; fill: white; }
        .logo-text { font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: var(--ink); }
        h1 { font-family: 'DM Serif Display', serif; font-size: 1.6rem; color: var(--ink); margin-bottom: 6px; text-align: center; }
        .subtitle { font-size: .875rem; color: var(--muted); text-align: center; margin-bottom: 32px; }
        .field { margin-bottom: 18px; }
        label { display: block; font-size: .82rem; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        input {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-family: 'DM Sans', sans-serif; font-size: .9rem; color: var(--ink);
            transition: border-color .2s;
            outline: none;
        }
        input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(13,110,110,.1); }
        .remember { display: flex; align-items: center; gap: 8px; font-size: .82rem; color: var(--muted); margin-bottom: 24px; }
        .remember input { width: auto; }
        .btn {
            width: 100%; padding: 13px;
            background: var(--teal); color: white;
            border: none; border-radius: 10px;
            font-family: 'DM Sans', sans-serif; font-size: .95rem; font-weight: 600;
            cursor: pointer; transition: all .2s;
        }
        .btn:hover { background: var(--teal-mid); transform: translateY(-1px); }
        .footer-link { text-align: center; font-size: .82rem; color: var(--muted); margin-top: 24px; }
        .footer-link a { color: var(--teal); font-weight: 600; text-decoration: none; }
        .error { background: var(--red-lt); border: 1px solid rgba(229,62,62,.25); color: #c53030; border-radius: 8px; padding: 10px 14px; font-size: .82rem; margin-bottom: 18px; }
        .field-error { font-size: .75rem; color: var(--red); margin-top: 4px; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="logo-mark">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
        </div>
        <span class="logo-text">AfyaLink</span>
    </div>

    <h1>Welcome back</h1>
    <p class="subtitle">Sign in to your AfyaLink account</p>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@facility.ke">
            @error('email') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">
            @error('password') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="remember">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="margin:0;font-weight:400">Remember me</label>
        </div>

        <button type="submit" class="btn">Sign In</button>
    </form>

    <div class="footer-link">
        Don't have an account? <a href="{{ route('register', ['role' => 'patient']) }}">Register</a>
    </div>
</div>
</body>
</html>
