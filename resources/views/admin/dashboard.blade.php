<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;cursor:pointer;transition:all .15s;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
.section{display:none;}
.section.active{display:block;}
.badge-accepted,.badge-active{background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-rejected{background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.card{background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;}
.stat-card{background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;}
.tl{padding-left:16px;border-left:2px solid #e2e8f0;}
.tli{padding-bottom:14px;position:relative;}
.tldot{width:10px;height:10px;border-radius:50%;position:absolute;left:-21px;top:2px;}
#sidebar{
  width:220px;
  background:#1e3a5f;
  position:fixed;
  top:0;
  bottom:0;
  left:0;
  z-index:400;
  transition:transform .3s ease;
  display:flex;
  flex-direction:column;
}
@media(max-width:900px){
  #sidebar{transform:translateX(-220px);}
  #sidebar.open{transform:translateX(0) !important;background:#1e3a5f !important;}
  #main-content{margin-left:0 !important;}
  #hamburger{display:flex !important;}
  .overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);z-index:399;}
  .overlay.show{display:block;}
}
</style>
</head>
<body style="background:#f0f6ff;font-family:'Inter',sans-serif;">
<div class="overlay" id="overlay"></div>
<div id="hamburger">
    <svg width="28" height="28" fill="white">
        <rect y="4" width="28" height="4"></rect>
        <rect y="12" width="28" height="4"></rect>
        <rect y="20" width="28" height="4"></rect>
    </svg>
</div>
<div style="display:flex;min-height:100vh;">

<!-- SIDEBAR -->
<aside id="sidebar" style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;overflow-y:auto;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);">
    <div style="font-size:16px;font-weight:700;color:white;">AfyaLink</div>
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Admin Portal</div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#fce7f3;color:#be185d;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">SA</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">System Admin</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">Super Administrator</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Main</div>
    <div class="slink on" onclick="showSection('dashboard',this)">Dashboard</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Verification</div>
    <div class="slink" onclick="showSection('doctors',this)">Doctor Applications</div>
    <div class="slink" onclick="showSection('hospitals',this)">Hospital Applications</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Management</div>
    <div class="slink" onclick="showSection('users',this)">Manage Users</div>
    <div class="slink" onclick="showSection('referrals',this)">All Referrals</div>
    <div class="slink" onclick="showSection('facilities',this)">Facilities</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">System</div>
    <div class="slink" onclick="showSection('reports',this)">System Reports</div>
    <div class="slink" onclick="showSection('logs',this)">Audit Logs</div>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Sign Out</button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<div id="main-content" style="margin-left:220px;flex:1;">

<!-- SUCCESS/ERROR MESSAGES -->
@if(session('success'))
<div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 28px;font-size:13px;font-weight:500;">✓ {{ session('success') }}</div>
@endif
@if(session('error'))
<div style="background:#fee2e2;border:1px solid #fecaca;color:#dc2626;padding:12px 28px;font-size:13px;">{{ session('error') }}</div>
@endif

