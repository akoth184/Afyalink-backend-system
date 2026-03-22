<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Doctor Dashboard — AfyaLink</title>
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
</style>
</head>
<body style="background:#f0f6ff;font-family:'Inter',sans-serif;">
<div style="display:flex;min-height:100vh;">

<!-- SIDEBAR -->
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;overflow-y:auto;">
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
<div style="margin-left:220px;flex:1;">

  <!-- TOPBAR -->
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
    <div>
      <div style="font-size:20px;font-weight:700;color:#0f172a;">Dashboard</div>
      <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Welcome back, Dr. {{ Auth::user()->first_name ?? '' }}</div>
    </div>
    <div style="display:flex;gap:8px;">
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
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Patients Today</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['patients_today'] ?? 0 }}</div>
        <div style="font-size:11px;color:#16a34a;margin-top:5px;">Registered today</div>
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
      <div style="display:flex;gap:8px;margin-bottom:12px;">
        <input type="text" id="searchInput" placeholder="Search by name or Patient ID..." style="flex:1;padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;font-family:inherit;background:#f8fafc;outline:none;">
        <button onclick="doSearch()" style="background:#2563eb;color:white;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Search</button>
      </div>
      <div id="searchResults"></div>
    </div>

  </div>
</div>
</div>

<script>
function doSearch() {
  var query = document.getElementById('searchInput').value;
  if(!query) return;
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '/patients/search?q=' + encodeURIComponent(query));
  xhr.onload = function() {
    if(xhr.status === 200) {
      document.getElementById('searchResults').innerHTML = xhr.responseText;
    }
  };
  xhr.send();
}
document.getElementById('searchInput').addEventListener('keypress', function(e) {
  if(e.key === 'Enter') doSearch();
});
</script>
</body>
</html>
