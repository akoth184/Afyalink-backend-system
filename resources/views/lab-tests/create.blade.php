<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Request Lab Test — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>body{font-family:'Inter',sans-serif;}.fi{width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:13px;font-family:'Inter',sans-serif;outline:none;}.fi:focus{border-color:#2563eb;background:white;}.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;}.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}</style>
</head>
<body style="background:#f0f6ff;">
<div style="display:flex;min-height:100vh;">
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:10px;">
    <svg width="28" height="28" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="9" fill="rgba(255,255,255,0.1)"/><circle cx="18" cy="18" r="3.5" fill="#3b82f6"/><circle cx="8" cy="11" r="2.5" fill="#60a5fa"/><circle cx="28" cy="11" r="2.5" fill="#60a5fa"/><circle cx="8" cy="25" r="2.5" fill="#60a5fa"/><circle cx="28" cy="25" r="2.5" fill="#60a5fa"/><line x1="18" y1="18" x2="8" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="8" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><polyline points="11,18 13,18 14,14 16,22 17,16 19,18 25,18" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
    <div><div style="font-size:15px;font-weight:700;color:white;">AfyaLink</div><div style="font-size:11px;color:rgba(255,255,255,.4);">Doctor Portal</div></div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <a href="{{ route('doctor.dashboard') }}" class="slink">Dashboard</a>
    <a href="{{ route('patients.index') }}" class="slink">My Patients</a>
    <a href="{{ route('referrals.create') }}" class="slink">Create Referral</a>
    <a href="{{ route('referrals.index') }}" class="slink">My Referrals</a>
    <a href="{{ route('records.index') }}" class="slink">Medical Records</a>
    <a href="{{ route('lab-tests.index') }}" class="slink on">Lab Tests</a>
    <a href="{{ route('facilities.index') }}" class="slink">Facilities</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Sign Out</button>
    </form>
  </div>
</aside>
<div style="margin-left:220px;flex:1;">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
    <div><div style="font-size:20px;font-weight:700;color:#0f172a;">Request Lab Test</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">Create a new lab test request for a patient</div></div>
    <a href="{{ route('lab-tests.index') }}" style="background:white;color:#2563eb;border:1.5px solid #2563eb;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">← Back</a>
  </div>
  <div style="padding:24px 28px;">
    @if($errors->any())
    <div style="background:#fee2e2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">
      @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
    </div>
    @endif
    <div style="background:white;border-radius:12px;padding:28px;border:1px solid #e2e8f0;max-width:700px;">
      <form method="POST" action="{{ route('lab-tests.store') }}">
        @csrf
        <div style="margin-bottom:16px;">
          <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Patient <span style="color:#dc2626;">*</span></label>
          <select name="patient_id" required class="fi">
            <option value="">Select patient...</option>
            @foreach($patients as $p)
            <option value="{{ $p->id }}" {{ old('patient_id')==$p->id?'selected':'' }}>{{ $p->first_name }} {{ $p->last_name }} — {{ $p->patient_id ?? 'N/A' }}</option>
            @endforeach
          </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
          <div>
            <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Test Category <span style="color:#dc2626;">*</span></label>
            <select name="test_category" required class="fi">
              <option value="blood" {{ old('test_category')==='blood'?'selected':'' }}>Blood Test</option>
              <option value="urine" {{ old('test_category')==='urine'?'selected':'' }}>Urine Test</option>
              <option value="imaging" {{ old('test_category')==='imaging'?'selected':'' }}>Imaging (X-Ray/Scan)</option>
              <option value="microbiology" {{ old('test_category')==='microbiology'?'selected':'' }}>Microbiology</option>
              <option value="other" {{ old('test_category')==='other'?'selected':'' }}>Other</option>
            </select>
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Specific Test Name <span style="color:#dc2626;">*</span></label>
            <input type="text" name="test_name" value="{{ old('test_name') }}" required placeholder="e.g. Full Blood Count, Chest X-Ray..." class="fi">
          </div>
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Requested Date</label>
          <input type="date" name="requested_date" value="{{ old('requested_date', date('Y-m-d')) }}" required class="fi">
        </div>
        <div style="margin-bottom:24px;">
          <label style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px;">Clinical Notes</label>
          <textarea name="clinical_notes" rows="3" placeholder="Reason for test, patient symptoms, relevant history..." class="fi" style="resize:vertical;min-height:80px;">{{ old('clinical_notes') }}</textarea>
        </div>
        <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:12px;margin-bottom:20px;">
          <div style="font-size:12px;font-weight:600;color:#d97706;margin-bottom:3px;">Note</div>
          <div style="font-size:12px;color:#92400e;">After requesting this test, the patient will be notified to visit the lab. You can upload the results once they are ready.</div>
        </div>
        <button type="submit" style="width:100%;background:#2563eb;color:white;border:none;padding:13px;border-radius:9px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;">Request Lab Test</button>
      </form>
    </div>
  </div>
</div>
</div>
</body>
</html>