<!-- DASHBOARD SECTION -->
<div id="sec-dashboard" class="section active">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:10;">
    <div style="font-size:20px;font-weight:700;color:#0f172a;">Admin Dashboard</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">System overview and management</div>
  </div>
  <div style="padding:24px 28px;">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
      <div class="stat-card" onclick="showSection('users',document.querySelector('[onclick*=\'users\']'))" style="cursor:pointer;"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Patients</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_patients'] ?? 0 }}</div><div style="font-size:11px;color:#16a34a;margin-top:5px;">Click to view all</div></div>
      <div class="stat-card" onclick="showSection('doctors',document.querySelector('[onclick*=\'doctors\']'))" style="cursor:pointer;"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Doctors</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_doctors'] ?? 0 }}</div><div style="font-size:11px;color:#2563eb;margin-top:5px;">Click to view all</div></div>
      <div class="stat-card" onclick="showSection('facilities',document.querySelector('[onclick*=\'facilities\']'))" style="cursor:pointer;"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Facilities</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_facilities'] ?? 0 }}</div><div style="font-size:11px;color:#2563eb;margin-top:5px;">Click to view all</div></div>
      <div class="stat-card" onclick="showSection('referrals',document.querySelector('[onclick*=\'referrals\']'))" style="cursor:pointer;"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Referrals</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_referrals'] ?? 0 }}</div><div style="font-size:11px;color:#16a34a;margin-top:5px;">Click to view all</div></div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
      <div class="card" style="margin-bottom:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;"><span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Pending Approvals</span></div>
        @php $pendingDoctors = \App\Models\User::where('role','doctor')->where('is_active',false)->get(); $pendingFacilities = \App\Models\Facility::where('is_active',false)->get(); @endphp
        @forelse($pendingDoctors as $doctor)
        <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
          <div style="width:30px;height:30px;border-radius:50%;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1)) }}</div>
          <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div><div style="font-size:11px;color:#94a3b8;">Doctor Application · {{ $doctor->specialization ?? 'General' }}</div></div>
          <div style="display:flex;gap:6px;">
            <form method="POST" action="{{ route('admin.doctor.approve', $doctor->id) }}">@csrf<button type="submit" style="background:#2563eb;color:white;border:none;padding:5px 12px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;">Approve</button></form>
            <form method="POST" action="{{ route('admin.doctor.reject', $doctor->id) }}">@csrf<button type="submit" style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;padding:5px 12px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;">Reject</button></form>
          </div>
        </div>
        @empty
        @endforelse
        @forelse($pendingFacilities as $facility)
        <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
          <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($facility->name ?? 'H', 0, 1)) }}</div>
          <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $facility->name }}</div><div style="font-size:11px;color:#94a3b8;">Hospital Application · {{ $facility->county }}</div></div>
          <div style="display:flex;gap:6px;">
            <form method="POST" action="{{ route('admin.facility.approve', $facility->id) }}">@csrf<button type="submit" style="background:#2563eb;color:white;border:none;padding:5px 12px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;">Approve</button></form>
            <form method="POST" action="{{ route('admin.facility.reject', $facility->id) }}">@csrf<button type="submit" style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;padding:5px 12px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;">Reject</button></form>
          </div>
        </div>
        @empty
        @endforelse
        @if($pendingDoctors->isEmpty() && $pendingFacilities->isEmpty())
        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No pending approvals</div>
        @endif
      </div>
      <div class="card" style="margin-bottom:0;">
        <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Recent Activity</div>
        <div class="tl">
          @foreach(\App\Models\Referral::latest()->take(4)->get() as $r)
          <div class="tli">
            <div class="tldot" style="background:{{ $r->status === 'accepted' ? '#16a34a' : ($r->status === 'rejected' ? '#dc2626' : '#d97706') }};"></div>
            <div style="font-size:13px;font-weight:600;color:#0f172a;">Referral {{ $r->status }}</div>
            <div style="font-size:11px;color:#94a3b8;">{{ $r->created_at->diffForHumans() }}</div>
            <div style="font-size:12px;color:#64748b;margin-top:3px;">{{ optional($r->patient)->first_name ?? 'N/A' }} · {{ $r->reason ?? '' }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    <div class="card">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;"><span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Recent Referrals</span><span style="font-size:12px;color:#2563eb;cursor:pointer;" onclick="showSection('referrals',document.querySelectorAll('.slink')[4])">View all →</span></div>
      @foreach(\App\Models\Referral::with(['patient','referringFacility','receivingFacility'])->latest()->take(4)->get() as $referral)
      <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(optional($referral->patient)->first_name ?? 'P',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</div><div style="font-size:11px;color:#94a3b8;">{{ optional($referral->referringFacility)->name ?? 'N/A' }} → {{ optional($referral->receivingFacility)->name ?? 'N/A' }} · {{ $referral->reason ?? '' }}</div></div>
        <span class="badge-{{ $referral->status ?? 'pending' }}">{{ ucfirst($referral->status ?? 'pending') }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

<!-- DOCTOR APPLICATIONS SECTION -->
<div id="sec-doctors" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;"><div style="font-size:20px;font-weight:700;color:#0f172a;">Doctor Applications</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">Review and approve doctor registrations</div></div>
  <div style="padding:24px 28px;">
    <div class="card">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;"><span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Pending Applications</span><span style="background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ \App\Models\User::where('role','doctor')->where('is_active',false)->count() }} Pending</span></div>
      @forelse(\App\Models\User::where('role','doctor')->where('is_active',false)->get() as $doctor)
      <div style="display:flex;align-items:center;gap:12px;padding:14px;background:#f8fafc;border-radius:8px;margin-bottom:10px;border:1px solid #e2e8f0;">
        <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($doctor->first_name ?? 'D',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:700;color:#0f172a;">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div><div style="font-size:12px;color:#64748b;">{{ $doctor->email }} · {{ $doctor->specialization ?? 'General Practice' }} · {{ $doctor->license_number ?? 'N/A' }}</div></div>
        <div style="display:flex;gap:8px;">
          <form method="POST" action="{{ route('admin.doctor.approve', $doctor->id) }}">@csrf<button type="submit" style="background:#2563eb;color:white;border:none;padding:7px 16px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">Approve</button></form>
          <form method="POST" action="{{ route('admin.doctor.reject', $doctor->id) }}">@csrf<button type="submit" style="background:#fee2e2;color:#dc2626;border:1.5px solid #fca5a5;padding:7px 16px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">Reject</button></form>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:30px;color:#94a3b8;font-size:13px;">No pending doctor applications</div>
      @endforelse
    </div>
    <div class="card">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Approved Doctors ({{ \App\Models\User::where('role','doctor')->where('is_active',true)->count() }})</div>
      @foreach(\App\Models\User::where('role','doctor')->where('is_active',true)->get() as $doctor)
      <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($doctor->first_name ?? 'D',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div><div style="font-size:11px;color:#94a3b8;">{{ $doctor->doctor_id ?? $doctor->license_number ?? 'N/A' }} · {{ $doctor->email }}</div></div>
        <span class="badge-active">Active</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

<!-- HOSPITAL APPLICATIONS SECTION -->
<div id="sec-hospitals" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;"><div style="font-size:20px;font-weight:700;color:#0f172a;">Hospital Applications</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">Review and approve hospital registrations</div></div>
  <div style="padding:24px 28px;">
    <div class="card">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;"><span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Pending Applications</span><span style="background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ \App\Models\Facility::where('is_active',false)->count() }} Pending</span></div>
      @forelse(\App\Models\Facility::where('is_active',false)->get() as $facility)
      <div style="display:flex;align-items:center;gap:12px;padding:14px;background:#f8fafc;border-radius:8px;margin-bottom:10px;border:1px solid #e2e8f0;">
        <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($facility->name ?? 'H',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $facility->name }}</div><div style="font-size:12px;color:#64748b;">{{ $facility->county }} · {{ ucfirst($facility->type) }} · {{ $facility->email ?? 'No email' }}</div></div>
        <div style="display:flex;gap:8px;">
          <form method="POST" action="{{ route('admin.facility.approve', $facility->id) }}">@csrf<button type="submit" style="background:#2563eb;color:white;border:none;padding:7px 16px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">Approve</button></form>
          <form method="POST" action="{{ route('admin.facility.reject', $facility->id) }}">@csrf<button type="submit" style="background:#fee2e2;color:#dc2626;border:1.5px solid #fca5a5;padding:7px 16px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">Reject</button></form>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:30px;color:#94a3b8;font-size:13px;">No pending hospital applications</div>
      @endforelse
    </div>
    <div class="card">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Approved Hospitals ({{ \App\Models\Facility::where('is_active',true)->count() }})</div>
      @foreach(\App\Models\Facility::where('is_active',true)->get() as $facility)
      <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($facility->name ?? 'H',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $facility->name }}</div><div style="font-size:11px;color:#94a3b8;">{{ $facility->county }} · {{ ucfirst($facility->type) }}</div></div>
        <span class="badge-active">Active</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

