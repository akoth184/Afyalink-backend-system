<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
.badge-accepted{background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-rejected{background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.stat-card{background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;transition:all .15s;cursor:pointer;}
.stat-card:hover{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.card{background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;}
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
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Patient Portal</div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->first_name ?? 'P', 0, 1)) }}</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">{{ Auth::user()->patient_id ?? 'Patient' }}</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Health</div>
    <a href="{{ route('patient.dashboard') }}" class="slink on">Dashboard</a>
    <a href="{{ route('patient.records') }}" class="slink">Medical Records</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Referrals</div>
    <a href="{{ route('patient.referrals') }}" class="slink">My Referrals</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Payments</div>
    <a href="{{ route('patient.payments') }}" class="slink">M-PESA Payments</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Explore</div>
    <a href="{{ route('patient.nearby-hospitals') }}" class="slink">Nearby Hospitals</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Account</div>
    <a href="{{ route('profile') }}" class="slink">Profile</a>
    <a href="{{ route('profile') }}" class="slink">Settings</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Logout</button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<div id="main-content" style="margin-left:220px;flex:1;">
  <!-- TOPBAR -->
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
    <div>
      <div style="font-size:20px;font-weight:700;color:#0f172a;">Dashboard</div>
      <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Welcome back, {{ Auth::user()->first_name ?? 'Patient' }}</div>
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
    </div>
  </div>

  <!-- BANNER -->
  <div style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);padding:28px 32px;display:flex;align-items:center;justify-content:space-between;">
    <div>
      <div style="font-size:11px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">{{ now()->format('l, d F Y') }}</div>
      <div style="font-size:26px;font-weight:700;color:white;margin-bottom:6px;">Welcome back, {{ Auth::user()->first_name ?? 'Patient' }} 👋</div>
      <div style="font-size:13px;color:rgba(255,255,255,.7);">Here's your health summary and today's updates</div>
      <div style="display:inline-block;background:rgba(255,255,255,.15);color:white;padding:4px 12px;border-radius:20px;font-size:11px;margin-top:8px;">Patient ID: {{ Auth::user()->patient_id ?? 'N/A' }}</div>
      <div style="display:inline-block;background:rgba(255,255,255,.15);color:white;padding:4px 12px;border-radius:20px;font-size:11px;margin-top:8px;margin-left:8px;">Gender: {{ Auth::user()->gender ?? 'Not set' }}</div>
    </div>
    <a href="#" style="background:white;color:#2563eb;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;flex-shrink:0;">Make a Payment</a>
  </div>

  <!-- SUCCESS MESSAGE -->
  @if(session('success'))
  <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 28px;font-size:13px;font-weight:500;">✓ {{ session('success') }}</div>
  @endif

  <div style="padding:24px 28px;">
    <!-- STAT CARDS -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
      <a href="#" style="text-decoration:none;">
        <div class="stat-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <div style="width:36px;height:36px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;"></div>
            <span style="font-size:11px;color:#2563eb;">Your records</span>
          </div>
          <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_records'] ?? 0 }}</div>
          <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;">Medical Records</div>
        </div>
      </a>
      <a href="{{ route('patient.referrals') }}" style="text-decoration:none;">
        <div class="stat-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <div style="width:36px;height:36px;border-radius:8px;background:#fef3c7;display:flex;align-items:center;justify-content:center;"></div>
            <span style="font-size:11px;color:#d97706;">{{ \App\Models\Referral::where('patient_id', Auth::id())->count() }} total</span>
          </div>
          <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Referral::where('patient_id', Auth::id())->count() }}</div>
          <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;">My Referrals</div>
        </div>
      </a>
      <a href="{{ route('patient.payments') }}" style="text-decoration:none;">
  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
      <div style="width:36px;height:36px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
        <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span style="font-size:11px;color:#16a34a;">M-PESA</span>
    </div>
    <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Payment::where('patient_id', Auth::id())->where('status','completed')->count() }}</div>
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;">My Payments</div>
  </div>
</a>
      <a href="{{ route('patient.nearby-hospitals') }}" style="text-decoration:none;">
  <div class="stat-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
      <div style="width:36px;height:36px;border-radius:8px;background:#fef3c7;display:flex;align-items:center;justify-content:center;">
        <svg width="18" height="18" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
      <span style="font-size:11px;color:#d97706;">Near you</span>
    </div>
    <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ \App\Models\Facility::where('is_active',true)->count() }}</div>
    <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;">Nearby Hospitals</div>
  </div>
</a>
    </div>

    <!-- TWO COLUMNS -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
      <!-- MY REFERRALS -->
      <div class="card" style="margin-bottom:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>My Referrals</span>
          <a href="{{ route('patient.referrals') }}" style="font-size:12px;color:#2563eb;font-weight:500;text-decoration:none;">View all →</a>
        </div>
        @forelse(\App\Models\Referral::where('patient_id', Auth::id())->with(['referringFacility','receivingFacility'])->latest()->take(4)->get() as $referral)
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
          <div style="flex:1;">
            <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->receivingFacility)->name ?? 'N/A' }}</div>
            <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $referral->reason ?? 'No reason' }} · {{ $referral->created_at->format('d M') }}</div>
          </div>
          <span class="badge-{{ $referral->status ?? 'pending' }}">{{ ucfirst($referral->status ?? 'pending') }}</span>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No referrals yet</div>
        @endforelse
      </div>

      <!-- NEARBY HOSPITALS -->
      <div class="card" style="margin-bottom:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Nearby Hospitals</span>
          <a href="{{ route('patient.nearby-hospitals') }}" style="font-size:12px;color:#2563eb;font-weight:500;text-decoration:none;">Open map →</a>
        </div>
        <div style="background:#dbeafe;border-radius:8px;height:90px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;border:1px dashed #93c5fd;">
          <a href="{{ route('patient.nearby-hospitals') }}" style="font-size:12px;color:#1d4ed8;font-weight:600;text-decoration:none;">🗺 Click to open Google Maps</a>
        </div>
        @foreach(\App\Models\Facility::where('is_active',true)->take(3)->get() as $facility)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f1f5f9;">
          <div>
            <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $facility->name }}</div>
            <div style="font-size:11px;color:#94a3b8;">{{ $facility->county }} · {{ ucfirst($facility->type) }}</div>
          </div>
          <span style="font-size:11px;color:#2563eb;background:#dbeafe;padding:3px 10px;border-radius:20px;font-weight:600;">Active</span>
        </div>
        @endforeach
      </div>
    </div>

    <!-- DOWNLOAD BANNER -->
    <div style="background:linear-gradient(135deg,#1e3a5f,#2563eb);border-radius:10px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;border:none;">
      <div>
        <div style="font-size:15px;font-weight:700;color:white;margin-bottom:4px;">Download Your Medical History</div>
        <div style="font-size:12px;color:rgba(255,255,255,.7);">Get a complete PDF of your health records and referral history</div>
      </div>
      <a href="{{ route('patient.records') }}" style="background:white;color:#2563eb;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;flex-shrink:0;margin-left:20px;">View My Records</a>
    </div>
  </div>
</div>
</div>
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
