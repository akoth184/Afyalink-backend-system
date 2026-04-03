<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Lab Tests — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
</style>
</head>
<body style="background:#f0f6ff;">
<div style="display:flex;min-height:100vh;">
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:10px;">
    <svg width="28" height="28" viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="9" fill="rgba(255,255,255,0.1)"/><circle cx="18" cy="18" r="3.5" fill="#3b82f6"/><circle cx="8" cy="11" r="2.5" fill="#60a5fa"/><circle cx="28" cy="11" r="2.5" fill="#60a5fa"/><circle cx="8" cy="25" r="2.5" fill="#60a5fa"/><circle cx="28" cy="25" r="2.5" fill="#60a5fa"/><line x1="18" y1="18" x2="8" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="11" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="8" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><line x1="18" y1="18" x2="28" y2="25" stroke="#60a5fa" stroke-width="1.5" opacity="0.6"/><polyline points="11,18 13,18 14,14 16,22 17,16 19,18 25,18" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
    <div><div style="font-size:15px;font-weight:700;color:white;">AfyaLink</div><div style="font-size:11px;color:rgba(255,255,255,.4);">Doctor Portal</div></div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->first_name ?? 'D',0,1)) }}</div>
    <div><div style="font-size:13px;color:white;font-weight:600;">Dr. {{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</div><div style="font-size:11px;color:rgba(255,255,255,.4);">{{ Auth::user()->specialization ?? 'General Practice' }}</div></div>
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
    <div><div style="font-size:20px;font-weight:700;color:#0f172a;">Lab Tests</div><div style="font-size:12px;color:#94a3b8;margin-top:3px;">Request and manage patient lab tests</div></div>
    <a href="{{ route('lab-tests.create') }}" style="background:#2563eb;color:white;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">+ Request Lab Test</a>
  </div>
  <div style="padding:24px 28px;">
    @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;font-weight:500;">✓ {{ session('success') }}</div>
    @endif
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>All Lab Tests ({{ $labTests->count() }})</div>
      @forelse($labTests as $test)
      <div style="padding:14px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:10px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
          <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
              <div style="font-size:14px;font-weight:700;color:#0f172a;">{{ $test->test_name }}</div>
              <span style="background:{{ $test->status === 'completed' ? '#dcfce7' : '#fef3c7' }};color:{{ $test->status === 'completed' ? '#16a34a' : '#d97706' }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($test->status) }}</span>
              <span style="background:#dbeafe;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($test->test_category) }}</span>
            </div>
            <div style="font-size:12px;color:#64748b;">Patient: {{ optional($test->patient)->first_name ?? 'N/A' }} {{ optional($test->patient)->last_name ?? '' }} · Requested: {{ \Carbon\Carbon::parse($test->requested_date)->format('d M Y') }}</div>
            @if($test->clinical_notes)
            <div style="font-size:12px;color:#64748b;margin-top:4px;">Notes: {{ $test->clinical_notes }}</div>
            @endif
            @if($test->result_notes)
            <div style="font-size:12px;color:#16a34a;margin-top:4px;background:#f0fdf4;padding:6px 10px;border-radius:6px;">Result: {{ $test->result_notes }}</div>
            @endif
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
            @if($test->status === 'requested')
            <button onclick="document.getElementById('upload-{{ $test->id }}').style.display='block'" style="background:#2563eb;color:white;border:none;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">Upload Result</button>
            @endif
            @if($test->result_file)
            <a href="{{ route('lab-tests.download', $test->id) }}" style="background:#dcfce7;color:#16a34a;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;text-align:center;">Download</a>
            @endif
          </div>
        </div>
        <!-- Upload Result Form -->
        <div id="upload-{{ $test->id }}" style="display:none;margin-top:14px;padding-top:14px;border-top:1px solid #e2e8f0;">
          <form method="POST" action="{{ route('lab-tests.upload', $test->id) }}" enctype="multipart/form-data">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
              <div>
                <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Result File (PDF/Image)</label>
                <input type="file" name="result_file" required accept=".pdf,.jpg,.jpeg,.png" style="width:100%;background:white;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px;font-size:12px;font-family:inherit;">
              </div>
              <div>
                <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Result Date</label>
                <input type="date" name="result_date" value="{{ date('Y-m-d') }}" required style="width:100%;background:white;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:12px;font-family:inherit;outline:none;">
              </div>
            </div>
            <div style="margin-bottom:12px;">
              <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Result Summary</label>
              <textarea name="result_notes" placeholder="Brief summary of results..." style="width:100%;background:white;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:12px;font-family:inherit;outline:none;resize:vertical;min-height:60px;"></textarea>
            </div>
            <div style="display:flex;gap:8px;">
              <button type="submit" style="background:#16a34a;color:white;border:none;padding:8px 16px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">Save Results</button>
              <button type="button" onclick="document.getElementById('upload-{{ $test->id }}').style.display='none'" style="background:white;color:#64748b;border:1.5px solid #e2e8f0;padding:8px 16px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">Cancel</button>
            </div>
          </form>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:40px;color:#94a3b8;">
        <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:6px;">No lab tests yet</div>
        <div style="font-size:13px;">Click Request Lab Test to create one</div>
      </div>
      @endforelse
    </div>
  </div>
</div>
</div>
</body>
</html>