<!-- MANAGE USERS SECTION -->
<div id="sec-users" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;"><div style="font-size:20px;font-weight:700;color:#0f172a;">Manage Users</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">View all system users</div></div>
  <div style="padding:24px 28px;">
    <div style="display:flex;gap:8px;margin-bottom:16px;">
      <button onclick="showUserTab('patients',this)" id="utab-patients" style="background:#2563eb;color:white;border:none;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Patients ({{ \App\Models\User::where('role','patient')->count() }})</button>
      <button onclick="showUserTab('doctors',this)" id="utab-doctors" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Doctors ({{ \App\Models\User::where('role','doctor')->count() }})</button>
      <button onclick="showUserTab('hospitals',this)" id="utab-hospitals" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Hospitals ({{ \App\Models\User::where('role','hospital')->count() }})</button>
    </div>

    <div id="utab-content-patients">
      @foreach(\App\Models\User::where('role','patient')->latest()->get() as $user)
      <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:34px;height:34px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($user->first_name ?? 'P',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $user->first_name }} {{ $user->last_name }}</div><div style="font-size:11px;color:#94a3b8;">{{ $user->patient_id ?? 'N/A' }} · {{ $user->email }} · {{ $user->gender ?? 'N/A' }}</div></div>
        <span style="background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Active</span>
      </div>
      @endforeach
    </div>

    <div id="utab-content-doctors" style="display:none;">
      @foreach(\App\Models\User::where('role','doctor')->latest()->get() as $user)
      <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:34px;height:34px;border-radius:50%;background:#dcfce7;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($user->first_name ?? 'D',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">Dr. {{ $user->first_name }} {{ $user->last_name }}</div><div style="font-size:11px;color:#94a3b8;">{{ $user->doctor_id ?? 'N/A' }} · {{ $user->email }} · {{ $user->specialization ?? 'General' }}</div></div>
        <span style="background:{{ $user->is_active ? '#dcfce7' : '#fef3c7' }};color:{{ $user->is_active ? '#16a34a' : '#d97706' }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ $user->is_active ? 'Active' : 'Pending' }}</span>
      </div>
      @endforeach
    </div>

    <div id="utab-content-hospitals" style="display:none;">
      @foreach(\App\Models\User::where('role','hospital')->latest()->get() as $user)
      <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:34px;height:34px;border-radius:50%;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($user->first_name ?? 'H',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $user->first_name }} {{ $user->last_name }}</div><div style="font-size:11px;color:#94a3b8;">{{ $user->email }} · Facility ID: {{ $user->facility_id ?? 'Not linked' }}</div></div>
        <span style="background:{{ $user->is_active ? '#dcfce7' : '#fef3c7' }};color:{{ $user->is_active ? '#16a34a' : '#d97706' }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ $user->is_active ? 'Active' : 'Pending' }}</span>
      </div>
      @endforeach
    </div>

    <script>
    function showUserTab(tab, el) {
      ['patients','doctors','hospitals'].forEach(function(t) {
        document.getElementById('utab-content-'+t).style.display = 'none';
        document.getElementById('utab-'+t).style.background = 'white';
        document.getElementById('utab-'+t).style.color = '#2563eb';
        document.getElementById('utab-'+t).style.border = '1.5px solid #2563eb';
      });
      document.getElementById('utab-content-'+tab).style.display = 'block';
      el.style.background = '#2563eb';
      el.style.color = 'white';
      el.style.border = 'none';
    }
    </script>
  </div>
