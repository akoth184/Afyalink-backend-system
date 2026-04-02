<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hospital Dashboard — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
@if(config('google-maps.api_key'))
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('google-maps.api_key') }}"></script>
@endif
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);border-left:3px solid transparent;cursor:pointer;text-decoration:none;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
.badge-accepted{background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-rejected{background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
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
<aside id="sidebar" style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);">
    <div style="font-size:16px;font-weight:700;color:white;">AfyaLink</div>
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Hospital Portal</div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:38px;height:38px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(optional($facility)->name ?? 'H', 0, 2)) }}</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">{{ optional($facility)->name ?? 'Hospital' }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">{{ optional($facility)->county ?? '' }}</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;overflow-y:auto;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Main</div>
    <div class="slink on" onclick="showSection('dashboard', this)">Dashboard</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Referrals</div>
    <a href="#incoming-referrals" class="slink" onclick="showSection('incoming-referrals', this)">Incoming Referrals</a>
    <a href="#transfer-form" class="slink" onclick="showSection('transfer-form', this)">Transfer Patient</a>
    <a href="#referral-reports" onclick="showSection('referral-reports', this)" class="slink">Referral Reports</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Management</div>
    <div class="slink" onclick="showSection('records', this)">Medical Records</div>
    <div class="slink" onclick="showSection('working-hours-section', this)">Working Hours</div>
    <div class="slink" onclick="showSection('settings', this)">Settings</div>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Sign Out</button>
    </form>
  </div>
</aside>

<!-- MAIN CONTENT -->
<div id="main-content" style="margin-left:220px;flex:1;">

  <!-- TOPBAR -->
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
    <div>
      <div style="font-size:20px;font-weight:700;color:#0f172a;">Hospital Dashboard</div>
      <div style="font-size:12px;color:#94a3b8;margin-top:3px;">{{ optional($facility)->name ?? 'Hospital' }}</div>
    </div>
    <div style="display:flex;align-items:center;gap:12px;">
      @php
        $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
      @endphp
      <div style="position:relative;cursor:pointer;" onclick="toggleNotifications()">
        <div style="width:38px;height:38px;border-radius:50%;background:#f0f6ff;border:1.5px solid #e2e8f0;display:flex;align-items:center;justify-content:center;">
          <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        @if($unreadCount > 0)
        <div style="position:absolute;top:-4px;right:-4px;width:18px;height:18px;background:#dc2626;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:white;border:2px solid white;">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</div>
        @endif
      </div>
      <!-- NOTIFICATIONS DROPDOWN -->
      <div id="notifications-dropdown" style="display:none;position:absolute;top:60px;right:16px;width:340px;background:white;border-radius:12px;border:1px solid #e2e8f0;box-shadow:0 8px 30px rgba(0,0,0,.12);z-index:200;">
        <div style="padding:14px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
          <div style="font-size:14px;font-weight:700;color:#0f172a;">Notifications</div>
          <form method="POST" action="{{ route('notifications.read') }}" style="margin:0;">@csrf<button type="submit" style="background:none;border:none;font-size:12px;color:#2563eb;cursor:pointer;font-family:inherit;font-weight:600;">Mark all read</button></form>
        </div>
        <div style="max-height:300px;overflow-y:auto;">
          @php $notifications = \App\Models\Notification::where('user_id', Auth::id())->latest()->take(10)->get(); @endphp
          @forelse($notifications as $notif)
          <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;background:{{ $notif->is_read ? 'white' : '#f0f6ff' }};">
            <div style="display:flex;align-items:flex-start;gap:10px;">
              <div style="width:8px;height:8px;border-radius:50%;background:{{ $notif->type === 'referral_rejected' ? '#dc2626' : ($notif->type === 'referral_accepted' ? '#16a34a' : '#2563eb') }};flex-shrink:0;margin-top:4px;"></div>
              <div style="flex:1;">
                <div style="font-size:13px;font-weight:{{ $notif->is_read ? '500' : '700' }};color:#0f172a;">{{ $notif->title }}</div>
                <div style="font-size:12px;color:#64748b;margin-top:2px;line-height:1.5;">{{ $notif->message }}</div>
                <div style="font-size:10px;color:#94a3b8;margin-top:4px;">{{ $notif->created_at->diffForHumans() }}</div>
              </div>
            </div>
          </div>
          @empty
          <div style="padding:24px;text-align:center;color:#94a3b8;font-size:13px;">No notifications yet</div>
          @endforelse
        </div>
      </div>
      <a href="#transfer-form" onclick="document.getElementById('transfer-form').scrollIntoView({behavior:'smooth'})" style="background:#2563eb;color:white;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">+ Transfer Patient</a>
    </div>
  </div>

  <!-- CONTENT -->
  <div style="padding:24px 28px;">

    <!-- DASHBOARD SECTION -->
    <div id="sec-dashboard" class="section" style="display:block;">
      <!-- STATS ROW -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
  <div style="background:white;border-radius:10px;padding:18px;border:1px solid #bfdbfe;background:#f0f6ff;">
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Incoming</div>
    <div style="font-size:28px;font-weight:800;color:#2563eb;">{{ $referrals->count() }}</div>
    <div style="font-size:11px;color:#2563eb;margin-top:5px;">All referrals received</div>
  </div>
  <div style="background:#f0fdf4;border-radius:10px;padding:18px;border:1px solid #bbf7d0;">
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Accepted</div>
    <div style="font-size:28px;font-weight:800;color:#16a34a;">{{ $referrals->where('status','accepted')->count() }}</div>
    <div style="font-size:11px;color:#16a34a;margin-top:5px;">Successfully accepted</div>
  </div>
  <div style="background:#fef9c3;border-radius:10px;padding:18px;border:1px solid #fde68a;">
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Pending Action</div>
    <div style="font-size:28px;font-weight:800;color:#d97706;">{{ $referrals->where('status','pending')->count() }}</div>
    <div style="font-size:11px;color:#d97706;margin-top:5px;">Needs review</div>
  </div>
  <div style="background:#fff1f2;border-radius:10px;padding:18px;border:1px solid #fca5a5;">
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Rejected</div>
    <div style="font-size:28px;font-weight:800;color:#dc2626;">{{ $referrals->where('status','rejected')->count() }}</div>
    <div style="font-size:11px;color:#dc2626;margin-top:5px;">All time</div>
  </div>
  <div style="background:#faf5ff;border-radius:10px;padding:18px;border:1px solid #e9d5ff;">
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Transferred Out</div>
    <div style="font-size:28px;font-weight:800;color:#9333ea;">{{ $outgoingReferrals->count() }}</div>
    <div style="font-size:11px;color:#9333ea;margin-top:5px;">Outgoing transfers</div>
  </div>
  <div style="background:#fdf2f8;border-radius:10px;padding:18px;border:1px solid #fbcfe8;">
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Today's Incoming</div>
    <div style="font-size:28px;font-weight:800;color:#be185d;">{{ $referrals->filter(function($r){ return $r->created_at && $r->created_at->isToday(); })->count() }}</div>
    <div style="font-size:11px;color:#be185d;margin-top:5px;">New today</div>
  </div>
  <div style="background:#f0fdf4;border-radius:10px;padding:18px;border:1px solid #bbf7d0;">
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Active Patients</div>
    <div style="font-size:28px;font-weight:800;color:#16a34a;">{{ $referrals->where('status','accepted')->unique('patient_id')->count() }}</div>
    <div style="font-size:11px;color:#16a34a;margin-top:5px;">Accepted referrals</div>
  </div>
  <div style="background:#f0f6ff;border-radius:10px;padding:18px;border:1px solid #bfdbfe;">
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total All Referrals</div>
    <div style="font-size:28px;font-weight:800;color:#2563eb;">{{ $referrals->count() + $outgoingReferrals->count() }}</div>
    <div style="font-size:11px;color:#2563eb;margin-top:5px;">Incoming + Outgoing</div>
  </div>
