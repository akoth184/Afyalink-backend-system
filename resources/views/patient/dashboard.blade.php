<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard — AfyaLink</title>
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
</style>
</head>
<body style="background:#f0f6ff;font-family:'Inter',sans-serif;">
<div style="display:flex;min-height:100vh;">

<!-- SIDEBAR -->
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
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
<div style="margin-left:220px;flex:1;">
  <!-- BANNER -->
  <div style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);padding:28px 32px;display:flex;align-items:center;justify-content:space-between;">
    <div>
      <div style="font-size:11px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">{{ now()->format('l, d F Y') }}</div>
      <div style="font-size:26px;font-weight:700;color:white;margin-bottom:6px;">Welcome back, {{ Auth::user()->first_name ?? 'Patient' }} 👋</div>
      <div style="font-size:13px;color:rgba(255,255,255,.7);">Here's your health summary and today's updates</div>
      <div style="display:inline-block;background:rgba(255,255,255,.15);color:white;padding:4px 12px;border-radius:20px;font-size:11px;margin-top:8px;">Patient ID: {{ Auth::user()->patient_id ?? 'N/A' }}</div>
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
            <span style="font-size:11px;color:#d97706;">{{ $my_referrals ?? 0 }} total</span>
          </div>
          <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $my_referrals ?? 0 }}</div>
          <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;">My Referrals</div>
        </div>
      </a>
      <a href="{{ route('patient.nearby-hospitals') }}" style="text-decoration:none;">
        <div class="stat-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <div style="width:36px;height:36px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;"></div>
            <span style="font-size:11px;color:#16a34a;">Nearby</span>
          </div>
          <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_facilities'] ?? 7 }}</div>
          <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;">Available Hospitals</div>
        </div>
      </a>
      <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
          <div style="width:36px;height:36px;border-radius:8px;background:#fce7f3;display:flex;align-items:center;justify-content:center;"></div>
          <span style="font-size:11px;color:#2563eb;">System-wide</span>
        </div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_doctors'] ?? 0 }}</div>
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;">Total Doctors</div>
      </div>
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
      <a href="#" onclick="alert('Download feature coming soon!')" style="background:white;color:#2563eb;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;flex-shrink:0;margin-left:20px;">Download PDF</a>
    </div>
  </div>
</div>
</div>
</body>
</html>