</div>

<!-- ALL REFERRALS SECTION -->
<div id="sec-referrals" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;"><div style="font-size:20px;font-weight:700;color:#0f172a;">All Referrals</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">System-wide referral management</div></div>
  <div style="padding:24px 28px;">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
      <div class="stat-card"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Referral::count() }}</div><div style="font-size:11px;color:#2563eb;margin-top:5px;">All time</div></div>
      <div class="stat-card"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Accepted</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Referral::where('status','accepted')->count() }}</div><div style="font-size:11px;color:#16a34a;margin-top:5px;">This week</div></div>
      <div class="stat-card"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Pending</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Referral::where('status','pending')->count() }}</div><div style="font-size:11px;color:#d97706;margin-top:5px;">Awaiting</div></div>
      <div class="stat-card"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Rejected</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Referral::where('status','rejected')->count() }}</div><div style="font-size:11px;color:#dc2626;margin-top:5px;">This month</div></div>
    </div>
    <div class="card">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>All Referrals ({{ \App\Models\Referral::count() }})</div>
      <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead><tr style="border-bottom:2px solid #f1f5f9;">
          <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Ref #</th>
          <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Patient</th>
          <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">From</th>
          <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">To</th>
          <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Reason</th>
          <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Status</th>
          <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Rejection Reason</th>
          <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Date</th>
        </tr></thead>
        <tbody>
        @foreach(\App\Models\Referral::with(['patient','referringFacility','receivingFacility'])->latest()->get() as $referral)
        <tr style="border-bottom:1px solid #f1f5f9;">
          <td style="padding:11px 0;font-weight:600;">REF-{{ str_pad($referral->id,5,'0',STR_PAD_LEFT) }}</td>
          <td style="padding:11px 0;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</td>
          <td style="padding:11px 0;color:#64748b;">{{ optional($referral->referringFacility)->name ?? 'N/A' }}</td>
          <td style="padding:11px 0;color:#64748b;">{{ optional($referral->receivingFacility)->name ?? 'N/A' }}</td>
          <td style="padding:11px 0;color:#64748b;">{{ $referral->reason ?? 'N/A' }}</td>
          <td style="padding:11px 0;"><span class="badge-{{ $referral->status ?? 'pending' }}">{{ ucfirst($referral->status ?? 'pending') }}</span></td>
          <td style="padding:11px 0;color:#64748b;font-size:12px;max-width:150px;">
            @if($referral->status === 'rejected' && $referral->rejection_reason)
            <span style="color:#dc2626;">{{ Str::limit($referral->rejection_reason, 50) }}</span>
            @else
            —
            @endif
          </td>
          <td style="padding:11px 0;color:#94a3b8;">{{ $referral->created_at->format('d M Y') }}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- FACILITIES SECTION -->
