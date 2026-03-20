<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hospital Dashboard — AfyaLink</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;cursor:pointer;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
.section{display:none;}
.section.active{display:block;}
.badge-accepted{background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-rejected{background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.card{background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;}
</style>
</head>
<body style="background:#f0f6ff;">
<div style="display:flex;min-height:100vh;">
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);">
    <div style="font-size:16px;font-weight:700;color:white;">AfyaLink</div>
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Hospital Portal</div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:34px;height:34px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">{{ strtoupper(substr(Auth::user()->first_name ?? 'H', 0, 1)) }}</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">{{ Auth::user()->first_name ?? 'Hospital' }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">{{ optional($facility)->name ?? 'Hospital' }}</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Main</div>
    <div class="slink on" onclick="showSection('dashboard',this)">Dashboard</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Referrals</div>
    <div class="slink" onclick="showSection('referrals',this)">Incoming Referrals</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Records</div>
    <div class="slink" onclick="showSection('records',this)">Medical Records</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Tools</div>
    <div class="slink" onclick="showSection('facilities',this)">Nearby Facilities</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Account</div>
    <div class="slink" onclick="showSection('hours',this)">Working Hours</div>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Sign Out</button>
    </form>
  </div>
</aside>
<div style="flex:1;overflow:auto;" id="main-content">
<div id="sec-dashboard" class="section active">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
    <div><div style="font-size:20px;font-weight:700;color:#0f172a;">Hospital Dashboard</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">{{ optional($facility)->name ?? 'Hospital' }} — {{ optional($facility)->county ?? '' }} County</div></div>
  </div>
  <div style="padding:24px 28px;">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
      <div style="background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Patients</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['total_patients'] ?? 0 }}</div><div style="font-size:11px;color:#2563eb;margin-top:5px;">Referred in</div></div>
      <div style="background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Pending Referrals</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['pending_referrals'] ?? 0 }}</div><div style="font-size:11px;color:#d97706;margin-top:5px;">Need action</div></div>
      <div style="background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Accepted</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['accepted_referrals'] ?? 0 }}</div><div style="font-size:11px;color:#16a34a;margin-top:5px;">This week</div></div>
      <div style="background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;"><div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Staff Members</div><div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['staff_count'] ?? 1 }}</div><div style="font-size:11px;color:#2563eb;margin-top:5px;">Active</div></div>
    </div>
    <div class="card">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;"><span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Recent Referrals</span><span style="font-size:12px;color:#2563eb;cursor:pointer;" onclick="showSection('referrals',document.querySelectorAll('.slink')[1])">View all →</span></div>
      @forelse($referrals as $referral)
      <div style="display:flex;align-items:center;gap:10px;padding:11px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:30px;height:30px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(optional($referral->patient)->first_name ?? 'P',0,1)) }}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</div><div style="font-size:11px;color:#94a3b8;">{{ optional($referral->referringFacility)->name ?? 'N/A' }} → {{ optional($referral->receivingFacility)->name ?? 'N/A' }}</div></div>
        <span class="badge-{{ $referral->status ?? 'pending' }}">{{ ucfirst($referral->status ?? 'pending') }}</span>
      </div>
      @empty
      <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No referrals yet</div>
      @endforelse
    </div>
  </div>
</div>
<div id="sec-referrals" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;">
    <div style="font-size:20px;font-weight:700;color:#0f172a;">Incoming Referrals</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Manage referrals sent to your facility</div>
  </div>
  <div style="padding:24px 28px;">
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>All Referrals ({{ $referrals->count() }})</span>
      </div>
      @forelse($referrals as $referral)
      <div style="display:flex;align-items:center;gap:12px;padding:14px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(optional($referral->patient)->first_name ?? 'P',0,1)) }}</div>
        <div style="flex:1;">
          <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }} <span style="font-size:11px;color:#94a3b8;font-weight:400;">{{ optional($referral->patient)->patient_id ?? '' }}</span></div>
          <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ optional($referral->referringFacility)->name ?? 'N/A' }} → {{ optional($referral->receivingFacility)->name ?? 'N/A' }}</div>
          <div style="font-size:11px;color:#64748b;margin-top:2px;">{{ $referral->reason ?? 'No reason specified' }}</div>
          <div style="font-size:11px;color:#94a3b8;">{{ $referral->created_at->format('d M Y') }}</div>
        </div>
        <span class="badge-{{ $referral->status ?? 'pending' }}">{{ ucfirst($referral->status ?? 'pending') }}</span>
        @if(($referral->status ?? 'pending') === 'pending')
        <div style="display:flex;gap:6px;">
          <form method="POST" action="{{ route('referrals.updateStatus', $referral->id) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="accepted">
            <button type="submit" style="background:#2563eb;color:white;border:none;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">Accept</button>
          </form>
          <form method="POST" action="{{ route('referrals.updateStatus', $referral->id) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <button type="submit" style="background:white;color:#dc2626;border:1.5px solid #fca5a5;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">Reject</button>
          </form>
        </div>
        @else
        <span style="font-size:12px;color:#94a3b8;">No action needed</span>
        @endif
      </div>
      @empty
      <div style="text-align:center;padding:40px;color:#94a3b8;font-size:13px;">No referrals yet</div>
      @endforelse
    </div>
  </div>
