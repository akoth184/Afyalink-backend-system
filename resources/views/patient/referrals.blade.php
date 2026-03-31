<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Referrals — AfyaLink</title>
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

        /* Badges */
        .badge{display:inline-flex;align-items:center;padding:4px 12px;border-radius:100px;font-size:.68rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase}
        .badge-pending{background:var(--amber-light);color:var(--amber-400);border:1px solid #fde68a}
        .badge-accepted{background:var(--green-light);color:var(--green-500);border:1px solid #a7f3d0}
        .badge-rejected{background:var(--coral-light);color:var(--coral-400);border:1px solid #fecdd3}
        .badge-completed{background:var(--purple-light);color:var(--purple-400);border:1px solid #ddd6fe}

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

        /* Actions */
        .action-btn{
            display:inline-flex;align-items:center;gap:6px;
            padding:8px 16px;border-radius:var(--radius-md);
            font-size:.8rem;font-weight:600;
            text-decoration:none;transition:all .18s;
            border:none;cursor:pointer;
        }
        .action-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2}
        .btn-primary{background:var(--accent);color:white}
        .btn-primary:hover{background:var(--accent-hover)}
        .btn-secondary{background:var(--surface-2);color:var(--text-secondary);border:1px solid var(--border)}
        .btn-secondary:hover{background:var(--border-soft)}

        /* Responsive */
        @media (max-width:768px){
            .table th:nth-child(3),.table td:nth-child(3),
            .table th:nth-child(5),.table td:nth-child(5){display:none}
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
            <h1 class="page-title">My Referrals</h1>
            <p class="page-subtitle">View your referral status and history</p>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-title-dot"></span>
                    Referral History
                </div>
            </div>
            <div class="card-body">
                @if($referrals->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="empty-title">No Referrals Yet</h3>
                        <p class="empty-text">You don't have any referrals. If you need a referral, please contact your doctor.</p>
                    </div>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Referring Doctor</th>
                                <th>Referring Facility</th>
                                <th>Receiving Hospital</th>
                                <th>Reason</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referrals as $referral)
                                <tr>
                                    <td>
                                        <div class="info-label">Name</div>
                                        <div class="info-value">{{ $referral->patient->first_name ?? 'N/A' }} {{ $referral->patient->last_name ?? '' }}</div>
                                    </td>
                                    <td>
                                        <div class="info-label">Doctor</div>
                                        <div class="info-value">
                                            @php
                                              $referredByUser = \App\Models\User::find($referral->referred_by);
                                            @endphp
                                            @if($referredByUser && $referredByUser->role === 'doctor')
                                              Dr. {{ $referredByUser->first_name }} {{ $referredByUser->last_name }}
                                            @elseif($referredByUser && in_array($referredByUser->role, ['hospital','facility']))
                                              Hospital Transfer
                                            @else
                                              AfyaLink System
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="info-label">Facility</div>
                                        <div class="info-value">{{ $referral->fromFacility->name ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="info-label">Hospital</div>
                                        <div class="info-value">{{ $referral->toFacility->name ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="info-label">Reason</div>
                                        <div class="info-value">{{ Str::limit($referral->reason, 50) }}</div>
                                    </td>
                                    <td>
                                        <div class="info-label">Date</div>
                                        <div class="info-value">{{ $referral->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td>
                                        @switch($referral->status)
                                            @case('pending')
                                                <span class="badge badge-pending">Pending</span>
                                                @break
                                            @case('accepted')
                                                <span class="badge badge-accepted">Accepted</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge-rejected">Rejected</span>
                                                @break
                                            @case('completed')
                                                <span class="badge badge-completed">Completed</span>
                                                @break
                                            @default
                                                <span class="badge badge-pending">{{ $referral->status }}</span>
                                        @endswitch
                                        @if($referral->priority)
                                        <span style="background:{{ $referral->priority === 'emergency' ? '#fee2e2' : ($referral->priority === 'urgent' ? '#fef3c7' : '#dbeafe') }};color:{{ $referral->priority === 'emergency' ? '#dc2626' : ($referral->priority === 'urgent' ? '#d97706' : '#2563eb') }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ ucfirst($referral->priority) }}</span>
                                        @endif
                                        @if($referral->status === 'rejected' && $referral->rejection_reason)
                                        <div style="margin-top:8px;background:#fee2e2;border-left:3px solid #dc2626;padding:8px 12px;border-radius:0 6px 6px 0;">
                                          <div style="font-size:10px;font-weight:700;color:#dc2626;margin-bottom:3px;">Hospital Response</div>
                                          <div style="font-size:12px;color:#991b1b;line-height:1.5;">{{ $referral->rejection_reason }}</div>
                                        </div>
                                        @endif
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
