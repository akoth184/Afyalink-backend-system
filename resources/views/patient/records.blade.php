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
                @forelse(\App\Models\MedicalRecord::where('patient_id', Auth::id())->with('doctor')->latest()->get() as $record)
<div style="display:flex;align-items:flex-start;gap:12px;padding:16px 0;border-bottom:1px solid #f1f5f9;">
  <div style="width:36px;height:36px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;font-weight:700;color:#1d4ed8;">R</div>
  <div style="flex:1;">
    <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $record->chief_complaint ?? 'Medical Record' }}</div>
    @if($record->diagnosis)
    <div style="font-size:12px;color:#64748b;margin-top:3px;"><strong>Diagnosis:</strong> {{ $record->diagnosis }}</div>
    @endif
    @if($record->treatment_plan)
    <div style="font-size:12px;color:#64748b;margin-top:3px;"><strong>Treatment:</strong> {{ $record->treatment_plan }}</div>
    @endif
    @if($record->medications)
    <div style="font-size:12px;color:#64748b;margin-top:3px;"><strong>Prescription:</strong> {{ $record->medications }}</div>
    @endif
    <div style="font-size:11px;color:#94a3b8;margin-top:5px;">
      {{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y') : 'No date' }}
      · Dr. {{ optional($record->doctor)->first_name ?? 'N/A' }} {{ optional($record->doctor)->last_name ?? '' }}
      · <span style="background:{{ $record->status === 'finalized' ? '#dcfce7' : '#fef3c7' }};color:{{ $record->status === 'finalized' ? '#16a34a' : '#d97706' }};padding:2px 8px;border-radius:20px;font-size:10px;font-weight:600;">{{ ucfirst($record->status ?? 'draft') }}</span>
    </div>
  </div>
  <div style="display:flex;gap:6px;flex-shrink:0;">
    <a href="{{ route('records.show', $record->id) }}" style="background:#dbeafe;color:#1d4ed8;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">View</a>
    <a href="{{ route('patient.record.download', $record->id) }}" style="background:#2563eb;color:white;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">PDF</a>
    @if($record->file_path)
    <a href="{{ route('patient.record.file', $record->id) }}" style="background:#dcfce7;color:#16a34a;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;">File</a>
    @endif
  </div>
</div>
@empty
<div style="text-align:center;padding:48px 20px;">
  <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:8px;">No medical records yet</div>
  <div style="font-size:13px;color:#94a3b8;">Your records will appear here after doctor visits</div>
</div>
@endforelse

<!-- LAB TEST RESULTS -->
<div style="margin-top:24px;">
  <div style="font-size:14px;font-weight:600;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
    <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;display:inline-block;"></span>
    Lab Test Results
  </div>
  @forelse(\App\Models\LabTest::where('patient_id', Auth::id())->with('doctor')->latest()->get() as $test)
  <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 0;border-bottom:1px solid #f1f5f9;">
    <div style="width:36px;height:36px;border-radius:8px;background:{{ $test->status === 'completed' ? '#dcfce7' : '#fef3c7' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="16" height="16" fill="none" stroke="{{ $test->status === 'completed' ? '#16a34a' : '#d97706' }}" stroke-width="2" viewBox="0 0 24 24"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v11m0 0H5a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2h-4m-6 0h6"/></svg>
    </div>
    <div style="flex:1;">
      <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $test->test_name }}</div>
      <div style="font-size:11px;color:#94a3b8;margin-top:2px;">
        {{ ucfirst($test->test_category) }} ·
        Dr. {{ optional($test->doctor)->first_name ?? 'N/A' }} {{ optional($test->doctor)->last_name ?? '' }} ·
        {{ \Carbon\Carbon::parse($test->requested_date)->format('d M Y') }}
      </div>
      @if($test->result_notes)
      <div style="font-size:12px;color:#0f172a;margin-top:6px;background:#f0fdf4;padding:8px 12px;border-radius:6px;border-left:3px solid #16a34a;">
        <div style="font-size:10px;font-weight:700;color:#16a34a;margin-bottom:2px;">RESULT</div>
        {{ $test->result_notes }}
      </div>
      @endif
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;flex-shrink:0;align-items:flex-end;">
      <span style="background:{{ $test->status === 'completed' ? '#dcfce7' : '#fef3c7' }};color:{{ $test->status === 'completed' ? '#16a34a' : '#d97706' }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ $test->status === 'completed' ? 'Results Ready' : 'Pending' }}</span>
      @if($test->result_file)
      <a href="{{ route('lab-tests.download', $test->id) }}" style="background:#2563eb;color:white;padding:5px 10px;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;margin-top:4px;">Download</a>
      @endif
    </div>
  </div>
  @empty
  <div style="text-align:center;padding:20px;color:#94a3b8;font-size:13px;">No lab tests requested yet</div>
  @endforelse
</div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
