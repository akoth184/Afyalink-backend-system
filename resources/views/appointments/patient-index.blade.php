<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Appointments — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>body{font-family:'Inter',sans-serif;}.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;}.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}</style>
</head>
<body style="background:#f0f6ff;">
<div style="display:flex;min-height:100vh;">
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:10px;">
    <svg width="28" height="28" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="9" fill="rgba(255,255,255,0.1)"/><circle cx="18" cy="18" r="3.5" fill="#3b82f6"/><circle cx="8" cy="11" r="2.5" fill="#60a5fa"/><circle cx="28" cy="11" r="2.5" fill="#60a5fa"/><circle cx="8" cy="25" r="2.5" fill="#60a5fa"/><circle cx="28" cy="25" r="2.5" fill="#60a5fa"/><line x1="18" y1="18" x2="8" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="8" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><polyline points="11,18 13,18 14,14 16,22 17,16 19,18 25,18" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
    <div><div style="font-size:15px;font-weight:700;color:white;">AfyaLink</div><div style="font-size:11px;color:rgba(255,255,255,.4);">Patient Portal</div></div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->first_name ?? 'P',0,1)) }}</div>
    <div><div style="font-size:13px;color:white;font-weight:600;">{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</div><div style="font-size:11px;color:rgba(255,255,255,.4);">{{ Auth::user()->patient_id ?? 'Patient' }}</div></div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <a href="{{ route('patient.dashboard') }}" class="slink">Dashboard</a>
    <a href="{{ route('patient.records') }}" class="slink">Medical Records</a>
    <a href="{{ route('patient.referrals') }}" class="slink">My Referrals</a>
    <a href="{{ route('appointments.index') }}" class="slink on">My Appointments</a>
    <a href="{{ route('patient.lab-tests') }}" class="slink">Lab Results</a>
    <a href="{{ route('patient.payments') }}" class="slink">M-PESA Payments</a>
    <a href="{{ route('patient.nearby-hospitals') }}" class="slink">Nearby Hospitals</a>
    <a href="{{ route('profile') }}" class="slink">Profile</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Logout</button>
    </form>
  </div>
</aside>
<div style="margin-left:220px;flex:1;">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:10;">
    <div style="font-size:20px;font-weight:700;color:#0f172a;">My Appointments</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Your scheduled appointments with doctors</div>
  </div>
  <div style="padding:24px 28px;">
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>All Appointments ({{ $appointments->count() }})</div>
      @forelse($appointments as $apt)
      <div style="padding:16px;background:{{ $apt->status === 'scheduled' ? '#f0f6ff' : '#f8fafc' }};border-radius:8px;border:1px solid {{ $apt->status === 'scheduled' ? '#bfdbfe' : '#e2e8f0' }};margin-bottom:10px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
          <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
              <div style="font-size:15px;font-weight:700;color:#0f172a;">Dr. {{ optional($apt->doctor)->first_name ?? 'N/A' }} {{ optional($apt->doctor)->last_name ?? '' }}</div>
              <span style="background:{{ $apt->status === 'completed' ? '#dcfce7' : ($apt->status === 'cancelled' ? '#fee2e2' : '#dbeafe') }};color:{{ $apt->status === 'completed' ? '#16a34a' : ($apt->status === 'cancelled' ? '#dc2626' : '#1d4ed8') }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($apt->status) }}</span>
            </div>
            <div style="font-size:12px;color:#64748b;">{{ optional($apt->doctor)->specialization ?? 'General Practice' }}</div>
            <div style="font-size:12px;color:#64748b;margin-top:4px;">{{ optional($apt->facility)->name ?? 'N/A' }}</div>
            <div style="margin-top:10px;background:white;border-radius:8px;padding:10px 14px;border:1px solid #e2e8f0;display:inline-flex;align-items:center;gap:12px;">
              <div style="text-align:center;"><div style="font-size:18px;font-weight:800;color:#2563eb;">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d') }}</div><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M Y') }}</div></div>
              <div style="width:1px;height:30px;background:#e2e8f0;"></div>
              <div style="font-size:14px;font-weight:700;color:#0f172a;">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</div>
            </div>
            @if($apt->notes)
            <div style="font-size:12px;color:#64748b;margin-top:8px;">Note: {{ $apt->notes }}</div>
            @endif
          </div>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:48px;">
        <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">No appointments yet</div>
        <div style="font-size:13px;color:#94a3b8;">Your appointments will appear here once a hospital books one for you</div>
      </div>
      @endforelse
    </div>
  </div>
</div>
</div>
</body>
</html>