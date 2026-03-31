<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Referral — AfyaLink</title><link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"><style>:root{--teal:#0d6e6e;--teal-mid:#0f8080;--teal-lt:#e6f4f4;--teal-dark:#0a5555;--green:#27ae60;--green-lt:#e8f8ef;--amber:#e67e22;--amber-lt:#fef3e7;--red:#e53e3e;--red-lt:#fff5f5;--blue:#3182ce;--blue-lt:#ebf8ff;--ink:#1a1f2e;--muted:#5a6275;--border:#dde4e4;--sidebar-w:260px;--header-h:64px;--shadow-sm:0 1px 4px rgba(0,0,0,.06)}*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}body{font-family:'DM Sans',sans-serif;background:#f1f5f5;color:var(--ink);min-height:100vh;display:flex}.sidebar{width:var(--sidebar-w);background:var(--teal-dark);min-height:100vh;position:fixed;left:0;top:0;bottom:0;display:flex;flex-direction:column;z-index:200}.sidebar-logo{padding:22px 24px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;text-decoration:none}.sidebar-logo-mark{width:36px;height:36px;background:rgba(255,255,255,.15);border-radius:9px;display:flex;align-items:center;justify-content:center}.sidebar-logo-mark svg{width:20px;height:20px;fill:white}.sidebar-logo-text{font-family:'DM Serif Display',serif;font-size:1.25rem;color:white}.sidebar-nav{flex:1;padding:16px 12px;overflow-y:auto}.nav-section-label{font-size:.65rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.35);padding:12px 12px 6px}.nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:9px;text-decoration:none;font-size:.875rem;font-weight:500;color:rgba(255,255,255,.65);transition:all .2s;margin-bottom:2px}.nav-item:hover{background:rgba(255,255,255,.08);color:white}.nav-item.active{background:rgba(255,255,255,.15);color:white}.nav-item svg{width:18px;height:18px;flex-shrink:0;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}.sidebar-user{padding:16px;border-top:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px}.sidebar-avatar{width:36px;height:36px;background:linear-gradient(135deg,var(--teal-mid),var(--green));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:white;flex-shrink:0}.sidebar-user-info{flex:1;overflow:hidden}.sidebar-user-name{font-size:.82rem;font-weight:600;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.sidebar-user-role{font-size:.72rem;color:rgba(255,255,255,.45)}.sidebar-logout{width:30px;height:30px;border-radius:7px;background:transparent;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.4);transition:all .2s}.sidebar-logout:hover{background:rgba(255,255,255,.1);color:white}.sidebar-logout svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}.main{margin-left:var(--sidebar-w);flex:1;min-height:100vh;display:flex;flex-direction:column}.topbar{height:var(--header-h);background:white;border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 32px;gap:16px;position:sticky;top:0;z-index:100;box-shadow:var(--shadow-sm)}.topbar-title{font-family:'DM Serif Display',serif;font-size:1.25rem;color:var(--ink);flex:1}.btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s;border:none}.btn-sm{padding:6px 14px;font-size:.78rem}.content{padding:28px 32px;flex:1}.card{background:white;border-radius:14px;border:1px solid var(--border);box-shadow:var(--shadow-sm);overflow:hidden;margin-bottom:20px}.card-header{display:flex;align-items:center;justify-content:space-between;padding:18px 22px;border-bottom:1px solid var(--border)}.card-title{font-family:'DM Serif Display',serif;font-size:1rem;color:var(--ink)}.detail-grid{display:grid;grid-template-columns:1fr 1fr}.detail-row{padding:14px 22px;border-bottom:1px solid #f0f4f4}.detail-row:nth-child(odd){background:#fafcfc}.detail-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:3px}.detail-value{font-size:.9rem;color:var(--ink);font-weight:500}.badge{display:inline-flex;align-items:center;padding:4px 12px;border-radius:100px;font-size:.78rem;font-weight:700}.badge-green{background:var(--green-lt);color:#276749}.badge-amber{background:var(--amber-lt);color:#9c5a0a}.badge-red{background:var(--red-lt);color:#c53030}.badge-blue{background:var(--blue-lt);color:#1a5276}.alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:.875rem;margin-bottom:20px}.alert-success{background:var(--green-lt);border:1px solid rgba(39,174,96,.25);color:#276749}@media(max-width:768px){.detail-grid{grid-template-columns:1fr}.main{margin-left:0}.sidebar{transform:translateX(-100%)}.content{padding:20px 16px}.topbar{padding:0 16px}}</style></head>
<body>
<aside class="sidebar"><a href="{{ route('dashboard') }}" class="sidebar-logo"><div class="sidebar-logo-mark"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg></div><span class="sidebar-logo-text">AfyaLink</span></a>
<nav class="sidebar-nav"><div class="nav-section-label">Main</div><a href="{{ route('dashboard') }}" class="nav-item"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a><div class="nav-section-label">Clinical</div><a href="{{ route('patients.index') }}" class="nav-item"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Patients</a><a href="{{ route('referrals.index') }}" class="nav-item active"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Referrals</a><a href="{{ route('records.index') }}" class="nav-item"><svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>Medical Records</a><div class="nav-section-label">Admin</div><a href="{{ route('facilities.index') }}" class="nav-item"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Facilities</a></nav>
<div class="sidebar-user"><div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? '', 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? '', 0, 1)) }}</div><div class="sidebar-user-info"><div class="sidebar-user-name">{{ Auth::user()->first_name ?? Auth::user()->name }} {{ Auth::user()->last_name ?? '' }}</div><div class="sidebar-user-role">{{ ucfirst(str_replace('_',' ',Auth::user()->role ?? 'user')) }}</div></div><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="sidebar-logout"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button></form></div></aside>
<div class="main">
<header class="topbar">
    <div class="topbar-title">Referral #{{ str_pad($referral->id,4,'0',STR_PAD_LEFT) }}</div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('referrals.edit', $referral) }}" class="btn btn-sm" style="background:var(--teal-lt);color:var(--teal)">Edit</a>
        <a href="{{ route('referrals.index') }}" class="btn btn-sm" style="background:#f0f2f5;color:var(--muted)">← Back</a>
    </div>
