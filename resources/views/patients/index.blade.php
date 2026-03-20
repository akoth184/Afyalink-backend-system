<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patients — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal:#0d6e6e;--teal-mid:#0f8080;--teal-lt:#e6f4f4;--teal-dark:#0a5555;
            --green:#27ae60;--green-lt:#e8f8ef;--amber:#e67e22;--amber-lt:#fef3e7;
            --red:#e53e3e;--red-lt:#fff5f5;--blue:#3182ce;--blue-lt:#ebf8ff;
            --ink:#1a1f2e;--muted:#5a6275;--border:#dde4e4;
            --sidebar-w:260px;--header-h:64px;
            --shadow-sm:0 1px 4px rgba(0,0,0,.06);--shadow-md:0 4px 16px rgba(0,0,0,.08);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:#f1f5f5;color:var(--ink);min-height:100vh;display:flex}
        .sidebar{width:var(--sidebar-w);background:var(--teal-dark);min-height:100vh;position:fixed;left:0;top:0;bottom:0;display:flex;flex-direction:column;z-index:200;transition:transform .3s}
        .sidebar-logo{padding:22px 24px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;text-decoration:none}
        .sidebar-logo-mark{width:36px;height:36px;background:rgba(255,255,255,.15);border-radius:9px;display:flex;align-items:center;justify-content:center}
        .sidebar-logo-mark svg{width:20px;height:20px;fill:white}
        .sidebar-logo-text{font-family:'DM Serif Display',serif;font-size:1.25rem;color:white}
        .sidebar-nav{flex:1;padding:16px 12px;overflow-y:auto}
        .nav-section-label{font-size:.65rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.35);padding:12px 12px 6px}
        .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:9px;text-decoration:none;font-size:.875rem;font-weight:500;color:rgba(255,255,255,.65);transition:all .2s;margin-bottom:2px}
        .nav-item:hover{background:rgba(255,255,255,.08);color:white}
        .nav-item.active{background:rgba(255,255,255,.15);color:white}
        .nav-item svg{width:18px;height:18px;flex-shrink:0;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
        .sidebar-user{padding:16px;border-top:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px}
        .sidebar-avatar{width:36px;height:36px;background:linear-gradient(135deg,var(--teal-mid),var(--green));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:white;flex-shrink:0}
        .sidebar-user-info{flex:1;overflow:hidden}
        .sidebar-user-name{font-size:.82rem;font-weight:600;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .sidebar-user-role{font-size:.72rem;color:rgba(255,255,255,.45)}
        .sidebar-logout{width:30px;height:30px;border-radius:7px;background:transparent;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.4);transition:all .2s}
        .sidebar-logout:hover{background:rgba(255,255,255,.1);color:white}
        .sidebar-logout svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
        .main{margin-left:var(--sidebar-w);flex:1;min-height:100vh;display:flex;flex-direction:column}
        .topbar{height:var(--header-h);background:white;border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 32px;gap:16px;position:sticky;top:0;z-index:100;box-shadow:var(--shadow-sm)}
        .topbar-title{font-family:'DM Serif Display',serif;font-size:1.25rem;color:var(--ink);flex:1}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s;border:none}
        .btn svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round}
        .btn-primary{background:var(--teal);color:white;box-shadow:0 2px 8px rgba(13,110,110,.25)}
        .btn-primary:hover{background:var(--teal-mid);transform:translateY(-1px)}
        .btn-sm{padding:6px 14px;font-size:.78rem}
        .btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(229,62,62,.2)}
        .btn-danger:hover{background:var(--red);color:white}
        .content{padding:28px 32px;flex:1}
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
        .td-sub{font-size:.75rem;color:var(--muted);margin-top:2px}
        .avatar-sm{width:32px;height:32px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:white;margin-right:10px;flex-shrink:0;vertical-align:middle}
        .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:100px;font-size:.72rem;font-weight:700}
        .badge-green{background:var(--green-lt);color:#276749}
        .badge-blue{background:var(--blue-lt);color:#1a5276}
        .empty-state{text-align:center;padding:60px 24px}
        .empty-state svg{width:48px;height:48px;stroke:var(--border);fill:none;stroke-width:1.5;margin-bottom:16px}
        .empty-state h3{font-family:'DM Serif Display',serif;font-size:1.1rem;color:var(--ink);margin-bottom:8px}
        .empty-state p{font-size:.875rem;color:var(--muted);margin-bottom:20px}
        .search-bar{display:flex;gap:10px;margin-bottom:20px}
        .search-bar input{flex:1;padding:10px 16px;border:1.5px solid var(--border);border-radius:9px;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;transition:border-color .2s}
        .search-bar input:focus{border-color:var(--teal)}
        .pagination{display:flex;justify-content:center;gap:6px;padding:16px;border-top:1px solid var(--border)}
        .pagination a,.pagination span{padding:6px 12px;border-radius:7px;font-size:.82rem;text-decoration:none;color:var(--muted);border:1px solid var(--border)}
        .pagination .active span{background:var(--teal);color:white;border-color:var(--teal)}
        .mobile-toggle{display:none;position:fixed;top:14px;left:14px;z-index:300;width:38px;height:38px;background:var(--teal);border:none;border-radius:9px;cursor:pointer;align-items:center;justify-content:center}
        .mobile-toggle svg{width:20px;height:20px;stroke:white;fill:none;stroke-width:2.2;stroke-linecap:round}
        @media(max-width:768px){.sidebar{transform:translateX(-100%)}.sidebar.open{transform:none}.main{margin-left:0}.mobile-toggle{display:flex}.content{padding:20px 16px}.topbar{padding:0 16px 0 56px}}
    </style>
</head>
<body>
<button class="mobile-toggle" id="sidebarToggle">
    <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
</button>
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-mark"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg></div>
        <span class="sidebar-logo-text">AfyaLink</span>
    </a>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-item"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a>
        <div class="nav-section-label">Clinical</div>
        <a href="{{ route('patients.index') }}" class="nav-item active"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Patients</a>
        <a href="{{ route('referrals.index') }}" class="nav-item"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Referrals</a>
        <a href="{{ route('records.index') }}" class="nav-item"><svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>Medical Records</a>
        <div class="nav-section-label">Admin</div>
        <a href="{{ route('facilities.index') }}" class="nav-item"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Facilities</a>
    </nav>
    <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? '', 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? '', 0, 1)) }}</div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->first_name ?? Auth::user()->name }} {{ Auth::user()->last_name ?? '' }}</div>
            <div class="sidebar-user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'user')) }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="sidebar-logout" title="Sign out"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button>
        </form>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:199"></div>

