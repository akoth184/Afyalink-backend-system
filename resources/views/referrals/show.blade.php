<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Referral Details — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
</style>
</head>
<body style="background:#f0f6ff;font-family:'Inter',sans-serif;">
<div style="display:flex;min-height:100vh;">
<aside id="sidebar" style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);">
    <div style="display:flex;align-items:center;gap:10px;">
      <svg width="28" height="28" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="9" fill="rgba(255,255,255,0.1)"/><circle cx="18" cy="18" r="3.5" fill="#3b82f6"/><circle cx="8" cy="11" r="2.5" fill="#60a5fa"/><circle cx="28" cy="11" r="2.5" fill="#60a5fa"/><circle cx="8" cy="25" r="2.5" fill="#60a5fa"/><circle cx="28" cy="25" r="2.5" fill="#60a5fa"/><line x1="18" y1="18" x2="8" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="8" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><polyline points="11,18 13,18 14,14 16,22 17,16 19,18 25,18" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
      <div>
        <div style="font-size:15px;font-weight:700;color:white;">AfyaLink</div>
        <div style="font-size:11px;color:rgba(255,255,255,.4);">@if(Auth::user()->role === 'patient') Patient @else Doctor @endif Portal</div>
      </div>
    </div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->first_name ?? 'U',0,1)) }}</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">@if(Auth::user()->role === 'doctor') Dr. @endif{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">{{ Auth::user()->role === 'doctor' ? (Auth::user()->specialization ?? 'Doctor') : (Auth::user()->patient_id ?? 'Patient') }}</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    @if(Auth::user()->role === 'patient')
    <a href="{{ route('patient.dashboard') }}" class="slink">Dashboard</a>
    <a href="{{ route('patient.records') }}" class="slink">Medical Records</a>
    <a href="{{ route('patient.referrals') }}" class="slink on">My Referrals</a>
    <a href="{{ route('patient.nearby-hospitals') }}" class="slink">Nearby Hospitals</a>
    <a href="{{ route('patient.payments') }}" class="slink">M-PESA Payments</a>
    @else
    <a href="{{ route('doctor.dashboard') }}" class="slink">Dashboard</a>
    <a href="{{ route('patients.index') }}" class="slink">My Patients</a>
    <a href="{{ route('referrals.create') }}" class="slink">Create Referral</a>
    <a href="{{ route('referrals.index') }}" class="slink on">My Referrals</a>
    <a href="{{ route('records.index') }}" class="slink">Medical Records</a>
    <a href="{{ route('facilities.index') }}" class="slink">Facilities</a>
    @endif
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Sign Out</button>
    </form>
  </div>
</aside>
<div id="main-content" style="margin-left:220px;flex:1;">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
    <div>
      <div style="font-size:20px;font-weight:700;color:#0f172a;">Referral Details</div>
      <div style="font-size:12px;color:#94a3b8;margin-top:3px;">REF-{{ str_pad($referral->id,5,'0',STR_PAD_LEFT) }}</div>
    </div>
    <a href="{{ Auth::user()->role === 'patient' ? route('patient.referrals') : route('referrals.index') }}" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">← Back</a>
  </div>
  <div style="padding:24px 28px;">
    @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">✓ {{ session('success') }}</div>
    @endif
    <!-- REFERRAL INFO -->
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Referral Information</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Patient</div><div style="font-size:14px;font-weight:600;color:#0f172a;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</div></div>
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Status</div>
          <span style="background:{{ $referral->status === 'accepted' ? '#dcfce7' : ($referral->status === 'rejected' ? '#fee2e2' : '#fef3c7') }};color:{{ $referral->status === 'accepted' ? '#16a34a' : ($referral->status === 'rejected' ? '#dc2626' : '#d97706') }};padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">{{ ucfirst($referral->status ?? 'pending') }}</span>
        </div>
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">From</div><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->referringFacility)->name ?? 'N/A' }}</div></div>
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">To</div><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->receivingFacility)->name ?? 'N/A' }}</div></div>
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Priority</div>
          <span style="background:{{ ($referral->priority ?? 'routine') === 'emergency' ? '#fee2e2' : (($referral->priority ?? 'routine') === 'urgent' ? '#fef3c7' : '#dbeafe') }};color:{{ ($referral->priority ?? 'routine') === 'emergency' ? '#dc2626' : (($referral->priority ?? 'routine') === 'urgent' ? '#d97706' : '#2563eb') }};padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">{{ ucfirst($referral->priority ?? 'Routine') }}</span>
        </div>
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Date Created</div><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $referral->created_at ? $referral->created_at->format('d M Y') : 'N/A' }}</div></div>
        @if($referral->appointment_date)
        <div><div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Expected Appointment</div><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ \Carbon\Carbon::parse($referral->appointment_date)->format('d M Y') }}</div></div>
        @endif
      </div>
    </div>
    <!-- REASON + CLINICAL -->
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Clinical Details</div>
      @if($referral->reason)
      <div style="margin-bottom:14px;">
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Reason for Referral</div>
        <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:12px;border-radius:8px;">{{ $referral->reason }}</div>
      </div>
      @endif
      @if($referral->clinical_summary)
      <div style="margin-bottom:14px;">
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Clinical Summary</div>
        <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:12px;border-radius:8px;">{{ $referral->clinical_summary }}</div>
      </div>
      @endif
      @if($referral->notes)
      <div>
        <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Additional Notes</div>
        <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:12px;border-radius:8px;">{{ $referral->notes }}</div>
      </div>
      @endif
      @if($referral->status === 'rejected' && $referral->rejection_reason)
      <div style="margin-top:14px;background:#fee2e2;border-left:3px solid #dc2626;padding:12px 14px;border-radius:0 8px 8px 0;">
        <div style="font-size:11px;font-weight:700;color:#dc2626;margin-bottom:4px;">Rejection Reason</div>
        <div style="font-size:13px;color:#991b1b;line-height:1.6;">{{ $referral->rejection_reason }}</div>
      </div>
      @endif
    </div>
    <!-- ATTACHMENT -->
    @if($referral->attachment_path)
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Attachment</div>
      <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
        <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ basename($referral->attachment_path) }}</div>
        <a href="{{ Storage::url($referral->attachment_path) }}" target="_blank" style="background:#2563eb;color:white;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">Download</a>
      </div>
    </div>
    @endif
  </div>
</div>
</div>
</body>
</html>