</header>
<main class="content">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @php
        $s = $referral->status ?? 'pending';
        if($s==='completed') $bc='badge-green';
        elseif($s==='accepted') $bc='badge-blue';
        elseif($s==='rejected') $bc='badge-red';
        else $bc='badge-amber';
    @endphp
    <div class="card">
        <div class="card-header">
            <span class="card-title">Referral Details</span>
            <span class="badge {{ $bc }}">{{ ucfirst($s) }}</span>
        </div>
        <div class="detail-grid">
            <div class="detail-row"><div class="detail-label">Patient</div><div class="detail-value">{{ optional($referral->patient)->first_name }} {{ optional($referral->patient)->last_name }}</div></div>
            <div class="detail-row"><div class="detail-label">Referred By</div><div class="detail-value">{{ optional($referral->referredBy)->first_name ?? 'N/A' }} {{ optional($referral->referredBy)->last_name ?? '' }}</div></div>
            <div class="detail-row"><div class="detail-label">From Facility</div><div class="detail-value">{{ optional($referral->fromFacility)->name ?? '—' }}</div></div>
            <div class="detail-row"><div class="detail-label">To Facility</div><div class="detail-value">{{ optional($referral->toFacility)->name ?? '—' }}</div></div>
            <div class="detail-row"><div class="detail-label">Date Created</div><div class="detail-value">{{ $referral->created_at->format('d M Y, H:i') }}</div></div>
            <div class="detail-row"><div class="detail-label">Last Updated</div><div class="detail-value">{{ $referral->updated_at->diffForHumans() }}</div></div>
        </div>
        @if($referral->priority)
        <div style="padding:16px 22px;border-top:1px solid var(--border)">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Priority</div>
            <span style="background:{{ $referral->priority === 'emergency' ? '#fee2e2' : ($referral->priority === 'urgent' ? '#fef3c7' : '#dbeafe') }};color:{{ $referral->priority === 'emergency' ? '#dc2626' : ($referral->priority === 'urgent' ? '#d97706' : '#2563eb') }};padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">{{ ucfirst($referral->priority) }}</span>
        </div>
        @endif
        @if($referral->appointment_date)
        <div style="padding:16px 22px;border-top:1px solid var(--border)">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:4px">Expected Appointment</div>
            <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ \Carbon\Carbon::parse($referral->appointment_date)->format('d M Y') }}</div>
        </div>
        @endif
        @if($referral->clinical_summary)
        <div style="padding:16px 22px;border-top:1px solid var(--border)">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Clinical Summary</div>
            <div style="font-size:13px;color:#0f172a;line-height:1.6;background:#f8fafc;padding:10px 12px;border-radius:8px;">{{ $referral->clinical_summary }}</div>
        </div>
        @endif
        <div style="padding:20px 22px;border-top:1px solid var(--border)">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Reason</div>
            <div style="font-size:.9rem;line-height:1.65;color:var(--ink)">{{ $referral->reason ?? '—' }}</div>
        </div>
        @if($referral->notes)
        <div style="padding:16px 22px;border-top:1px solid var(--border);background:#fafcfc">
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px">Notes</div>
            <div style="font-size:.875rem;line-height:1.65;color:var(--muted)">{{ $referral->notes }}</div>
        </div>
        @endif
    </div>
    <!-- Update Status -->
    <div class="card">
        <div class="card-header"><span class="card-title">Update Status</span></div>
        <div style="padding:20px 22px">
            <form method="POST" action="{{ route('referrals.updateStatus', $referral->id) }}" style="display:flex;gap:10px;align-items:flex-end">
                @csrf @method('PATCH')
                <div style="flex:1"><label style="font-size:.82rem;font-weight:600;color:var(--ink);display:block;margin-bottom:6px">New Status</label>
                <select name="status" style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:9px;font-size:.875rem;outline:none;background:white">
                    <option value="pending"   {{ $s==='pending'   ? 'selected':'' }}>Pending</option>
                    <option value="accepted"  {{ $s==='accepted'  ? 'selected':'' }}>Accepted</option>
                    <option value="rejected"  {{ $s==='rejected'  ? 'selected':'' }}>Rejected</option>
                    <option value="completed" {{ $s==='completed' ? 'selected':'' }}>Completed</option>
                </select></div>
                <button type="submit" class="btn btn-sm" style="background:var(--teal);color:white">Update</button>
            </form>
        </div>
    </div>
</main></div></body></html>
