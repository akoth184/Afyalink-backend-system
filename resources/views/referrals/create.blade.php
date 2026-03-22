<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>New Referral — AfyaLink</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:#f0f6ff;color:#1a1f2e;min-height:100vh}
.form-card{background:white;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden;max-width:760px}
.form-header{padding:20px 28px;border-bottom:1px solid #e2e8f0}
.form-header h2{font-family:'DM Serif Display',serif;font-size:1.1rem;color:#1a1f2e}
.form-header p{font-size:.82rem;color:#5a6275;margin-top:4px}
.form-body{padding:28px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
.form-group{margin-bottom:20px}
.form-group label{display:block;font-size:.82rem;font-weight:600;color:#1a1f2e;margin-bottom:6px}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;color:#1a1f2e;outline:none;transition:border-color .2s;background:white}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.08)}
.form-group textarea{resize:vertical;min-height:90px}
.field-error{font-size:.75rem;color:#e53e3e;margin-top:4px}
.form-footer{padding:20px 28px;border-top:1px solid #e2e8f0;display:flex;gap:10px}
.btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s;border:none}
.btn-primary{background:#2563eb;color:white}
.btn-primary:hover{background:#1d4ed8}
.btn-sm{padding:6px 14px;font-size:.78rem}
@media(max-width:768px){.form-row{grid-template-columns:1fr}}
</style>
</head>
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
    <a href="{{ route('referrals.create') }}" style="display:block;padding:10px 20px;font-size:13px;color:white;text-decoration:none;background:rgba(59,130,246,.2);border-left:3px solid #3b82f6;">Create Referral</a>
    <a href="{{ route('referrals.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">My Referrals</a>
    <a href="{{ route('records.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Medical Records</a>
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
<div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:10;">
  <div style="font-size:20px;font-weight:700;color:#0f172a;">Create Referral</div>
  <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Refer a patient to another facility</div>
</div>
<div style="padding:24px 28px;">
<div class="form-card">
<div class="form-header"><h2>Create Referral</h2><p>Refer a patient from one facility to another</p></div>
<form method="POST" action="{{ route('referrals.store') }}">@csrf
<div class="form-body">
    <div class="form-group"><label>Patient *</label><select name="patient_id" required><option value="">Select patient...</option>@foreach($patients as $p)<option value="{{ $p->id }}" {{ old('patient_id')==$p->id ? 'selected' : '' }}>{{ $p->first_name }} {{ $p->last_name }}</option>@endforeach</select>@error('patient_id')<div class="field-error">{{ $message }}</div>@enderror</div>
    <div class="form-row">
        <div class="form-group"><label>Referring Facility *</label><select name="referring_facility_id" required><option value="">From facility...</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ old('referring_facility_id')==$f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select>@error('referring_facility_id')<div class="field-error">{{ $message }}</div>@enderror</div>
        <div class="form-group"><label>Receiving Facility *</label><select name="receiving_facility_id" required><option value="">To facility...</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ old('receiving_facility_id')==$f->id ? 'selected' : '' }}>{{ $f->name }}</option>@endforeach</select>@error('receiving_facility_id')<div class="field-error">{{ $message }}</div>@enderror</div>
    </div>
    <div class="form-group"><label>Reason for Referral *</label><textarea name="reason" required placeholder="Describe the clinical reason for this referral...">{{ old('reason') }}</textarea>@error('reason')<div class="field-error">{{ $message }}</div>@enderror</div>
    <div class="form-group"><label>Additional Notes</label><textarea name="notes" placeholder="Any extra information...">{{ old('notes') }}</textarea>@error('notes')<div class="field-error">{{ $message }}</div>@enderror</div>
</div>
<div class="form-footer"><button type="submit" class="btn btn-primary">Create Referral</button><a href="{{ route('referrals.index') }}" class="btn btn-sm" style="background:#f0f2f5;color:#5a6275">Cancel</a></div>
</form>
</div>
</div></div></div>
</body>
</html>
