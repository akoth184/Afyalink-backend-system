<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Medical Records — AfyaLink</title><link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"></head>
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
    <div style="font-size:20px;font-weight:700;color:#0f172a;">Medical Records</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">View and manage patient medical records</div>
  </div>
  <a href="{{ route('records.create') }}" style="background:#2563eb;color:white;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">+ Add Record</a>
</div>
<div style="padding:24px 28px;">
        @if(session('success'))<div style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:.875rem;margin-bottom:20px;background:#e8f8ef;border:1px solid rgba(39,174,96,.25);color:#276749;">{{ session('success') }}</div>@endif
        <div style="background:white;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid #e2e8f0;"><span style="font-family:'DM Serif Display',serif;font-size:1rem;color:#1a1f2e;">All Records ({{ $records->count() }})</span></div>
            @if($records->isEmpty())
                <div style="text-align:center;padding:60px 24px;"><svg viewBox="0 0 24 24" style="width:48px;height:48px;stroke:#dde4e4;fill:none;stroke-width:1.5;margin-bottom:16px;"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg><h3 style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:#1a1f2e;margin-bottom:8px;">No records yet</h3><p style="font-size:.875rem;color:#5a6275;margin-bottom:20px;">Add the first medical record.</p><a href="{{ route('records.create') }}" style="background:#2563eb;color:white;padding:9px 18px;border-radius:9px;font-size:.85rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:7px;">Add Record</a></div>
            @else
                <table style="width:100%;border-collapse:collapse;">
                    <thead><tr><th style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#5a6275;padding:10px 22px;text-align:left;background:#f8fafa;border-bottom:1px solid #e2e8f0;">Title</th><th style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#5a6275;padding:10px 22px;text-align:left;background:#f8fafa;border-bottom:1px solid #e2e8f0;">Patient</th><th style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#5a6275;padding:10px 22px;text-align:left;background:#f8fafa;border-bottom:1px solid #e2e8f0;">Facility</th><th style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#5a6275;padding:10px 22px;text-align:left;background:#f8fafa;border-bottom:1px solid #e2e8f0;">Type</th><th style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#5a6275;padding:10px 22px;text-align:left;background:#f8fafa;border-bottom:1px solid #e2e8f0;">Visit Date</th><th style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#5a6275;padding:10px 22px;text-align:left;background:#f8fafa;border-bottom:1px solid #e2e8f0;">Added</th><th></th></tr></thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td style="padding:13px 22px;font-size:.85rem;border-bottom:1px solid #f0f4f4;"><div style="font-weight:600;color:#1a1f2e;">{{ $record->title ?? 'Record #'.$record->id }}</div></td>
                            <td style="padding:13px 22px;font-size:.82rem;border-bottom:1px solid #f0f4f4;">{{ optional($record->patient)->first_name ?? 'N/A' }} {{ optional($record->patient)->last_name ?? '' }}</td>
                            <td style="padding:13px 22px;font-size:.82rem;color:#5a6275;border-bottom:1px solid #f0f4f4;">{{ optional($record->facility)->name ?? '—' }}</td>
                            <td style="padding:13px 22px;border-bottom:1px solid #f0f4f4;"><span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:100px;font-size:.72rem;font-weight:700;background:#ebf8ff;color:#1a5276;">{{ ucfirst($record->record_type ?? 'general') }}</span></td>
                            <td style="padding:13px 22px;font-size:.78rem;color:#5a6275;border-bottom:1px solid #f0f4f4;">{{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y') : '—' }}</td>
                            <td style="padding:13px 22px;font-size:.78rem;color:#5a6275;border-bottom:1px solid #f0f4f4;">{{ $record->created_at->format('d M Y') }}</td>
                            <td style="padding:13px 22px;border-bottom:1px solid #f0f4f4;"><a href="{{ route('records.show', $record) }}" style="background:#2563eb;color:white;padding:6px 14px;border-radius:8px;font-size:.78rem;font-weight:600;text-decoration:none;">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="display:flex;justify-content:center;gap:6px;padding:16px;border-top:1px solid #e2e8f0;flex-wrap:wrap;">{{ $records->links() }}</div>
            @endif
        </div>
</div>
</div></div>
</body></html>