</div>
    </div><!-- END DASHBOARD SECTION -->

    <!-- INCOMING REFERRALS FULL WIDTH -->
    <div id="sec-incoming-referrals" class="section" style="display:none;background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
<div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
  <div>
    <div style="font-size:20px;font-weight:700;color:#0f172a;">Incoming Referrals</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">All referrals sent to your facility</div>
  </div>
  <div style="display:flex;gap:8px;">
    <button onclick="filterReferrals('all',this)" style="background:#2563eb;color:white;border:none;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;" class="filter-btn">All ({{ $referrals->count() }})</button>
    <button onclick="filterReferrals('pending',this)" style="background:white;color:#d97706;border:1.5px solid #fde68a;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;" class="filter-btn">Pending ({{ $referrals->where('status','pending')->count() }})</button>
    <button onclick="filterReferrals('accepted',this)" style="background:white;color:#16a34a;border:1.5px solid #bbf7d0;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;" class="filter-btn">Accepted ({{ $referrals->where('status','accepted')->count() }})</button>
    <button onclick="filterReferrals('rejected',this)" style="background:white;color:#dc2626;border:1.5px solid #fca5a5;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;" class="filter-btn">Rejected ({{ $referrals->where('status','rejected')->count() }})</button>
  </div>
</div>
<div style="padding:24px 28px;">
  @forelse($referrals->sortByDesc(function($r){ return $r->status === 'pending' ? 1 : 0; }) as $referral)
  <div class="ref-item ref-{{ $referral->status ?? 'pending' }}" style="display:flex;align-items:flex-start;gap:12px;padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:10px;">
    <div style="width:38px;height:38px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(optional($referral->patient)->first_name ?? 'P',0,1)) }}</div>
    <div style="flex:1;">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
        <div style="font-size:14px;font-weight:700;color:#0f172a;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</div>
        <span style="background:{{ $referral->status === 'accepted' ? '#dcfce7' : ($referral->status === 'rejected' ? '#fee2e2' : '#fef3c7') }};color:{{ $referral->status === 'accepted' ? '#16a34a' : ($referral->status === 'rejected' ? '#dc2626' : '#d97706') }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($referral->status ?? 'pending') }}</span>
        @if($referral->priority && $referral->priority !== 'routine')
        <span style="background:{{ $referral->priority === 'emergency' ? '#fee2e2' : '#fef3c7' }};color:{{ $referral->priority === 'emergency' ? '#dc2626' : '#d97706' }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($referral->priority) }}</span>
        @endif
        @if($referral->status === 'pending')
        <span style="background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">Action Required</span>
        @endif
      </div>
      <div style="font-size:12px;color:#64748b;">From: {{ optional($referral->referringFacility)->name ?? 'N/A' }}</div>
      <div style="font-size:12px;color:#64748b;margin-top:2px;">Reason: {{ $referral->reason ?? 'N/A' }} · {{ $referral->created_at ? $referral->created_at->format('d M Y') : '' }}</div>
      @if($referral->rejection_reason)
      <div style="margin-top:8px;background:#fee2e2;border-left:3px solid #dc2626;padding:8px 12px;border-radius:0 6px 6px 0;">
        <div style="font-size:10px;font-weight:700;color:#dc2626;margin-bottom:2px;">Rejection Reason</div>
        <div style="font-size:12px;color:#991b1b;">{{ $referral->rejection_reason }}</div>
      </div>
      @endif
    </div>
    @if($referral->status === 'pending')
    <div style="display:flex;gap:8px;flex-shrink:0;margin-top:4px;">
      <form method="POST" action="{{ route('referrals.updateStatus', $referral->id) }}">
        @method('PATCH')
        @csrf
        <input type="hidden" name="status" value="accepted">
        <input type="hidden" name="redirect_to" value="referrals">
        <button type="submit" style="background:#dcfce7;color:#16a34a;border:1.5px solid #bbf7d0;padding:8px 16px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">Accept</button>
      </form>
      <button type="button" onclick="openRejectModal({{ $referral->id }},'{{ addslashes(optional($referral->patient)->first_name) }} {{ addslashes(optional($referral->patient)->last_name) }}')" style="background:#fee2e2;color:#dc2626;border:1.5px solid #fca5a5;padding:8px 16px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">Reject</button>
    </div>
    @endif
  </div>
  @empty
  <div style="text-align:center;padding:40px;color:#94a3b8;">
    <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">No incoming referrals yet</div>
    <div style="font-size:13px;">Referrals sent to your facility will appear here</div>
  </div>
  @endforelse
