<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Application — AfyaLink</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue: #2563eb; --blue-dark: #1e3a5f; --blue-lt: #eff6ff;
            --ink: #1a1f2e; --muted: #5a6275; --border: #dde4e4;
            --red: #e53e3e;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f1f3d 0%, #1e3a5f 100%);
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
        .logo-mark { width: 40px; height: 40px; background: linear-gradient(135deg, var(--blue-dark), var(--blue)); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .logo-mark svg { width: 22px; height: 22px; fill: white; }
        .logo-text { font-family: 'Inter', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--ink); }
        h1 { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 1.6rem; color: var(--ink); margin-bottom: 6px; text-align: center; }
        .subtitle { font-size: .875rem; color: var(--muted); text-align: center; margin-bottom: 32px; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .field { margin-bottom: 16px; }
        label { display: block; font-size: .82rem; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        input, select {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid var(--border); border-radius: 10px;
            font-family: 'DM Sans', sans-serif; font-size: .9rem; color: var(--ink);
            transition: border-color .2s; outline: none; background: white;
        }
        input:focus, select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
        .btn { width: 100%; padding: 13px; background: var(--blue); color: white; border: none; border-radius: 10px; font-family: 'Inter', sans-serif; font-size: .95rem; font-weight: 600; cursor: pointer; transition: all .2s; margin-top: 8px; }
        .btn:hover { background: var(--blue-dark); transform: translateY(-1px); }
        .footer-link { text-align: center; font-size: .82rem; color: var(--muted); margin-top: 24px; }
        .footer-link a { color: var(--blue); font-weight: 600; text-decoration: none; }
        .field-error { font-size: .75rem; color: var(--red); margin-top: 4px; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: .875rem; }
        .alert-success { background: #e6f8ee; color: #22a85a; border: 1px solid #c5e8d3; }
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

    <h1>Apply as Doctor</h1>
    <p class="subtitle">Register your interest to join AfyaLink as a doctor</p>

    <form method="POST" action="{{ route('doctor.apply.submit') }}">
        @csrf

        <div class="row">
            <div class="field">
                <label for="first_name">First name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="John">
                @error('first_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label for="last_name">Last name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="Doe">
                @error('last_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="doctor@hospital.ke">
            @error('email') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="field">
                <label for="phone">Phone number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="+254 700 000000">
                @error('phone') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label for="license_number">Medical License Number</label>
                <input type="text" id="license_number" name="license_number" value="{{ old('license_number') }}" required placeholder="MD/KN/000000">
                @error('license_number') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="field">
            <label for="specialization">Specialization</label>
            <select id="specialization" name="specialization" required>
                <option value="">Select specialization</option>
                <option value="General Practice" {{ old('specialization') == 'General Practice' ? 'selected' : '' }}>General Practice</option>
                <option value="Internal Medicine" {{ old('specialization') == 'Internal Medicine' ? 'selected' : '' }}>Internal Medicine</option>
                <option value="Pediatrics" {{ old('specialization') == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                <option value="Surgery" {{ old('specialization') == 'Surgery' ? 'selected' : '' }}>Surgery</option>
                <option value="Obstetrics & Gynecology" {{ old('specialization') == 'Obstetrics & Gynecology' ? 'selected' : '' }}>Obstetrics & Gynecology</option>
                <option value="Psychiatry" {{ old('specialization') == 'Psychiatry' ? 'selected' : '' }}>Psychiatry</option>
                <option value="Emergency Medicine" {{ old('specialization') == 'Emergency Medicine' ? 'selected' : '' }}>Emergency Medicine</option>
                <option value="Other" {{ old('specialization') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('specialization') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="facility_name">Current Facility/Hospital</label>
            <input type="text" id="facility_name" name="facility_name" value="{{ old('facility_name') }}" required placeholder="Kenyatta National Hospital">
            @error('facility_name') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Min. 8 characters">
            @error('password') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat password">
        </div>

        <button type="submit" class="btn">Submit Application</button>
    </form>

    <div class="footer-link">
        Already have an account? <a href="{{ route('doctor.login') }}">Sign in</a>
    </div>
    <div class="footer-link">
        <a href="/">← Back to Home</a>
    </div>
</div>
</body>
</html>