<div id="sec-facilities" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;"><div style="font-size:20px;font-weight:700;color:#0f172a;">Facilities</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">All registered health facilities</div></div>
  <div style="padding:24px 28px;">
    <div class="card">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>All Facilities ({{ \App\Models\Facility::where('is_active', true)->count() }})</div>
      @foreach(\App\Models\Facility::where('is_active', true)->latest()->get() as $facility)
      <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($facility->name ?? 'F',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $facility->name }}</div><div style="font-size:11px;color:#94a3b8;">{{ $facility->county }} · {{ ucfirst($facility->type) }} · {{ $facility->phone ?? 'No phone' }}</div></div>
        <span class="{{ $facility->is_active ? 'badge-active' : 'badge-pending' }}">{{ $facility->is_active ? 'Active' : 'Inactive' }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>

<!-- SYSTEM REPORTS SECTION -->
<div id="sec-reports" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
    <div><div style="font-size:20px;font-weight:700;color:#0f172a;">System Reports</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">Generate and export system-wide reports</div></div>
    <a href="{{ route('admin.reports.export') }}" target="_blank" style="background:#2563eb;color:white;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-block;">Export PDF</a>
  </div>
  <div style="padding:24px 28px;">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
      <div class="stat-card"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Patients</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\User::where('role','patient')->count() }}</div></div>
      <div class="stat-card"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Referrals</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Referral::count() }}</div></div>
      <div class="stat-card"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Acceptance Rate</div><div style="font-size:26px;font-weight:700;color:#0f172a;">@php $total = \App\Models\Referral::count(); $accepted = \App\Models\Referral::where('status','accepted')->count(); echo $total > 0 ? round(($accepted/$total)*100) : 0; @endphp%</div></div>
      <div class="stat-card"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Active Facilities</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Facility::where('is_active',true)->count() }}</div></div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
      <div class="card" style="margin-bottom:0;">
        <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Referrals by Status</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px;background:#f0fdf4;border-radius:8px;"><span style="font-size:13px;font-weight:500;color:#0f172a;">Accepted</span><span style="font-size:16px;font-weight:700;color:#16a34a;">{{ \App\Models\Referral::where('status','accepted')->count() }}</span></div>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px;background:#fef3c7;border-radius:8px;"><span style="font-size:13px;font-weight:500;color:#0f172a;">Pending</span><span style="font-size:16px;font-weight:700;color:#d97706;">{{ \App\Models\Referral::where('status','pending')->count() }}</span></div>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px;background:#fee2e2;border-radius:8px;"><span style="font-size:13px;font-weight:500;color:#0f172a;">Rejected</span><span style="font-size:16px;font-weight:700;color:#dc2626;">{{ \App\Models\Referral::where('status','rejected')->count() }}</span></div>
        </div>
      </div>
      <div class="card" style="margin-bottom:0;">
        <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Top Referring Facilities</div>
        @foreach(\App\Models\Facility::where('is_active', true)->withCount('referralsFrom')->orderByDesc('referrals_from_count')->take(5)->get() as $f)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
          <span style="color:#0f172a;">{{ $f->name }}</span>
          <span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ $f->referrals_from_count }} referrals</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<!-- AUDIT LOGS SECTION -->