</div>
<script>
function filterReferrals(status, el) {
  document.querySelectorAll('.ref-item').forEach(function(item) {
    item.style.display = (status === 'all' || item.classList.contains('ref-' + status)) ? 'flex' : 'none';
  });
  document.querySelectorAll('.filter-btn').forEach(function(btn) {
    btn.style.background = 'white';
    btn.style.borderWidth = '1.5px';
  });
  el.style.background = '#2563eb';
  el.style.color = 'white';
  el.style.borderColor = '#2563eb';
}
</script>
    </div>

    <!-- Referral Reports -->
    <div id="sec-referral-reports" class="section" style="display:none;background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Referral Reports</span>
        <a href="{{ route('hospital.reports.download') }}" style="background:#2563eb;color:white;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Download PDF</a>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
        <div style="background:#f0f6ff;border-radius:8px;padding:16px;text-align:center;">
          <div style="font-size:28px;font-weight:700;color:#2563eb;">{{ $referrals->count() }}</div>
          <div style="font-size:11px;color:#64748b;margin-top:4px;text-transform:uppercase;letter-spacing:.05em;">Total Referrals</div>
        </div>
        <div style="background:#f0fdf4;border-radius:8px;padding:16px;text-align:center;">
          <div style="font-size:28px;font-weight:700;color:#16a34a;">{{ $referrals->where('status','accepted')->count() }}</div>
          <div style="font-size:11px;color:#64748b;margin-top:4px;text-transform:uppercase;letter-spacing:.05em;">Accepted</div>
        </div>
        <div style="background:#fef3c7;border-radius:8px;padding:16px;text-align:center;">
          <div style="font-size:28px;font-weight:700;color:#d97706;">{{ $referrals->where('status','pending')->count() }}</div>
          <div style="font-size:11px;color:#64748b;margin-top:4px;text-transform:uppercase;letter-spacing:.05em;">Pending</div>
        </div>
      </div>
      <div style="display:flex;gap:8px;margin-bottom:16px;">
        <button onclick="showReportTab('incoming',this)" id="tab-incoming" style="background:#2563eb;color:white;border:none;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Incoming</button>
        <button onclick="showReportTab('outgoing',this)" id="tab-outgoing" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Outgoing / Transfers</button>
      </div>
      <div id="report-incoming">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
          <thead>
            <tr style="border-bottom:2px solid #f1f5f9;">
              <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Ref #</th>
              <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Patient</th>
              <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">From</th>
              <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Reason</th>
              <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Status</th>
              <th style="text-align:left;padding:8px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($referrals as $referral)
            @if($referral->receiving_facility_id == optional($facility)->id)
            <tr style="border-bottom:1px solid #f1f5f9;">
              <td style="padding:11px 0;font-weight:600;">REF-{{ str_pad($referral->id,5,'0',STR_PAD_LEFT) }}</td>
              <td style="padding:11px 0;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</td>
              <td style="padding:11px 0;color:#64748b;">{{ optional($referral->referringFacility)->name ?? 'N/A' }}</td>
              <td style="padding:11px 0;color:#64748b;">{{ $referral->reason ?? 'N/A' }}</td>
              <td style="padding:11px 0;"><span class="badge-{{ ($referral->receivingFacility && !$referral->receivingFacility->is_active) ? 'pending' : ($referral->status ?? 'pending') }}">{{ ucfirst(($referral->receivingFacility && !$referral->receivingFacility->is_active) ? 'pending' : ($referral->status ?? 'pending')) }}</span></td>
              <td style="padding:11px 0;color:#94a3b8;">{{ $referral->created_at->format('d M Y') }}</td>
            </tr>
            @endif
            @endforeach
          </tbody>
        </table>
      </div>
      <div id="report-outgoing" style="display:none;">
        @forelse($outgoingReferrals ?? [] as $referral)
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
          <div style="flex:1;">
            <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</div>
            <div style="font-size:11px;color:#94a3b8;">To: {{ optional($referral->receivingFacility)->name ?? 'N/A' }} · {{ $referral->reason ?? '' }}</div>
          </div>
          <div style="text-align:right;">
            <span style="background:{{ $referral->status === 'accepted' ? '#dcfce7' : ($referral->status === 'rejected' ? '#fee2e2' : '#fef3c7') }};color:{{ $referral->status === 'accepted' ? '#16a34a' : ($referral->status === 'rejected' ? '#dc2626' : '#d97706') }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($referral->status ?? 'pending') }}</span>
            <div style="font-size:10px;color:#94a3b8;margin-top:3px;">{{ $referral->created_at ? $referral->created_at->format('d M Y') : '' }}</div>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No outgoing referrals yet</div>
        @endforelse
      </div>
    </div>

    <!-- Medical Records Section -->
    <div id="sec-records" class="section" style="display:none;background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-top:16px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Medical Records</span>
        <span style="font-size:12px;color:#94a3b8;">Records of accepted referred patients</span>
      </div>
      @php
        $facilityId = optional($facility)->id;
        $acceptedPatientIds = \App\Models\Referral::where('receiving_facility_id', $facilityId)
            ->where('status','accepted')
            ->pluck('patient_id')
            ->toArray();
        $hospitalRecords = \App\Models\MedicalRecord::with(['patient','doctor'])
            ->whereIn('patient_id', $acceptedPatientIds)
            ->latest()
            ->get();
      @endphp
      @if($hospitalRecords->isEmpty())
      <div style="text-align:center;padding:40px;color:#94a3b8;">
        <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">No medical records yet</div>
        <div style="font-size:13px;">Medical records for accepted referred patients will appear here</div>
      </div>
      @else
      @foreach($hospitalRecords as $record)
      <div style="display:flex;align-items:flex-start;gap:12px;padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:10px;">
        <div style="flex:1;">
          <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ optional($record->patient)->first_name ?? 'N/A' }} {{ optional($record->patient)->last_name ?? '' }}</div>
          <div style="font-size:12px;color:#64748b;margin-top:2px;">{{ $record->chief_complaint ?? $record->diagnosis ?? 'No diagnosis' }}</div>
          <div style="font-size:11px;color:#94a3b8;margin-top:2px;">Dr. {{ optional($record->doctor)->first_name ?? 'N/A' }} {{ optional($record->doctor)->last_name ?? '' }} · {{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y') : 'No date' }}</div>
        </div>
        <div style="display:flex;gap:6px;flex-shrink:0;">
          <a href="{{ route('records.show', $record->id) }}" style="background:#dbeafe;color:#1d4ed8;padding:6px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">View</a>
          <a href="{{ route('records.download', $record->id) }}" style="background:#2563eb;color:white;padding:6px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">PDF</a>
        </div>
      </div>
      @endforeach
      @endif
    </div>

    <!-- TRANSFER + WORKING HOURS -->
    <div style="display:block;">

      <!-- Transfer Form -->
      <div id="sec-transfer-form" class="section" style="display:none;background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
<div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
  <div>
    <div style="font-size:20px;font-weight:700;color:#0f172a;">Transfer Patient</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Transfer a patient to another health facility</div>
  </div>
</div>
<div style="padding:24px 28px;">
  @if(session('success'))
  <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;font-weight:500;">✓ {{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div style="background:#fee2e2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">{{ session('error') }}</div>
  @endif
  <div style="background:white;border-radius:12px;padding:24px;border:1px solid #e2e8f0;max-width:700px;">
    <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:4px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Transfer Details</div>
    <div style="font-size:12px;color:#64748b;margin-bottom:20px;">Search for a patient, select receiving hospital and provide transfer reason</div>
    <form method="POST" action="{{ route('referrals.store') }}">
      @csrf
      <input type="hidden" name="referred_by" value="{{ Auth::id() }}">
      <input type="hidden" name="referring_facility_id" value="{{ optional($facility)->id }}">
      <input type="hidden" name="status" value="pending">
      <!-- PATIENT SEARCH -->
      <div style="margin-bottom:16px;">
        <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Search Patient</label>
        <div style="display:flex;gap:8px;margin-bottom:8px;">
          <input type="text" id="transfer-search-input" placeholder="Type patient name or PAT-ID..." style="flex:1;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:13px;font-family:inherit;outline:none;">
          <button type="button" onclick="searchTransferPatient()" style="background:#2563eb;color:white;border:none;padding:10px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Search</button>
        </div>
        <div id="transfer-search-results"></div>
        <input type="hidden" name="patient_id" id="transfer-patient-id">
        <div id="transfer-selected-patient" style="display:none;background:#dcfce7;border:1px solid #bbf7d0;border-radius:8px;padding:12px;display:none;align-items:center;gap:10px;">
          <div style="width:34px;height:34px;border-radius:50%;background:#2563eb;color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;" id="transfer-patient-avatar">P</div>
          <div style="flex:1;"><div style="font-size:13px;font-weight:700;color:#0f172a;" id="transfer-patient-name">Patient Name</div><div style="font-size:11px;color:#16a34a;" id="transfer-patient-sub">PAT-ID</div></div>
          <button type="button" onclick="clearTransferPatient()" style="background:none;border:none;color:#64748b;font-size:12px;cursor:pointer;font-family:inherit;">✕ Change</button>
        </div>
      </div>
      <!-- RECEIVING HOSPITAL -->
      <div style="margin-bottom:16px;">
        <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Receiving Hospital</label>
        <select name="receiving_facility_id" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:13px;font-family:inherit;outline:none;cursor:pointer;color:#0f172a;">
          <option value="">-- Select receiving hospital --</option>
          @foreach(\App\Models\Facility::where('is_active',true)->where('id','!=',optional($facility)->id)->orderBy('name')->get() as $f)
          <option value="{{ $f->id }}">{{ $f->name }} — {{ $f->county }}</option>
          @endforeach
        </select>
      </div>
      <!-- PRIORITY -->
      <div style="margin-bottom:16px;">
        <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Transfer Priority</label>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
          <label style="border:2px solid #e2e8f0;border-radius:8px;padding:12px;text-align:center;cursor:pointer;transition:all .15s;" id="priority-routine">
            <input type="radio" name="priority" value="routine" style="display:none;" checked onchange="setPriority('routine')">
            <div style="font-size:12px;font-weight:700;color:#0f172a;">Routine</div>
            <div style="font-size:10px;color:#64748b;margin-top:2px;">Within 24-48hrs</div>
          </label>
          <label style="border:2px solid #2563eb;background:#f0f6ff;border-radius:8px;padding:12px;text-align:center;cursor:pointer;transition:all .15s;" id="priority-urgent">
            <input type="radio" name="priority" value="urgent" style="display:none;" onchange="setPriority('urgent')">
            <div style="font-size:12px;font-weight:700;color:#2563eb;">Urgent</div>
            <div style="font-size:10px;color:#64748b;margin-top:2px;">Within 4-6hrs</div>
          </label>
          <label style="border:2px solid #e2e8f0;border-radius:8px;padding:12px;text-align:center;cursor:pointer;transition:all .15s;" id="priority-emergency">
            <input type="radio" name="priority" value="emergency" style="display:none;" onchange="setPriority('emergency')">
            <div style="font-size:12px;font-weight:700;color:#dc2626;">Emergency</div>
            <div style="font-size:10px;color:#64748b;margin-top:2px;">Immediate</div>
          </label>
        </div>
      </div>
      <!-- REASON -->
      <div style="margin-bottom:20px;">
        <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Reason for Transfer <span style="color:#dc2626;">*</span></label>
        <textarea name="reason" required placeholder="e.g. Patient requires specialist care not available at this facility..." style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:13px;font-family:inherit;outline:none;resize:vertical;min-height:90px;"></textarea>
        <div style="font-size:11px;color:#94a3b8;margin-top:4px;">This will be visible to the receiving hospital and the patient.</div>
      </div>
      <button type="submit" style="width:100%;background:#2563eb;color:white;border:none;padding:13px;border-radius:9px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;">Initiate Transfer</button>
    </form>
  </div>
</div>
<script>
function searchTransferPatient() {
  var query = document.getElementById('transfer-search-input').value.trim();
  if(!query) return;
  var results = document.getElementById('transfer-search-results');
  results.innerHTML = '<div style="padding:10px;font-size:13px;color:#64748b;">Searching...</div>';
  fetch('/patient/search?query=' + encodeURIComponent(query), {
    headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
  })
  .then(r => r.json())
  .then(data => {
    if(!data.length){ results.innerHTML = '<div style="padding:10px;font-size:13px;color:#94a3b8;">No patients found.</div>'; return; }
    results.innerHTML = data.map(p => `
      <div style="display:flex;align-items:center;gap:10px;padding:10px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:6px;">
        <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">${p.first_name.charAt(0)}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">${p.first_name} ${p.last_name}</div><div style="font-size:11px;color:#94a3b8;">${p.patient_id ?? 'N/A'} · ${p.email}</div></div>
        <button type="button" onclick="setTransferPatient(${p.id},'${p.first_name} ${p.last_name}','${p.patient_id ?? ''}')" style="background:#2563eb;color:white;border:none;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;font-family:inherit;">Select</button>
      </div>
    `).join('');
  })
  .catch(() => { results.innerHTML = '<div style="padding:10px;font-size:13px;color:#dc2626;">Search failed.</div>'; });
}
function setTransferPatient(id, name, patientId) {
  document.getElementById('transfer-patient-id').value = id;
  document.getElementById('transfer-patient-name').textContent = name;
  document.getElementById('transfer-patient-sub').textContent = patientId;
  document.getElementById('transfer-patient-avatar').textContent = name.charAt(0);
  document.getElementById('transfer-search-results').innerHTML = '';
  document.getElementById('transfer-search-input').style.display = 'none';
  document.getElementById('transfer-selected-patient').style.display = 'flex';
}
function clearTransferPatient() {
  document.getElementById('transfer-patient-id').value = '';
  document.getElementById('transfer-search-input').style.display = 'block';
  document.getElementById('transfer-search-input').value = '';
  document.getElementById('transfer-selected-patient').style.display = 'none';
}
function showHospitalDropdown() {
  document.getElementById('hospital-dropdown').style.display = 'block';
}
function filterHospitals() {
  var query = document.getElementById('hospital-search-input').value.toLowerCase();
  document.querySelectorAll('#hospital-dropdown > div').forEach(function(item) {
    var name = item.querySelector('div div').textContent.toLowerCase();
    item.style.display = name.includes(query) ? 'flex' : 'none';
  });
  document.getElementById('hospital-dropdown').style.display = 'block';
}
function selectHospital(id, name) {
  document.getElementById('selected-hospital-id').value = id;
  document.getElementById('hospital-search-input').value = name;
  document.getElementById('hospital-dropdown').style.display = 'none';
  document.getElementById('hospital-search-input').style.borderColor = '#16a34a';
}
function setPriority(type) {
  ['routine','urgent','emergency'].forEach(function(t) {
    var el = document.getElementById('priority-' + t);
    el.style.borderColor = '#e2e8f0';
    el.style.background = 'white';
  });
  var sel = document.getElementById('priority-' + type);
  sel.style.borderColor = '#2563eb';
  sel.style.background = '#f0f6ff';
}
document.addEventListener('click', function(e) {
  if(!e.target.closest('#hospital-search-input') && !e.target.closest('#hospital-dropdown')) {
    var dd = document.getElementById('hospital-dropdown');
    if(dd) dd.style.display = 'none';
  }
});
document.getElementById('transfer-search-input').addEventListener('keypress', function(e) {
  if(e.key === 'Enter') { e.preventDefault(); searchTransferPatient(); }
});
</script>
      </div>

      <!-- Working Hours -->
      <div id="sec-working-hours-section" class="section" style="display:none;background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
          <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Working Hours</span>
          <span style="font-size:12px;color:#2563eb;cursor:pointer;font-weight:500;" onclick="document.getElementById('edit-hours').style.display=document.getElementById('edit-hours').style.display==='none'?'block':'none'">Edit</span>
        </div>
        @php
          $wh = is_string(optional($facility)->working_hours) ? json_decode($facility->working_hours, true) : (optional($facility)->working_hours ?? []);
        @endphp
        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d)
        <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px;">
          <span style="color:#64748b;">{{ $d }}</span>
          <span style="font-weight:600;color:#0f172a;">{{ $wh[$d] ?? 'Not set' }}</span>
        </div>
        @endforeach
        <div id="edit-hours" style="display:none;margin-top:16px;border-top:1px solid #f1f5f9;padding-top:16px;">
  <form method="POST" action="{{ route('hospital.hours.update') }}">
    @csrf
    @php
      $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
      $wh = is_string(optional($facility)->working_hours) ? json_decode($facility->working_hours, true) : (optional($facility)->working_hours ?? []);
    @endphp
    @foreach($days as $day)
    @php
      $current = $wh[$day] ?? 'Not set';
      $isClosed = $current === 'Closed';
      $is24 = $current === 'Open 24 Hours';
      $parts = (!$isClosed && !$is24 && str_contains($current, ' - ')) ? explode(' - ', $current) : ['8:00 AM', '6:00 PM'];
    @endphp
    <div style="margin-bottom:12px;padding:10px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span style="font-size:13px;font-weight:600;color:#0f172a;">{{ $day }}</span>
        <div style="display:flex;gap:12px;align-items:center;">
          <label style="display:flex;align-items:center;gap:4px;font-size:11px;color:#64748b;cursor:pointer;">
            <input type="checkbox" name="allday_{{ $day }}" value="1" {{ $is24 ? 'checked' : '' }} onchange="toggleDay('{{ $day }}', this, 'allday')"> 24hrs
          </label>
          <label style="display:flex;align-items:center;gap:4px;font-size:11px;color:#64748b;cursor:pointer;">
            <input type="checkbox" name="closed_{{ $day }}" value="1" {{ $isClosed ? 'checked' : '' }} onchange="toggleDay('{{ $day }}', this, 'closed')"> Closed
          </label>
        </div>
      </div>
      <div id="times-{{ $day }}" style="display:{{ ($isClosed || $is24) ? 'none' : 'flex' }};gap:8px;align-items:center;">
        <select name="open_{{ $day }}" style="flex:1;background:white;border:1px solid #e2e8f0;border-radius:6px;padding:6px 8px;font-size:12px;font-family:inherit;">
          @foreach(['12:00 AM','1:00 AM','2:00 AM','3:00 AM','4:00 AM','5:00 AM','6:00 AM','7:00 AM','8:00 AM','9:00 AM','10:00 AM','11:00 AM','12:00 PM','1:00 PM','2:00 PM','3:00 PM','4:00 PM','5:00 PM','6:00 PM','7:00 PM','8:00 PM','9:00 PM','10:00 PM','11:00 PM'] as $t)
          <option value="{{ $t }}" {{ $parts[0] === $t ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
        <span style="font-size:11px;color:#94a3b8;">to</span>
        <select name="close_{{ $day }}" style="flex:1;background:white;border:1px solid #e2e8f0;border-radius:6px;padding:6px 8px;font-size:12px;font-family:inherit;">
          @foreach(['12:00 AM','1:00 AM','2:00 AM','3:00 AM','4:00 AM','5:00 AM','6:00 AM','7:00 AM','8:00 AM','9:00 AM','10:00 AM','11:00 AM','12:00 PM','1:00 PM','2:00 PM','3:00 PM','4:00 PM','5:00 PM','6:00 PM','7:00 PM','8:00 PM','9:00 PM','10:00 PM','11:00 PM'] as $t)
          <option value="{{ $t }}" {{ isset($parts[1]) && $parts[1] === $t ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
      </div>
    </div>
    @endforeach
    <button type="submit" style="width:100%;background:#2563eb;color:white;border:none;padding:10px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Save Working Hours</button>
  </form>
  <script>
  function toggleDay(day, cb, type) {
    var times = document.getElementById('times-' + day);
    var allday = document.querySelector('[name=allday_' + day + ']');
    var closed = document.querySelector('[name=closed_' + day + ']');
    if(type === 'allday' && cb.checked && closed) closed.checked = false;
    if(type === 'closed' && cb.checked && allday) allday.checked = false;
    var hide = (allday && allday.checked) || (closed && closed.checked);
    times.style.display = hide ? 'none' : 'flex';
  }
  </script>
</div>
      </div>
    </div>

    <!-- SETTINGS SECTION -->
    <div id="sec-settings" class="section" style="display:none;">
      <!-- Profile Settings -->
      <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
          <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Profile Settings</span>
        </div>
        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('PUT')
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
            <div>
              <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">First Name</label>
              <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
            </div>
            <div>
              <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Last Name</label>
              <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
            </div>
          </div>
          <div style="margin-bottom:12px;">
            <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Email</label>
            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
          </div>
          <div style="margin-bottom:12px;">
            <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
          </div>
          <button type="submit" style="background:#2563eb;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Update Profile</button>
        </form>
      </div>

      <!-- Change Password -->
      <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
          <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Change Password</span>
        </div>
        <form method="POST" action="{{ route('settings.password') }}">
          @csrf
          @method('PUT')
          <div style="margin-bottom:12px;">
            <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Current Password</label>
            <input type="password" name="current_password" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
            <div>
              <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">New Password</label>
              <input type="password" name="password" required minlength="8" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
            </div>
            <div>
              <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Confirm Password</label>
              <input type="password" name="password_confirmation" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
            </div>
          </div>
          <button type="submit" style="background:#2563eb;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Change Password</button>
        </form>
      </div>
    </div>

  </div>
</div>
</div>

<!-- NEARBY HOSPITALS MODAL -->
<div id="nearby-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.6);z-index:500;align-items:center;justify-content:center;">
  <div style="background:white;border-radius:16px;padding:28px;width:700px;max-height:80vh;box-shadow:0 24px 60px rgba(0,0,0,.25);overflow-y:auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
      <div style="font-size:16px;font-weight:700;color:#0f172a;">📍 Nearby Hospitals</div>
      <button onclick="closeNearbyModal()" style="background:#f1f5f9;border:none;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:13px;color:#64748b;font-family:inherit;">✕</button>
    </div>
    <div id="nearby-loading" style="text-align:center;padding:40px;color:#94a3b8;">
      <div style="font-size:14px;">Getting your location...</div>
      <div style="font-size:12px;margin-top:8px;">Please allow location access when prompted</div>
    </div>
    <div id="nearby-content" style="display:none;">
      <div id="nearby-map" style="width:100%;height:300px;border-radius:10px;margin-bottom:16px;border:1px solid #e2e8f0;"></div>
      <div id="nearby-list"></div>
    </div>
    <div id="nearby-error" style="display:none;text-align:center;padding:20px;">
      <div style="font-size:14px;font-weight:600;color:#dc2626;margin-bottom:8px;">Unable to get location automatically</div>
      <div style="font-size:12px;color:#64748b;margin-bottom:16px;">Please select your location on the map below</div>
      <div id="location-picker-map" style="width:100%;height:300px;border-radius:10px;border:1px solid #e2e8f0;margin-bottom:12px;"></div>
      <div style="font-size:11px;color:#94a3b8;">Click on the map to set your location, then click "Search Nearby"</div>
      <button onclick="searchFromMap()" style="margin-top:12px;background:#2563eb;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Search Nearby</button>
    </div>
  </div>
</div>

<!-- REJECT MODAL -->
<div id="reject-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.6);z-index:500;align-items:center;justify-content:center;">
  <div style="background:white;border-radius:16px;padding:28px;width:440px;box-shadow:0 24px 60px rgba(0,0,0,.25);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
      <div style="font-size:16px;font-weight:700;color:#0f172a;">Reject Referral</div>
      <button onclick="closeRejectModal()" style="background:#f1f5f9;border:none;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:13px;color:#64748b;font-family:inherit;">✕</button>
    </div>
    <div id="reject-patient-info" style="background:#fee2e2;border:1px solid #fecaca;border-radius:8px;padding:12px;margin-bottom:16px;">
      <div style="font-size:12px;font-weight:600;color:#dc2626;margin-bottom:2px;">Rejecting referral for <span id="reject-patient-name"></span></div>
      <div style="font-size:11px;color:#b91c1c;">The referring doctor and patient will be notified with your reason.</div>
    </div>
    <form id="reject-form" method="POST">
      @method('PATCH')
      @csrf
      <input type="hidden" name="status" value="rejected">
      <div style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em;">Reason for Rejection <span style="color:#dc2626;">*</span></div>
      <textarea name="rejection_reason" required placeholder="e.g. No available beds. Please refer to Nairobi Hospital instead..." style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 12px;font-size:13px;font-family:'Inter',sans-serif;outline:none;resize:vertical;min-height:90px;margin-bottom:6px;"></textarea>
      <div style="font-size:11px;color:#94a3b8;margin-bottom:16px;">This reason will be visible to the referring doctor and patient.</div>
      <div style="display:flex;gap:10px;">
        <button type="button" onclick="closeRejectModal()" style="flex:1;background:white;color:#64748b;border:1.5px solid #e2e8f0;padding:11px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Cancel</button>
        <button type="submit" style="flex:1;background:#dc2626;color:white;border:none;padding:11px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Confirm Rejection</button>
      </div>
    </form>
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

// Nearby Hospitals Functions
function findNearbyHospitals() {
  document.getElementById('nearby-modal').style.display = 'flex';
  document.getElementById('nearby-loading').style.display = 'block';
  document.getElementById('nearby-content').style.display = 'none';
  document.getElementById('nearby-error').style.display = 'none';

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        loadNearbyHospitals(lat, lng);
      },
      function(error) {
        document.getElementById('nearby-loading').style.display = 'none';
        document.getElementById('nearby-error').style.display = 'block';
        // Initialize location picker map
        setTimeout(initLocationPicker, 100);
      }
    );
  } else {
    document.getElementById('nearby-loading').style.display = 'none';
    document.getElementById('nearby-error').style.display = 'block';
    // Initialize location picker map
    setTimeout(initLocationPicker, 100);
  }
}

function loadNearbyHospitals(lat, lng) {
  fetch('/facility/nearby?latitude=' + lat + '&longitude=' + lng)
    .then(response => response.json())
    .then(data => {
      document.getElementById('nearby-loading').style.display = 'none';
      document.getElementById('nearby-content').style.display = 'block';

      if (data.success && data.data.length > 0) {
        initMap(lat, lng, data.data);
        renderHospitalList(data.data);
      } else {
        document.getElementById('nearby-list').innerHTML = '<div style="text-align:center;padding:20px;color:#94a3b8;">No nearby hospitals found</div>';
      }
    })
    .catch(error => {
      document.getElementById('nearby-loading').style.display = 'none';
      document.getElementById('nearby-error').style.display = 'block';
    });
}

function initMap(userLat, userLng, hospitals) {
  var mapDiv = document.getElementById('nearby-map');

  // Check if Google Maps is available
  if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
    mapDiv.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;background:#f8fafc;border-radius:10px;"><div style="text-align:center;padding:20px;"><div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:8px;">Google Maps Not Available</div><div style="font-size:12px;color:#64748b;">Please configure GOOGLE_MAPS_API_KEY in your .env file</div></div></div>';
    return;
  }

  var map = new google.maps.Map(mapDiv, {
    center: {lat: userLat, lng: userLng},
    zoom: 12
  });

  // Add user marker
  new google.maps.Marker({
    position: {lat: userLat, lng: userLng},
    map: map,
    title: 'Your Location',
    icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
  });

  // Add hospital markers
  hospitals.forEach(function(hospital) {
    var marker = new google.maps.Marker({
      position: {lat: parseFloat(hospital.latitude), lng: parseFloat(hospital.longitude)},
      map: map,
      title: hospital.name,
      icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
    });

    var infoWindow = new google.maps.InfoWindow({
      content: '<div style="padding:8px;"><strong>' + hospital.name + '</strong><br>' + hospital.distance + ' km away</div>'
    });

    marker.addListener('click', function() {
      infoWindow.open(map, marker);
    });
  });
}

function renderHospitalList(hospitals) {
  var listDiv = document.getElementById('nearby-list');
  var html = '<div style="font-size:13px;font-weight:600;color:#0f172a;margin-bottom:12px;">Select a hospital:</div>';

  hospitals.forEach(function(hospital) {
    html += '<div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:8px;cursor:pointer;" onclick="selectHospital(' + hospital.id + ', \'' + hospital.name.replace(/'/g, "\\'") + '\')">'  ;
    html += '<div>';
    html += '<div style="font-size:13px;font-weight:600;color:#0f172a;">' + hospital.name + '</div>';
    html += '<div style="font-size:11px;color:#64748b;">' + hospital.county + ' · ' + hospital.distance + ' km away</div>';
    html += '</div>';
    html += '<button type="button" style="background:#2563eb;color:white;border:none;padding:6px 12px;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;">Select</button>';
    html += '</div>';
  });

  listDiv.innerHTML = html;
}

function selectHospital(id, name) {
  document.getElementById('receiving_facility_id').value = id;
  closeNearbyModal();
}

function closeNearbyModal() {
  document.getElementById('nearby-modal').style.display = 'none';
}

var locationPickerMap = null;
var locationMarker = null;
var selectedLat = null;
var selectedLng = null;

function initLocationPicker() {
  var mapDiv = document.getElementById('location-picker-map');
  if (!mapDiv) return;

  // Check if Google Maps is available
  if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
    mapDiv.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;background:#f8fafc;border-radius:10px;"><div style="text-align:center;padding:20px;"><div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:8px;">Google Maps Not Available</div><div style="font-size:12px;color:#64748b;">Please configure GOOGLE_MAPS_API_KEY in your .env file</div></div></div>';
    return;
  }

  // Default to Nairobi coordinates
  var defaultLat = -1.2921;
  var defaultLng = 36.8219;

  locationPickerMap = new google.maps.Map(mapDiv, {
    center: {lat: defaultLat, lng: defaultLng},
    zoom: 12
  });

  // Add click listener to set location
  locationPickerMap.addListener('click', function(event) {
    selectedLat = event.latLng.lat();
    selectedLng = event.latLng.lng();

    // Remove existing marker
    if (locationMarker) {
      locationMarker.setMap(null);
    }

    // Add new marker
    locationMarker = new google.maps.Marker({
      position: {lat: selectedLat, lng: selectedLng},
      map: locationPickerMap,
      title: 'Your Location',
      icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
    });
  });
}

function searchFromMap() {
  if (selectedLat === null || selectedLng === null) {
    alert('Please click on the map to select your location first');
    return;
  }

  document.getElementById('nearby-loading').style.display = 'block';
  document.getElementById('nearby-error').style.display = 'none';
  loadNearbyHospitals(selectedLat, selectedLng);
}
</script>
<script>
function showSection(name, el) {
  document.querySelectorAll('.section').forEach(function(s){s.style.display='none';});
  document.querySelectorAll('.slink').forEach(function(l){l.classList.remove('on');});
  var sec = document.getElementById('sec-'+name);
  if(sec){sec.style.display='block';}
  if(el){el.classList.add('on');}
  window.scrollTo({top:0,behavior:'smooth'});
  localStorage.setItem('hosp-sec',name);
}
function filterReferrals(status,el){
  document.querySelectorAll('.ref-item').forEach(function(item){
    item.style.display=(status==='all'||item.classList.contains('ref-'+status))?'flex':'none';
  });
  document.querySelectorAll('.filter-btn').forEach(function(b){
    b.style.background='white';
    b.style.color='#64748b';
    b.style.border='1.5px solid #e2e8f0';
  });
  el.style.background='#2563eb';
  el.style.color='white';
  el.style.border='none';
}
function showReportTab(tab, el) {
  document.getElementById('report-incoming').style.display = tab === 'incoming' ? 'block' : 'none';
  document.getElementById('report-outgoing').style.display = tab === 'outgoing' ? 'block' : 'none';
  document.getElementById('tab-incoming').style.background = tab === 'incoming' ? '#2563eb' : 'white';
  document.getElementById('tab-incoming').style.color = tab === 'incoming' ? 'white' : '#2563eb';
  document.getElementById('tab-incoming').style.border = tab === 'incoming' ? 'none' : '1.5px solid #2563eb';
  document.getElementById('tab-outgoing').style.background = tab === 'outgoing' ? '#2563eb' : 'white';
  document.getElementById('tab-outgoing').style.color = tab === 'outgoing' ? 'white' : '#2563eb';
  document.getElementById('tab-outgoing').style.border = tab === 'outgoing' ? 'none' : '1.5px solid #2563eb';
}
window.addEventListener('DOMContentLoaded',function(){
  window.location.hash = '';
  var section = 'dashboard';
  document.querySelectorAll('.section').forEach(function(s){s.style.display='none';});
  var sec=document.getElementById('sec-'+section);
  if(sec){sec.style.display='block';}
  var el=document.querySelector('[onclick*="'+section+'"]');
  if(el){el.classList.add('on');}
});
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
<script>
function openRejectModal(referralId, patientName) {
  document.getElementById('reject-patient-name').textContent = patientName;
  document.getElementById('reject-form').action = '/referrals/' + referralId + '/status';
  document.getElementById('reject-modal').style.display = 'flex';
}
function closeRejectModal() {
  document.getElementById('reject-modal').style.display = 'none';
}
</script>
<script>
function toggleNotifications(){
  var d = document.getElementById('notifications-dropdown');
  d.style.display = d.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e){
  var dropdown = document.getElementById('notifications-dropdown');
  if(dropdown && !e.target.closest('[onclick="toggleNotifications()"]') && !e.target.closest('#notifications-dropdown')){
    dropdown.style.display = 'none';
  }
});
</script>

</body>
</html>
