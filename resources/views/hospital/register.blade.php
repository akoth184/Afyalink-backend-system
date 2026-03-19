<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Hospital — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal: #0d6e6e; --teal-mid: #0f8080; --teal-lt: #e6f4f4;
            --ink: #1a1f2e; --muted: #5a6275; --border: #dde4e4;
            --red: #e53e3e;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #0a5555 0%, #0d6e6e 100%);
            min-height: 100vh;
            padding: 24px;
        }
        .card {
            background: white; border-radius: 20px;
            padding: 48px 44px; width: 100%; max-width: 540px;
            margin: 0 auto;
            box-shadow: 0 24px 64px rgba(0,0,0,.2);
        }
        .logo { display: flex; align-items: center; gap: 10px; justify-content: center; margin-bottom: 32px; }
        .logo-mark { width: 40px; height: 40px; background: linear-gradient(135deg, var(--teal), var(--teal-mid)); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .logo-mark svg { width: 22px; height: 22px; fill: white; }
        .logo-text { font-family: 'DM Serif Display', serif; font-size: 1.5rem; color: var(--ink); }
        h1 { font-family: 'DM Serif Display', serif; font-size: 1.6rem; color: var(--ink); margin-bottom: 6px; text-align: center; }
        .subtitle { font-size: .875rem; color: var(--muted); text-align: center; margin-bottom: 32px; }
        .section-title { font-size: 0.75rem; font-weight: 700; color: var(--teal); text-transform: uppercase; letter-spacing: 0.05em; margin: 24px 0 16px; padding-bottom: 8px; border-bottom: 1px solid var(--border); }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .field { margin-bottom: 16px; }
        label { display: block; font-size: .82rem; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        input, select {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-family: 'DM Sans', sans-serif; font-size: .9rem; color: var(--ink);
            transition: border-color .2s; outline: none; background: white;
        }
        input:focus, select:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(13,110,110,.1); }
        .btn { width: 100%; padding: 13px; background: var(--teal); color: white; border: none; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: .95rem; font-weight: 600; cursor: pointer; transition: all .2s; margin-top: 8px; }
        .btn:hover { background: var(--teal-mid); transform: translateY(-1px); }
        .footer-link { text-align: center; font-size: .82rem; color: var(--muted); margin-top: 24px; }
        .footer-link a { color: var(--teal); font-weight: 600; text-decoration: none; }
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

    <h1>Register Your Facility</h1>
    <p class="subtitle">Join AfyaLink as a hospital or healthcare facility</p>

    <form method="POST" action="{{ route('hospital.register.submit') }}">
        @csrf

        <div class="section-title">Facility Information</div>

        <div class="field">
            <label for="facility_name">Facility Name</label>
            <input type="text" id="facility_name" name="facility_name" value="{{ old('facility_name') }}" required placeholder="Kenyatta National Hospital">
            @error('facility_name') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="field">
                <label for="facility_type">Facility Type</label>
                <select id="facility_type" name="facility_type" required>
                    <option value="">Select type</option>
                    <option value="hospital" {{ old('facility_type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                    <option value="clinic" {{ old('facility_type') == 'clinic' ? 'selected' : '' }}>Clinic</option>
                    <option value="health_center" {{ old('facility_type') == 'health_center' ? 'selected' : '' }}>Health Center</option>
                    <option value="dispensary" {{ old('facility_type') == 'dispensary' ? 'selected' : '' }}>Dispensary</option>
                </select>
                @error('facility_type') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label for="mfl_code">MFL Code</label>
                <input type="text" id="mfl_code" name="mfl_code" value="{{ old('mfl_code') }}" required placeholder="00000">
                @error('mfl_code') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label for="county">County</label>
                <input type="text" id="county" name="county" value="{{ old('county') }}" required placeholder="Nairobi">
                @error('county') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label for="sub_county">Sub-County</label>
                <input type="text" id="sub_county" name="sub_county" value="{{ old('sub_county') }}" required placeholder="Starehe">
                @error('sub_county') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="field">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="+254 700 000000">
            @error('phone') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="section-title">Administrator Account</div>

        <div class="row">
            <div class="field">
                <label for="admin_first_name">First Name</label>
                <input type="text" id="admin_first_name" name="admin_first_name" value="{{ old('admin_first_name') }}" required placeholder="John">
                @error('admin_first_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label for="admin_last_name">Last Name</label>
                <input type="text" id="admin_last_name" name="admin_last_name" value="{{ old('admin_last_name') }}" required placeholder="Doe">
                @error('admin_last_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="field">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="admin@hospital.ke">
            @error('email') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Min. 8 characters">
            @error('password') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat password">
        </div>

        <button type="submit" class="btn">Register Facility</button>
    </form>

    <div class="footer-link">
        Already have an account? <a href="{{ route('hospital.login') }}">Sign in</a>
    </div>
    <div class="footer-link">
        <a href="/">← Back to Home</a>
    </div>
</div>
</body>
</html>
