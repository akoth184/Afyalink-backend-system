<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Medical Records — AfyaLink</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue-50:  #f0f6ff;
            --blue-100: #dbeafe;
            --blue-200: #bfdbfe;
            --blue-400: #3b82f6;
            --blue-500: #2563eb;
            --blue-600: #1d4ed8;
            --blue-700: #1e40af;
            --accent:   #2563eb;
            --accent-light: #eff6ff;
            --accent-hover: #1d4ed8;
            --green-400: #34d399;
            --green-500: #10b981;
            --green-light: #ecfdf5;
            --amber-400: #f59e0b;
            --amber-light: #fffbeb;
            --coral-400: #f43f5e;
            --coral-light: #fff1f2;
            --purple-400: #a78bfa;
            --purple-light: #f5f3ff;
            --bg:        #f4f7fb;
            --surface:   #ffffff;
            --surface-2: #f8fafc;
            --border:    #e2e8f0;
            --border-soft: #f1f5f9;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --sidebar-w: 220px;
            --header-h:  64px;
            --radius-2xl: 20px;
            --radius-xl:  16px;
            --radius-lg:  12px;
            --radius-md:  8px;
            --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:  0 4px 16px rgba(37,99,235,0.08), 0 1px 4px rgba(0,0,0,0.06);
            --shadow-card:0 2px 12px rgba(0,0,0,0.06);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text-primary);min-height:100vh;overflow-x:hidden}

        /* Topbar */
        .topbar{
            height:var(--header-h);
            background:var(--surface);
            border-bottom:1px solid var(--border);
            display:flex;align-items:center;
            padding:0 24px;gap:16px;
            position:fixed;top:0;left:0;right:0;z-index:300;
            box-shadow:0 1px 4px rgba(0,0,0,0.05);
        }
        .logo{display:flex;align-items:center;gap:10px;text-decoration:none}
        .logo-mark{
            width:38px;height:38px;border-radius:11px;
            background:linear-gradient(135deg,var(--blue-500),#0ea5e9);
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 2px 10px rgba(37,99,235,0.35);flex-shrink:0;
        }
        .logo-mark svg{width:18px;height:18px;fill:none;stroke:white;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
        .logo-text{font-family:'DM Serif Display',serif;font-size:1.35rem;color:var(--text-primary);letter-spacing:-.01em}
        .logo-text span{color:var(--accent)}
        .topbar-spacer{flex:1}
        .topbar-right{display:flex;align-items:center;gap:14px}
        .topbar-pill{
            display:flex;align-items:center;gap:6px;
            padding:6px 14px;border-radius:100px;
            background:var(--accent-light);
            border:1px solid var(--blue-200);
            font-size:.75rem;font-weight:600;color:var(--accent);
        }
        .status-dot{width:6px;height:6px;border-radius:50%;background:var(--green-500);box-shadow:0 0 6px var(--green-400);animation:pulse-dot 2s ease-in-out infinite}
        @keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.6;transform:scale(1.3)}}
        .topbar-sep{width:1px;height:22px;background:var(--border)}
        .topbar-user{display:flex;align-items:center;gap:9px;cursor:pointer}
        .topbar-user-name{font-size:.83rem;font-weight:600;color:var(--text-secondary)}
        .avatar{
            width:36px;height:36px;border-radius:50%;
            background:linear-gradient(135deg,var(--blue-400),#0ea5e9);
            display:flex;align-items:center;justify-content:center;
            font-size:.68rem;font-weight:700;color:white;
            border:2px solid var(--blue-200);flex-shrink:0;overflow:hidden;
            box-shadow:0 2px 8px rgba(37,99,235,0.2);
        }
        .avatar svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2}

        /* Main */
        .main{padding:calc(var(--header-h) + 30px) 24px 40px;max-width:1200px;margin:0 auto}
        .page-header{margin-bottom:28px}
        .page-title{font-family:'DM Serif Display',serif;font-size:2rem;color:var(--text-primary);margin-bottom:6px}
        .page-subtitle{font-size:.9rem;color:var(--text-muted)}

        /* Cards */
        .card{
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:var(--radius-xl);
            box-shadow:var(--shadow-card);
            overflow:hidden;
            animation:fadeSlideUp .55s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes fadeSlideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
        .card-header{
            display:flex;align-items:center;justify-content:space-between;
            padding:18px 24px 15px;
            border-bottom:1px solid var(--border-soft);
        }
        .card-title{
            font-weight:700;font-size:.95rem;color:var(--text-primary);
            display:flex;align-items:center;gap:9px;
        }
        .card-title-dot{width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0}
        .card-body{padding:0}

        /* Table */
        .table{width:100%;border-collapse:collapse}
        .table th{
            text-align:left;
            padding:12px 20px;
            font-size:.7rem;font-weight:700;
            color:var(--text-muted);
            text-transform:uppercase;
            letter-spacing:.06em;
            background:var(--surface-2);
            border-bottom:1px solid var(--border);
        }
        .table td{
            padding:16px 20px;
            font-size:.85rem;
            color:var(--text-secondary);
            border-bottom:1px solid var(--border-soft);
        }
        .table tr:last-child td{border-bottom:none}
        .table tr:hover td{background:var(--blue-50)}

        /* Empty State */
        .empty-state{padding:60px 24px;text-align:center}
        .empty-icon{
            width:72px;height:72px;border-radius:50%;
            background:var(--accent-light);
            display:flex;align-items:center;justify-content:center;
            margin:0 auto 20px;
        }
        .empty-icon svg{width:32px;height:32px;stroke:var(--accent);fill:none;stroke-width:1.5}
        .empty-title{font-weight:700;font-size:1.1rem;color:var(--text-primary);margin-bottom:6px}
        .empty-text{font-size:.85rem;color:var(--text-muted);max-width:320px;margin:0 auto}

        /* Info Row */
        .info-label{font-size:.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px}
        .info-value{font-size:.88rem;color:var(--text-primary);font-weight:500}

        /* Back Link */
        .back-link{display:inline-flex;align-items:center;gap:6px;color:var(--accent);text-decoration:none;font-size:.85rem;font-weight:500;margin-bottom:16px}
        .back-link:hover{color:var(--accent-hover)}
        .back-link svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}

        /* Buttons */
        .btn{
            display:inline-flex;align-items:center;gap:6px;
            padding:8px 14px;border-radius:var(--radius-md);
            font-size:.75rem;font-weight:600;
            text-decoration:none;transition:all .18s;
            border:none;cursor:pointer;
        }
        .btn svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2}
        .btn-primary{background:var(--accent);color:white}
        .btn-primary:hover{background:var(--accent-hover)}
        .btn-secondary{background:var(--surface-2);color:var(--text-secondary);border:1px solid var(--border)}
        .btn-secondary:hover{background:var(--border-soft)}
        .btn-outline{background:transparent;color:var(--accent);border:1px solid var(--blue-200)}
        .btn-outline:hover{background:var(--blue-50);border-color:var(--accent)}
        .btn-sm{padding:6px 10px;font-size:.7rem}

        /* Responsive */
        @media (max-width:768px){
            .table th:nth-child(3),.table td:nth-child(3),
            .table th:nth-child(4),.table td:nth-child(4){display:none}
            .main{padding:calc(var(--header-h) + 16px) 16px 32px}
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a href="{{ route('dashboard') }}" class="logo">
            <div class="logo-mark">
                <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div class="logo-text">Afya<span>Link</span></div>
        </a>
        <div class="topbar-spacer"></div>
        <div class="topbar-right">
            <div class="topbar-pill">
                <span class="status-dot"></span>
                Patient Portal
            </div>
            <div class="topbar-sep"></div>
            <div class="topbar-user">
                <div class="avatar">
                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <span class="topbar-user-name">{{ Auth::user()->first_name ?? 'Patient' }}</span>
            </div>
        </div>
    </header>

    <main class="main">
        <a href="{{ route('dashboard') }}" class="back-link">
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Dashboard
        </a>

        <div class="page-header">
            <h1 class="page-title">My Medical Records</h1>
            <p class="page-subtitle">View your medical history and download records</p>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-title-dot"></span>
                    Medical History
                </div>
            </div>
            <div class="card-body">
                @if($records->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="empty-title">No Medical Records</h3>
                        <p class="empty-text">You don't have any medical records yet. Records will appear here after your doctor visits.</p>
                    </div>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Record Date</th>
                                <th>Doctor Name</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>
                                        <div class="info-label">Date</div>
                                        <div class="info-value">{{ $record->visit_date->format('M d, Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="info-label">Doctor</div>
                                        <div class="info-value">
                                            @if($record->doctor)
                                                Dr. {{ $record->doctor->first_name }} {{ $record->doctor->last_name }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="info-label">Diagnosis</div>
                                        <div class="info-value">{{ Str::limit($record->diagnosis ?? 'No diagnosis', 60) }}</div>
                                    </td>
                                    <td>
                                        <div class="info-label">Treatment</div>
                                        <div class="info-value">{{ Str::limit($record->treatment_plan ?? 'No treatment', 60) }}</div>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:8px">
                                            <a href="{{ route('records.show', $record->id) }}" class="btn btn-outline btn-sm">
                                                <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                View
                                            </a>
                                            <a href="{{ route('records.download', $record->id) }}" class="btn btn-primary btn-sm">
                                                <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
