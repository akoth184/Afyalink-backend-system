<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Referrals — AfyaLink</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{--blue:#2563eb;--blue-lt:#dbeafe;--green:#27ae60;--green-lt:#e8f8ef;--amber:#e67e22;--amber-lt:#fef3e7;--red:#e53e3e;--red-lt:#fff5f5;--blue2:#3182ce;--blue2-lt:#ebf8ff;--ink:#1a1f2e;--muted:#5a6275;--border:#e2e8f0;--shadow-sm:0 1px 4px rgba(0,0,0,.06)}
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:#f0f6ff;color:var(--ink);min-height:100vh}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s;border:none}
        .btn-primary{background:var(--blue);color:white;box-shadow:0 2px 8px rgba(37,99,235,.25)}
        .btn-primary:hover{background:#1d4ed8;transform:translateY(-1px)}
        .btn-sm{padding:6px 14px;font-size:.78rem}
        .alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:.875rem;margin-bottom:20px}
        .alert-success{background:var(--green-lt);border:1px solid rgba(39,174,96,.25);color:#276749}
        .card{background:white;border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow-sm);overflow:hidden}
        .card-header{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border)}
        .card-title{font-family:'DM Serif Display',serif;font-size:1rem;color:var(--ink)}
        .data-table{width:100%;border-collapse:collapse}
        .data-table th{font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);padding:10px 22px;text-align:left;background:#f8fafa;border-bottom:1px solid var(--border)}
        .data-table td{padding:13px 22px;font-size:.85rem;border-bottom:1px solid #f0f4f4;vertical-align:middle}
        .data-table tr:last-child td{border-bottom:none}
        .data-table tr:hover td{background:#f8fafa}
        .td-name{font-weight:600;color:var(--ink)}
        .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:100px;font-size:.72rem;font-weight:700}
        .badge-green{background:var(--green-lt);color:#276749}
        .badge-amber{background:var(--amber-lt);color:#9c5a0a}
        .badge-red{background:var(--red-lt);color:#c53030}
        .badge-blue{background:var(--blue2-lt);color:#1a5276}
        .empty-state{text-align:center;padding:60px 24px}
        .empty-state svg{width:48px;height:48px;stroke:var(--border);fill:none;stroke-width:1.5;margin-bottom:16px}
        .empty-state h3{font-family:'DM Serif Display',serif;font-size:1.1rem;color:var(--ink);margin-bottom:8px}
        .empty-state p{font-size:.875rem;color:var(--muted);margin-bottom:20px}
        .pagination{display:flex;justify-content:center;gap:6px;padding:16px;border-top:1px solid var(--border);flex-wrap:wrap}
        .pagination a,.pagination span{padding:6px 12px;border-radius:7px;font-size:.82rem;text-decoration:none;color:var(--muted);border:1px solid var(--border)}
        .pagination .active span{background:var(--blue);color:white;border-color:var(--blue)}
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
    <a href="{{ route('referrals.create') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Create Referral</a>
    <a href="{{ route('referrals.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:white;text-decoration:none;background:rgba(59,130,246,.2);border-left:3px solid #3b82f6;">My Referrals</a>
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
<div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:10;">
  <div>
    <div style="font-size:20px;font-weight:700;color:#0f172a;">My Referrals</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Track all referrals you have created</div>
  </div>
  <a href="{{ route('referrals.create') }}" style="background:#2563eb;color:white;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">+ New Referral</a>
</div>
<div style="padding:24px 28px;">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card">
            <div class="card-header"><span class="card-title">All Referrals ({{ $referrals->count() }})</span></div>
            @if($referrals->isEmpty())
                <div class="empty-state">
                    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <h3>No referrals yet</h3><p>Create the first patient referral.</p>
                    <a href="{{ route('referrals.create') }}" class="btn btn-primary btn-sm">New Referral</a>
                </div>
            @else
                <table class="data-table">
                    <thead><tr><th>Patient</th><th>From</th><th>To</th><th>Reason</th><th>Status</th><th>Date</th><th></th></tr></thead>
                    <tbody>
                        @foreach($referrals as $ref)
                        @php
                            $s = $ref->status ?? 'pending';
                            if($s==='completed') $bc='badge-green';
                            elseif($s==='accepted') $bc='badge-blue';
                            elseif($s==='rejected') $bc='badge-red';
                            else $bc='badge-amber';
                        @endphp
                        <tr>
                            <td><div class="td-name">{{ optional($ref->patient)->first_name }} {{ optional($ref->patient)->last_name }}</div></td>
                            <td style="font-size:.82rem;color:var(--muted)">{{ optional($ref->referringFacility)->name ?? '&mdash;' }}</td>
                            <td style="font-size:.82rem;color:var(--muted)">{{ optional($ref->receivingFacility)->name ?? '&mdash;' }}</td>
                            <td style="font-size:.82rem;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $ref->reason ?? '&mdash;' }}</td>
                            <td><span class="badge {{ $bc }}">{{ ucfirst($s) }}</span>
                            @if($ref->priority)
                            <span style="background:{{ $ref->priority === 'emergency' ? '#fee2e2' : ($ref->priority === 'urgent' ? '#fef3c7' : '#dbeafe') }};color:{{ $ref->priority === 'emergency' ? '#dc2626' : ($ref->priority === 'urgent' ? '#d97706' : '#2563eb') }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($ref->priority) }}</span>
                            @endif
                            @if($ref->status === 'rejected' && $ref->rejection_reason)
                            <div style="margin-top:8px;background:#fee2e2;border-left:3px solid #dc2626;padding:8px 12px;border-radius:0 6px 6px 0;">
                              <div style="font-size:10px;font-weight:700;color:#dc2626;text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px;">Rejection Reason</div>
                              <div style="font-size:12px;color:#991b1b;line-height:1.5;">{{ $ref->rejection_reason }}</div>
                            </div>
                            @endif
                            </td>
                            <td style="font-size:.78rem;color:var(--muted)">{{ $ref->created_at->format('d M Y') }}</td>
                            <td><a href="{{ route('referrals.show', $ref) }}" class="btn btn-sm" style="background:var(--blue-lt);color:var(--blue)">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination">{{ $referrals->links() }}</div>
            @endif
        </div>
</div></div></div>
</body>
</html>