<div class="main">
    <header class="topbar">
        <div class="topbar-title">Patients</div>

    </header>
    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <span class="card-title">All Patients ({{ $patients->total() ?? $patients->count() }})</span>
                <form method="GET" action="{{ route('patients.index') }}" style="display:flex;gap:8px">
                    <input type="text" name="search" placeholder="Search name, email..." value="{{ request('search') }}" style="padding:6px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:.82rem;outline:none">
                    <button type="submit" class="btn btn-sm" style="background:var(--teal-lt);color:var(--teal)">Search</button>
                </form>
            </div>
            @if($patients->isEmpty())
                <div class="empty-state">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <h3>No patients found</h3>
                    <p>No patients match your search.</p>
                </div>
            @else
                <table class="data-table">
                    <thead><tr><th>Patient</th><th>Contact</th><th>Gender</th><th>Facility</th><th>Registered</th><th></th></tr></thead>
                    <tbody>
                        @foreach($patients as $patient)
                        @php $colors=['#0d6e6e','#27ae60','#3182ce','#e67e22','#8e44ad']; $c=$colors[$loop->index%5]; @endphp
                        <tr>
                            <td>
                                <span class="avatar-sm" style="background:{{ $c }}">{{ strtoupper(substr($patient->first_name ?? 'P', 0, 1)) }}</span>
                                <div style="display:inline-block;vertical-align:middle">
                                    <div class="td-name">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                    <div class="td-sub">#{{ str_pad($patient->id,4,'0',STR_PAD_LEFT) }}</div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:.82rem">{{ $patient->email ?? '—' }}</div>
                                <div class="td-sub">{{ $patient->phone ?? '—' }}</div>
                            </td>
                            <td><span class="badge badge-blue">{{ ucfirst($patient->gender ?? 'N/A') }}</span></td>
                            <td style="font-size:.82rem;color:var(--muted)">{{ optional($patient->facility)->name ?? '—' }}</td>
                            <td style="font-size:.78rem;color:var(--muted)">{{ $patient->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm" style="background:var(--teal-lt);color:var(--teal)">View</a>
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm" style="background:#f0f2f5;color:var(--muted)">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination">{{ $patients->links() }}</div>
            @endif
        </div>
    </main>
</div>
<script>
    const sidebar=document.getElementById('sidebar'),overlay=document.getElementById('sidebarOverlay'),toggle=document.getElementById('sidebarToggle');
    toggle.addEventListener('click',()=>{sidebar.classList.toggle('open');overlay.style.display=sidebar.classList.contains('open')?'block':'none'});
    overlay.addEventListener('click',()=>{sidebar.classList.remove('open');overlay.style.display='none'});
</script>
</body>
</html>
