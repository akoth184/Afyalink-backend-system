<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Appointments — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<style>
body{font-family:'Inter',sans-serif;margin:0;padding:0;background:#f0f6ff;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh;">
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:10px;">
    <svg width="28" height="28" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="9" fill="rgba(255,255,255,0.1)"/><circle cx="18" cy="18" r="3.5" fill="#3b82f6"/><circle cx="8" cy="11" r="2.5" fill="#60a5fa"/><circle cx="28" cy="11" r="2.5" fill="#60a5fa"/><circle cx="8" cy="25" r="2.5" fill="#60a5fa"/><circle cx="28" cy="25" r="2.5" fill="#60a5fa"/><line x1="18" y1="18" x2="8" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="8" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><polyline points="11,18 13,18 14,14 16,22 17,16 19,18 25,18" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
    <div><div style="font-size:15px;font-weight:700;color:white;">AfyaLink</div><div style="font-size:11px;color:rgba(255,255,255,.4);">Hospital Portal</div></div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <a href="{{ route('hospital.dashboard') }}" class="slink">Dashboard</a>
    <a href="{{ route('appointments.index') }}" class="slink on">Appointments</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Logout</button>
    </form>
  </div>
</aside>
<div style="margin-left:220px;flex:1;padding:24px;">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;margin:-24px -24px 24px -24px;">
    <div style="font-size:20px;font-weight:700;color:#0f172a;">Appointments</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Manage patient appointments</div>
  </div>
  @if(session('success'))
  <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">✓ {{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div style="background:#fee2e2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">{{ session('error') }}</div>
  @endif
  <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
    <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;">All Appointments ({{ $appointments->count() }})</div>
    @forelse($appointments as $appt)
    <div style="display:flex;align-items:flex-start;gap:12px;padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:10px;">
      <div style="width:40px;height:40px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#1d4ed8;">{{ strtoupper(substr(optional($appt->patient)->first_name ?? 'P',0,1)) }}</div>
      <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
          <div style="font-size:14px;font-weight:700;color:#0f172a;">{{ optional($appt->patient)->first_name ?? 'N/A' }} {{ optional($appt->patient)->last_name ?? '' }}</div>
          <span style="background:{{ $appt->status === 'completed' ? '#dcfce7' : ($appt->status === 'cancelled' ? '#fee2e2' : '#fef3c7') }};color:{{ $appt->status === 'completed' ? '#16a34a' : ($appt->status === 'cancelled' ? '#dc2626' : '#d97706') }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($appt->status) }}</span>
        </div>
        <div style="font-size:12px;color:#64748b;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y') }} at {{ $appt->appointment_time }} · Dr. {{ optional($appt->doctor)->first_name ?? '' }} {{ optional($appt->doctor)->last_name ?? '' }}</div>
      </div>
      <form method="POST" action="{{ route('appointments.updateStatus', $appt->id) }}">
        @csrf
        <select name="status" onchange="this.form.submit()" style="padding:6px;border-radius:6px;border:1px solid #e2e8f0;font-size:12px;">
          <option value="scheduled" {{ $appt->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
          <option value="completed" {{ $appt->status == 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="cancelled" {{ $appt->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
      </form>
    </div>
    @empty
    <div style="text-align:center;padding:40px;color:#94a3b8;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">No appointments yet</div>
    </div>
    @endforelse
  </div>
</div>
</div>
</body>
</html>