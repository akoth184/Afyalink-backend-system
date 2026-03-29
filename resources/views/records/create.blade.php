<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Add Record — AfyaLink</title><link rel="icon" type="image/svg+xml" href="/favicon.svg"><link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"></head>
<body>
<div style="display:flex;min-height:100vh;">
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
<div style="margin-left:220px;flex:1;background:#f0f6ff;">
<div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
  <div>
    <div style="font-size:20px;font-weight:700;color:#0f172a;">Create Medical Record</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Add a new medical record for a patient</div>
  </div>
  <a href="{{ route('records.index') }}" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">← Back to Records</a>
</div>
<div style="padding:24px 28px;">
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden;max-width:800px;">
            <div style="padding:20px 28px;border-bottom:1px solid #e2e8f0;"><h2 style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:#1a1f2e;">New Medical Record</h2><p style="font-size:.82rem;color:#5a6275;margin-top:4px;">Document a patient visit or clinical entry</p></div>
            <form method="POST" action="{{ route('records.store') }}" enctype="multipart/form-data">@csrf
                <div style="padding:28px;">
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #e2e8f0;">Patient & Visit Info</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                        <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Patient *</label><select name="patient_id" required style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;"><option value="">Select patient...</option>@foreach($patients as $p)<option value="{{ $p->id }}" {{ old('patient_id')==$p->id?'selected':'' }}>{{ $p->first_name }} {{ $p->last_name }}</option>@endforeach</select>@error('patient_id')<div style="font-size:.75rem;color:#e53e3e;margin-top:4px;">{{ $message }}</div>@enderror</div>
                        <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Facility</label><select name="facility_id" style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;"><option value="">Select facility...</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ old('facility_id')==$f->id?'selected':'' }}>{{ $f->name }}</option>@endforeach</select>@error('facility_id')<div style="font-size:.75rem;color:#e53e3e;margin-top:4px;">{{ $message }}</div>@enderror</div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                        <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Record Title *</label><input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Outpatient Visit, Lab Result" style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;">@error('title')<div style="font-size:.75rem;color:#e53e3e;margin-top:4px;">{{ $message }}</div>@enderror</div>
                        <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Record Type</label><select name="record_type" style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;"><option value="general" {{ old('record_type')=='general'?'selected':'' }}>General</option><option value="lab" {{ old('record_type')=='lab'?'selected':'' }}>Lab Result</option><option value="radiology" {{ old('record_type')=='radiology'?'selected':'' }}>Radiology</option><option value="prescription" {{ old('record_type')=='prescription'?'selected':'' }}>Prescription</option><option value="surgery" {{ old('record_type')=='surgery'?'selected':'' }}>Surgery</option><option value="discharge" {{ old('record_type')=='discharge'?'selected':'' }}>Discharge Summary</option></select></div>
                    </div>
                    <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Visit Date</label><input type="date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;">@error('visit_date')<div style="font-size:.75rem;color:#e53e3e;margin-top:4px;">{{ $message }}</div>@enderror</div>
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#2563eb;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #e2e8f0;margin-top:8px">Clinical Details</div>
                    <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Diagnosis</label><textarea name="diagnosis" placeholder="Primary diagnosis and findings..." style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;resize:vertical;min-height:90px;">{{ old('diagnosis') }}</textarea>@error('diagnosis')<div style="font-size:.75rem;color:#e53e3e;margin-top:4px;">{{ $message }}</div>@enderror</div>
                    <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Treatment</label><textarea name="treatment" placeholder="Treatment plan and procedures..." style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;resize:vertical;min-height:90px;">{{ old('treatment') }}</textarea></div>
                    <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Prescription</label><textarea name="prescription" placeholder="Medications prescribed..." style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;resize:vertical;min-height:90px;">{{ old('prescription') }}</textarea></div>
                    <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Notes</label><textarea name="notes" placeholder="Additional clinical notes..." style="width:100%;padding:10px 14px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white;resize:vertical;min-height:90px;">{{ old('notes') }}</textarea></div>
                    <div style="margin-bottom:20px;"><label style="display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px;">Attach File (PDF, DOC, Images)</label><input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="width:100%;padding:8px;border:1.5px solid #dde4e4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;background:white;"><p style="font-size:0.75rem;color:#5a6275;margin-top:4px">Maximum file size: 10MB</p></div>
                </div>
                <div style="padding:20px 28px;border-top:1px solid #e2e8f0;display:flex;gap:10px;"><button type="submit" style="background:#2563eb;color:white;padding:9px 18px;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600;cursor:pointer;border:none;">Save Record</button><a href="{{ route('records.index') }}" style="background:#f0f2f5;color:#5a6275;padding:9px 18px;border-radius:9px;font-size:.85rem;font-weight:600;text-decoration:none;">Cancel</a></div>
            </form>
        </div>
</div>
</div></div>
</body></html>
