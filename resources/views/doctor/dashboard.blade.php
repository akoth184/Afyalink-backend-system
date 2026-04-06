<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Doctor Dashboard — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;transition:all .15s;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
.badge-accepted{background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-rejected{background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.card{background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;}
.stat-card{background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;}
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
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Doctor Portal</div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->first_name ?? 'D', 0, 1)) }}</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">Dr. {{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">{{ Auth::user()->specialization ?? 'General Practice' }}</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Main</div>
    <a href="{{ route('doctor.dashboard') }}" class="slink on">Dashboard</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Patients</div>
    <a href="{{ route('patients.index') }}" class="slink">My Patients</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Clinical</div>
    <a href="{{ route('referrals.create') }}" class="slink">Create Referral</a>
    <a href="{{ route('referrals.index') }}" class="slink">My Referrals</a>
    <a href="{{ route('records.index') }}" class="slink">Medical Records</a>
    <a href="{{ route('lab-tests.index') }}" class="slink">Lab Tests</a>
    <a href="{{ route('appointments.index') }}" class="slink">My Appointments</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Tools</div>
    <a href="{{ route('patient.nearby-hospitals') }}" class="slink">Nearby Hospitals</a>
    <a href="{{ route('facilities.index') }}" class="slink">Facilities</a>
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
      <div style="font-size:20px;font-weight:700;color:#0f172a;">Dashboard</div>
      <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Welcome back, Dr. {{ Auth::user()->first_name ?? '' }}</div>
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
      <a href="{{ route('records.create') }}" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">+ New Record</a>
      <a href="{{ route('referrals.create') }}" style="background:#2563eb;color:white;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">+ New Referral</a>
    </div>
  </div>

  <!-- SUCCESS MESSAGE -->
  @if(session('success'))
  <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 28px;font-size:13px;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
  @endif

  <!-- CONTENT -->
  <div style="padding:24px 28px;">

    <!-- STATS -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
      <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
          <div style="width:36px;height:36px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
            <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          </div>
          <span style="font-size:11px;color:#16a34a;">Today</span>
        </div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Referral::where('referred_by', Auth::id())->whereDate('created_at', today())->count() }}</div>
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;">Referrals Today</div>
      </div>
      <div class="stat-card">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Patients</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_patients'] ?? 0 }}</div>
        <div style="font-size:11px;color:#2563eb;margin-top:5px;">In system</div>
      </div>
      <div class="stat-card">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Pending Referrals</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['pending_referrals'] ?? 0 }}</div>
        <div style="font-size:11px;color:#d97706;margin-top:5px;">Awaiting response</div>
      </div>
      <div class="stat-card">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Medical Records</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_records'] ?? 0 }}</div>
        <div style="font-size:11px;color:#2563eb;margin-top:5px;">Created by you</div>
      </div>
    </div>

    <!-- TWO COLUMN ROW -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

      <!-- RECENT PATIENTS -->
      <div class="card" style="margin-bottom:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Recent Patients</span>
          <a href="{{ route('patients.index') }}" style="font-size:12px;color:#2563eb;font-weight:500;text-decoration:none;">View all →</a>
        </div>
        @forelse($recentPatients ?? [] as $patient)
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
          <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($patient->first_name ?? 'P', 0, 1)) }}</div>
          <div style="flex:1;">
            <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $patient->first_name ?? '' }} {{ $patient->last_name ?? '' }}</div>
            <div style="font-size:11px;color:#94a3b8;">{{ $patient->patient_id ?? '' }} · {{ $patient->email ?? '' }}</div>
          </div>
          <a href="{{ route('patients.show', $patient->id) }}" style="background:#2563eb;color:white;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">View</a>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No patients yet</div>
        @endforelse
      </div>

      <!-- RECENT REFERRALS -->
      <div class="card" style="margin-bottom:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Recent Referrals</span>
          <a href="{{ route('referrals.index') }}" style="font-size:12px;color:#2563eb;font-weight:500;text-decoration:none;">View all →</a>
        </div>
        @forelse(\App\Models\Referral::where('referred_by', Auth::id())->with(['patient','receivingFacility'])->latest()->take(4)->get() as $referral)
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
          <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(optional($referral->patient)->first_name ?? 'P', 0, 1)) }}</div>
          <div style="flex:1;">
            <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</div>
            <div style="font-size:11px;color:#94a3b8;">→ {{ optional($referral->receivingFacility)->name ?? 'N/A' }} · {{ $referral->reason ?? '' }}</div>
          </div>
          <span class="badge-{{ $referral->status ?? 'pending' }}">{{ ucfirst($referral->status ?? 'pending') }}</span>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No referrals yet</div>
        @endforelse
      </div>
    </div>

    <!-- QUICK SEARCH -->
    <div class="card">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Quick Patient Search</div>
      <div style="display:flex;gap:8px;margin-bottom:16px;">
        <input type="text" id="patient-search-input" placeholder="Search by name or Patient ID..." style="flex:1;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:13px;font-family:inherit;outline:none;">
        <button onclick="searchPatients()" style="background:#2563eb;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Search</button>
      </div>
      <div id="search-results"></div>
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
function searchPatients() {
  var query = document.getElementById('patient-search-input').value.trim();
  if(!query) return;
  var results = document.getElementById('search-results');
  results.innerHTML = '<div style="padding:16px;text-align:center;color:#64748b;font-size:13px;">Searching...</div>';
  fetch('/patient/search?query=' + encodeURIComponent(query), {
    headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
  })
  .then(r => r.json())
  .then(data => {
    if(!data.length){
      results.innerHTML = '<div style="padding:16px;text-align:center;color:#94a3b8;font-size:13px;">No patients found for "' + query + '"</div>';
      return;
    }
    results.innerHTML = data.map(p => `
      <div style="display:flex;align-items:center;gap:10px;padding:12px;background:white;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:8px;">
        <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;">${p.first_name.charAt(0)}</div>
        <div style="flex:1;">
          <div style="font-size:13px;font-weight:600;color:#0f172a;">${p.first_name} ${p.last_name}</div>
          <div style="font-size:11px;color:#94a3b8;">${p.patient_id ?? 'N/A'} · ${p.email}</div>
        </div>
        <a href="/patients/${p.id}" style="background:#2563eb;color:white;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">View</a>
      </div>
    `).join('');
  })
  .catch(() => {
    results.innerHTML = '<div style="padding:16px;text-align:center;color:#dc2626;font-size:13px;">Search failed. Try again.</div>';
  });
}
document.addEventListener('DOMContentLoaded', function() {
  var input = document.getElementById('patient-search-input');
  if(input) input.addEventListener('keypress', function(e){ if(e.key==='Enter') searchPatients(); });
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
