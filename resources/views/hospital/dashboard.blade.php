<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hospital Dashboard — AfyaLink</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);border-left:3px solid transparent;cursor:pointer;text-decoration:none;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
.badge-accepted{background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-rejected{background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
</style>
</head>
<body style="background:#f0f6ff;font-family:'Inter',sans-serif;">
<div style="display:flex;min-height:100vh;">

<!-- SIDEBAR -->
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
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
    <div class="slink on">Dashboard</div>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Referrals</div>
    <a href="#incoming-referrals" class="slink" onclick="document.getElementById('incoming-referrals').scrollIntoView({behavior:'smooth'})">Incoming Referrals</a>
    <a href="#transfer-form" class="slink" onclick="document.getElementById('transfer-form').scrollIntoView({behavior:'smooth'})">Transfer Patient</a>
    <a href="#incoming-referrals" onclick="document.getElementById('incoming-referrals').scrollIntoView({behavior:'smooth'})" class="slink">Referral Reports</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Management</div>
    <div class="slink" onclick="document.getElementById('medical-records-section').scrollIntoView({behavior:'smooth'})">Medical Records</div>
    <div class="slink" onclick="document.getElementById('working-hours-section').scrollIntoView({behavior:'smooth'})">Working Hours</div>
    <div class="slink">Settings</div>
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
      <div style="font-size:20px;font-weight:700;color:#0f172a;">Hospital Dashboard</div>
      <div style="font-size:12px;color:#94a3b8;margin-top:3px;">{{ optional($facility)->name ?? 'Hospital' }}</div>
    </div>
    <a href="#transfer-form" onclick="document.getElementById('transfer-form').scrollIntoView({behavior:'smooth'})" style="background:#2563eb;color:white;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">+ Transfer Patient</a>
  </div>

  <!-- CONTENT -->
  <div style="padding:24px 28px;">

    <!-- STATS ROW -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
      <div style="background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Incoming Today</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['pending_referrals'] ?? 0 }}</div>
        <div style="font-size:11px;color:#d97706;margin-top:5px;">Pending action</div>
      </div>
      <div style="background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Accepted</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $stats['accepted_referrals'] ?? 0 }}</div>
        <div style="font-size:11px;color:#16a34a;margin-top:5px;">This week</div>
      </div>
      <div style="background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Transferred Out</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">0</div>
        <div style="font-size:11px;color:#2563eb;margin-top:5px;">This month</div>
      </div>
      <div style="background:white;border-radius:10px;padding:18px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total Referrals</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;">{{ $referrals->count() ?? 0 }}</div>
        <div style="font-size:11px;color:#2563eb;margin-top:5px;">All time</div>
      </div>
    </div>

    <!-- INCOMING REFERRALS FULL WIDTH -->
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;" id="incoming-referrals">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Incoming Referrals</span>
        <a href="{{ route('referrals.index') }}" style="font-size:12px;color:#2563eb;font-weight:500;text-decoration:none;">View All</a>
      </div>
      @forelse($referrals as $referral)
      <div style="display:flex;align-items:center;gap:12px;padding:14px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(optional($referral->patient)->first_name ?? 'P',0,1)) }}</div>
        <div style="flex:1;">
          <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }} <span style="font-size:11px;color:#94a3b8;font-weight:400;">{{ optional($referral->patient)->patient_id ?? '' }}</span></div>
          <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ optional($referral->referringFacility)->name ?? 'N/A' }} · {{ $referral->reason ?? 'No reason' }} · {{ $referral->created_at->format('M d') }}</div>
        </div>
        <span class="badge-{{ $referral->status ?? 'pending' }}" style="margin-right:8px;">{{ ucfirst($referral->status ?? 'pending') }}</span>
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
      <div style="text-align:center;padding:30px;color:#94a3b8;font-size:13px;">No referrals yet</div>
      @endforelse
    </div>

    <!-- Referral Reports -->
    <div id="referral-reports" style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Referral Reports</span>
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
          <tr style="border-bottom:1px solid #f1f5f9;">
            <td style="padding:11px 0;font-weight:600;">REF-{{ str_pad($referral->id,5,'0',STR_PAD_LEFT) }}</td>
            <td style="padding:11px 0;">{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</td>
            <td style="padding:11px 0;color:#64748b;">{{ optional($referral->referringFacility)->name ?? 'N/A' }}</td>
            <td style="padding:11px 0;color:#64748b;">{{ $referral->reason ?? 'N/A' }}</td>
            <td style="padding:11px 0;"><span class="badge-{{ $referral->status ?? 'pending' }}">{{ ucfirst($referral->status ?? 'pending') }}</span></td>
            <td style="padding:11px 0;color:#94a3b8;">{{ $referral->created_at->format('d M Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Medical Records Section -->
    <div id="medical-records-section" style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-top:16px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <span style="font-size:14px;font-weight:600;color:#0f172a;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Medical Records</span>
        <span style="font-size:12px;color:#94a3b8;">Records of referred patients</span>
      </div>
      @php
        $acceptedPatientIds = $referrals->where('status','accepted')->pluck('patient_id')->filter()->unique()->values();
        $patientRecords = \App\Models\MedicalRecord::whereIn('patient_id', $acceptedPatientIds)->with('patient')->latest()->get();
      @endphp
      @forelse($patientRecords as $record)
      <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #f1f5f9;">
        <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(optional($record->patient)->first_name ?? 'P',0,1)) }}</div>
        <div style="flex:1;">
          <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ optional($record->patient)->first_name ?? 'N/A' }} {{ optional($record->patient)->last_name ?? '' }}</div>
          <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $record->diagnosis ?? 'No diagnosis' }} · {{ $record->visit_date ? $record->visit_date->format('d M Y') : 'No date' }}</div>
        </div>
        <span style="font-size:11px;color:#2563eb;background:#dbeafe;padding:3px 10px;border-radius:20px;font-weight:600;">{{ ucfirst($record->status ?? 'draft') }}</span>
      </div>
      @empty
      <div style="text-align:center;padding:30px;color:#94a3b8;font-size:13px;">
        <div style="font-size:13px;font-weight:600;color:#0f172a;margin-bottom:6px;">No medical records yet</div>
        <div>Medical records will appear here once doctors create them for referred patients</div>
      </div>
      @endforelse
    </div>

    <!-- TRANSFER + WORKING HOURS -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

      <!-- Transfer Form -->
      <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;" id="transfer-form">
        <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Hospital-to-Hospital Transfer</div>
        <form method="POST" action="{{ route('referrals.store') }}">
          @csrf
          <div style="margin-bottom:12px;">
            <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Patient</label>
            <select name="patient_id" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
              <option value="">Select patient...</option>
              @foreach(\App\Models\User::where('role','patient')->get() as $p)
              <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }} — {{ $p->patient_id }}</option>
              @endforeach
            </select>
          </div>
          <div style="margin-bottom:12px;">
            <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Target Hospital</label>
            <select name="receiving_facility_id" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;">
              <option value="">Select hospital...</option>
              @foreach(\App\Models\Facility::where('is_active',true)->where('id','!=',optional($facility)->id)->get() as $f)
              <option value="{{ $f->id }}">{{ $f->name }}</option>
              @endforeach
            </select>
          </div>
          <div style="margin-bottom:12px;">
            <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Transfer Reason</label>
            <textarea name="reason" required style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;resize:vertical;min-height:80px;" placeholder="Reason for inter-hospital transfer..."></textarea>
          </div>
          <input type="hidden" name="referring_facility_id" value="{{ optional($facility)->id }}">
          <input type="hidden" name="status" value="pending">
          <input type="hidden" name="referred_by" value="{{ Auth::id() }}">
          <button type="submit" style="width:100%;background:#2563eb;color:white;border:none;padding:10px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Initiate Transfer</button>
        </form>
      </div>

      <!-- Working Hours -->
      <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;" id="working-hours-section">
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
          <form method="POST" action="{{ route('hospital.dashboard') }}">
            @csrf
            <div style="margin-bottom:10px;">
              <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Day</label>
              <select id="edit-day" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:12px;font-family:inherit;">
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday','All Days'] as $d)
                <option>{{ $d }}</option>
                @endforeach
              </select>
            </div>
            <div style="margin-bottom:10px;">
              <label style="font-size:11px;color:#64748b;font-weight:600;display:block;margin-bottom:5px;">Hours</label>
              <input type="text" id="edit-time" placeholder="e.g. 8:00 AM – 6:00 PM or 24 Hours" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:12px;font-family:inherit;">
            </div>
            <button type="button" onclick="saveHour()" style="width:100%;background:#2563eb;color:white;border:none;padding:9px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;">Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<script>
function saveHour() {
  alert('Working hours updated! (Connect to backend to persist)');
  document.getElementById('edit-hours').style.display = 'none';
}
</script>
</body>
</html>
