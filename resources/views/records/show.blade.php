<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Medical Record — AfyaLink</title>
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
@if(Auth::user()->role === 'patient')
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);">
    <div style="font-size:16px;font-weight:700;color:white;">AfyaLink</div>
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Patient Portal</div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->first_name ?? 'P', 0, 1)) }}</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">{{ Auth::user()->patient_id ?? 'Patient' }}</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Health</div>
    <a href="{{ route('patient.dashboard') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Dashboard</a>
    <a href="{{ route('patient.records') }}" style="display:block;padding:10px 20px;font-size:13px;color:white;text-decoration:none;background:rgba(59,130,246,.2);border-left:3px solid #3b82f6;">Medical Records</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Referrals</div>
    <a href="{{ route('patient.referrals') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">My Referrals</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Explore</div>
    <a href="{{ route('patient.nearby-hospitals') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Nearby Hospitals</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Account</div>
    <a href="{{ route('profile') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Profile</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Logout</button>
    </form>
  </div>
</aside>
@else
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
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
    <a href="{{ route('doctor.dashboard') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Dashboard</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Patients</div>
    <a href="{{ route('patients.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">My Patients</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Clinical</div>
    <a href="{{ route('referrals.create') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Create Referral</a>
    <a href="{{ route('referrals.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">My Referrals</a>
    <a href="{{ route('records.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:white;text-decoration:none;background:rgba(59,130,246,.2);border-left:3px solid #3b82f6;">Medical Records</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Tools</div>
    <a href="{{ route('patient.nearby-hospitals') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Nearby Hospitals</a>
    <a href="{{ route('facilities.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Facilities</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Sign Out</button>
    </form>
  </div>
</aside>
@endif
<div style="margin-left:220px;flex:1;">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
  <div>
    <div style="font-size:20px;font-weight:700;color:#0f172a;">Medical Record</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Record details and clinical information</div>
  </div>
  <div style="display:flex;gap:8px;">
    @if(Auth::user()->role === 'patient')
    <a href="{{ route('patient.records') }}" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">← Back</a>
    <a href="{{ route('patient.record.download', $record->id) }}" style="background:#2563eb;color:white;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Download PDF</a>
    @elseif(Auth::user()->role === 'hospital')
    <a href="{{ route('hospital.dashboard') }}" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">← Back</a>
    <a href="{{ route('records.download', $record->id) }}" style="background:#2563eb;color:white;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Download PDF</a>
    @else
    <a href="{{ route('records.index') }}" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">← Back</a>
    <a href="{{ route('records.download', $record->id) }}" style="background:#2563eb;color:white;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Download PDF</a>
    @endif
  </div>
</div>
  <div style="padding:24px 28px;">
    @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">✓ {{ session('success') }}</div>
    @endif
    <!-- PATIENT INFO -->
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Patient Information</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div>
          <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Patient Name</div>
          <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ optional($record->patient)->first_name ?? 'N/A' }} {{ optional($record->patient)->last_name ?? '' }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Patient ID</div>
          <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ optional($record->patient)->patient_id ?? 'N/A' }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Doctor</div>
          <div style="font-size:14px;font-weight:600;color:#0f172a;">Dr. {{ optional($record->doctor)->first_name ?? 'N/A' }} {{ optional($record->doctor)->last_name ?? '' }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Visit Date</div>
          <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y') : 'N/A' }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Facility</div>
          <div style="font-size:14px;font-weight:600;color:#0f172a;">{{ optional($record->facility)->name ?? 'N/A' }}</div>
        </div>
        <div>
          <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Status</div>
          <span style="background:{{ $record->status === 'finalized' ? '#dcfce7' : '#fef3c7' }};color:{{ $record->status === 'finalized' ? '#16a34a' : '#d97706' }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">{{ ucfirst($record->status ?? 'draft') }}</span>
        </div>
      </div>
    </div>
    <!-- CLINICAL DETAILS -->
<div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-bottom:16px;">
  <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
    <span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Clinical Details
  </div>
  @if($record->chief_complaint)
  <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Chief Complaint / Title</div>
    <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:10px 12px;border-radius:8px;">{{ $record->chief_complaint }}</div>
  </div>
  @endif
  @if($record->diagnosis)
  <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Diagnosis</div>
    <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:10px 12px;border-radius:8px;">{{ $record->diagnosis }}</div>
  </div>
  @endif
  @if($record->treatment_plan)
  <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Treatment Plan</div>
    <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:10px 12px;border-radius:8px;">{{ $record->treatment_plan }}</div>
  </div>
  @endif
  @if($record->medications)
  <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Prescription / Medications</div>
    <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:10px 12px;border-radius:8px;">{{ $record->medications }}</div>
  </div>
  @endif
  @if($record->notes)
  <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
    <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Notes</div>
    <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:10px 12px;border-radius:8px;">{{ $record->notes }}</div>
  </div>
  @endif
  @if(!$record->chief_complaint && !$record->diagnosis && !$record->treatment_plan && !$record->medications && !$record->notes)
  <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No clinical details recorded</div>
  @endif
</div>
    <!-- FILE ATTACHMENT -->
    @if($record->file_path)
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Attached File</div>
      <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
        <div style="font-size:13px;color:#0f172a;font-weight:600;">{{ basename($record->file_path) }}</div>
        <a href="{{ route('patient.record.file', $record->id) }}" style="background:#2563eb;color:white;padding:6px 14px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;">Download File</a>
      </div>
    </div>
    @endif
@php
  $patientLabTests = \App\Models\LabTest::where('patient_id', $record->patient_id)
      ->with('doctor')->latest()->get();
@endphp
@if($patientLabTests->count() > 0)
<div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;margin-top:16px;">
  <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
    <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;display:inline-block;"></span>
    Lab Test History
  </div>
  @foreach($patientLabTests as $test)
  <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid #f1f5f9;">
    <div style="flex:1;">
      <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $test->test_name }}</div>
      <div style="font-size:11px;color:#94a3b8;">{{ ucfirst($test->test_category) }} · Dr. {{ optional($test->doctor)->first_name ?? 'N/A' }} · {{ \Carbon\Carbon::parse($test->requested_date)->format('d M Y') }}</div>
      @if($test->result_notes)
      <div style="font-size:11px;color:#16a34a;margin-top:3px;">Result: {{ $test->result_notes }}</div>
      @endif
    </div>
    <div style="display:flex;align-items:center;gap:6px;">
      <span style="background:{{ $test->status === 'completed' ? '#dcfce7' : '#fef3c7' }};color:{{ $test->status === 'completed' ? '#16a34a' : '#d97706' }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ $test->status === 'completed' ? 'Results Ready' : 'Pending' }}</span>
      @if($test->result_file)
      <a href="{{ route('lab-tests.download', $test->id) }}" style="background:#dbeafe;color:#1d4ed8;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">Download</a>
      @endif
    </div>
  </div>
  @endforeach
</div>
@endif
  </div>
</div>
</div>
</body>
</html>