<div id="sec-logs" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
    <div><div style="font-size:20px;font-weight:700;color:#0f172a;">Audit Logs</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">System activity and change history</div></div>
    <a href="{{ route('admin.audit-logs') }}" style="background:#2563eb;color:white;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-block;">View Full Logs</a>
  </div>
  <div style="padding:24px 28px;">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
      <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Total Logs</div>
        <div style="font-size:24px;font-weight:700;color:#0f172a;">{{ \App\Models\AuditLog::count() }}</div>
      </div>
      <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Logins</div>
        <div style="font-size:24px;font-weight:700;color:#7c3aed;">{{ \App\Models\AuditLog::where('action','login')->count() }}</div>
      </div>
      <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Updates</div>
        <div style="font-size:24px;font-weight:700;color:#d97706;">{{ \App\Models\AuditLog::where('action','updated')->count() }}</div>
      </div>
      <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">This Week</div>
        <div style="font-size:24px;font-weight:700;color:#2563eb;">{{ \App\Models\AuditLog::where('created_at', '>=', now()->subWeek())->count() }}</div>
      </div>
    </div>
    <div class="card">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Recent System Activity</div>
      <div class="tl">
        @forelse(\App\Models\AuditLog::latest()->take(8)->get() as $log)
        <div class="tli">
          <div class="tldot" style="background:{{ $log->action === 'login' ? '#7c3aed' : ($log->action === 'updated' ? '#d97706' : ($log->action === 'created' ? '#16a34a' : '#dc2626')) }};"></div>
          <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ ucfirst($log->action) }}</div>
          <div style="font-size:11px;color:#94a3b8;">{{ $log->created_at->format('d M Y, h:i A') }}</div>
          <div style="font-size:12px;color:#64748b;margin-top:3px;">{{ $log->description ?? 'No description' }}</div>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No audit logs yet</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

</div>
</div>

<script>
function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('show');
}
function closeSidebar(){
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('show');
}
function showSection(name, el) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.slink').forEach(l => l.classList.remove('on'));
  document.getElementById('sec-' + name).classList.add('active');
  if(el) el.classList.add('on');
}
</script>
<script>
document.getElementById("hamburger").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main-content");

    if (sidebar.style.transform === "translateX(0px)") {
        sidebar.style.transform = "translateX(-260px)";
        main.style.marginLeft = "0px";
    } else {
        sidebar.style.transform = "translateX(0px)";
        main.style.marginLeft = "260px";
    }
});
</script>
</body>
</html>