</div>
<div id="sec-records" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
    <div><div style="font-size:20px;font-weight:700;color:#0f172a;">Medical Records</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">View and create patient medical records</div></div>
  </div>
  <div style="padding:24px 28px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Create New Record</div>
      <form method="POST" action="{{ route('records.store') }}">
        @csrf
        <div style="margin-bottom:12px;">
          <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Patient</label>
          <select name="patient_id" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
            <option value="">Select patient...</option>
            @foreach(\App\Models\User::where('role','patient')->get() as $p)
            <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }} — {{ $p->patient_id }}</option>
            @endforeach
          </select>
        </div>
        <div style="margin-bottom:12px;">
          <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Visit Date</label>
          <input type="date" name="visit_date" value="{{ date('Y-m-d') }}" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
        </div>
        <div style="margin-bottom:12px;">
          <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Diagnosis</label>
          <textarea name="diagnosis" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;resize:vertical;min-height:80px;" placeholder="Primary diagnosis and findings..."></textarea>
        </div>
        <div style="margin-bottom:12px;">
          <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Treatment Plan</label>
          <textarea name="treatment_plan" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;resize:vertical;min-height:60px;" placeholder="Prescribed treatment..."></textarea>
        </div>
        <input type="hidden" name="facility_id" value="{{ optional($facility)->id }}">
        <input type="hidden" name="doctor_id" value="{{ Auth::id() }}">
        <input type="hidden" name="chief_complaint" value="Hospital visit">
        <button type="submit" style="width:100%;background:#2563eb;color:white;border:none;padding:10px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Save Medical Record</button>
      </form>
    </div>
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Past Records</div>
      <div style="text-align:center;padding:40px 20px;color:#94a3b8;">
        <div style="font-size:13px;font-weight:600;color:#0f172a;margin-bottom:6px;">No records yet</div>
        <div style="font-size:13px;">Records created will appear here</div>
      </div>
    </div>
  </div>
</div>
<div id="sec-facilities" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
    <div><div style="font-size:20px;font-weight:700;color:#0f172a;">Nearby Facilities</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">Find hospitals near you for emergency referrals</div></div>
    <button onclick="getHospitalLocation()" style="background:#2563eb;color:white;border:none;padding:9px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Use My Location</button>
  </div>
  <div style="padding:24px 28px;">
    <div id="map" style="width:100%;height:300px;border-radius:10px;margin-bottom:16px;border:1px solid #e2e8f0;"></div>
    <div style="background:#dbeafe;border-radius:10px;height:300px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;border:1px dashed #93c5fd;margin-bottom:16px;display:none;" id="map-placeholder">
      <div style="font-size:14px;font-weight:600;color:#1d4ed8;">Google Maps</div>
      <div style="font-size:12px;color:#3b82f6;">Click Use My Location to find nearby hospitals</div>
    </div>
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>All Active Facilities</div>
      @foreach(\App\Models\Facility::where('is_active',true)->get() as $f)
      <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;">
        <div>
          <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $f->name }}</div>
          <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $f->county }} · {{ ucfirst($f->type) }} · {{ $f->phone ?? 'No phone' }}</div>
        </div>
        <span style="font-size:11px;color:#2563eb;background:#dbeafe;padding:3px 10px;border-radius:20px;font-weight:600;">{{ ucfirst($f->type) }}</span>
      </div>
      @endforeach
    </div>
  </div>
  <script>
  function getHospitalLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(pos) {
        document.getElementById('map-placeholder').style.display = 'none';
        document.getElementById('map').style.display = 'block';
      });
    }
  }
  </script>
</div>
<div id="sec-hours" class="section">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
    <div><div style="font-size:20px;font-weight:700;color:#0f172a;">Working Hours</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">View your facility operating hours</div></div>
  </div>
  <div style="padding:24px 28px;">
    <div style="background:white;border-radius:10px;padding:24px;border:1px solid #e2e8f0;max-width:500px;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Operating Hours</div>
      @php
        $hours = is_string(optional($facility)->working_hours)
          ? json_decode($facility->working_hours, true)
          : (optional($facility)->working_hours ?? []);
      @endphp
      @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
      <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f1f5f9;">
        <span style="font-size:13px;font-weight:500;color:#0f172a;">{{ $day }}</span>
        <span style="font-size:13px;color:#64748b;background:#f8fafc;padding:4px 12px;border-radius:6px;border:1px solid #e2e8f0;">{{ $hours[$day] ?? 'Not set' }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>
</div>
</div>
<script>
function showSection(name, el) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.slink').forEach(l => l.classList.remove('on'));
  document.getElementById('sec-' + name).classList.add('active');
  if(el) el.classList.add('on');
}
</script>
</body>
</html>
