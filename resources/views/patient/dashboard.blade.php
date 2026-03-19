<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard — AfyaLink</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--blue-50:#f0f6ff;--blue-100:#dbeafe;--blue-200:#bfdbfe;--blue-400:#3b82f6;--blue-500:#2563eb;--blue-600:#1d4ed8;--blue-700:#1e40af;--accent:#2563eb;--accent-light:#eff6ff;--accent-hover:#1d4ed8;--green-400:#34d399;--green-500:#10b981;--green-light:#ecfdf5;--amber-400:#f59e0b;--amber-light:#fffbeb;--coral-400:#f43f5e;--coral-light:#fff1f2;--purple-400:#a78bfa;--purple-light:#f5f3ff;--bg:#f4f7fb;--surface:#ffffff;--surface-2:#f8fafc;--border:#e2e8f0;--border-soft:#f1f5f9;--text-primary:#0f172a;--text-secondary:#475569;--text-muted:#94a3b8;--sidebar-w:220px;--header-h:64px;--radius-2xl:20px;--radius-xl:16px;--radius-lg:12px;--radius-md:8px;--shadow-sm:0 1px 3px rgba(0,0,0,0.06),0 1px 2px rgba(0,0,0,0.04);--shadow-md:0 4px 16px rgba(37,99,235,0.08),0 1px 4px rgba(0,0,0,0.06);--shadow-lg:0 8px 32px rgba(37,99,235,0.12),0 2px 8px rgba(0,0,0,0.06);--shadow-card:0 2px 12px rgba(0,0,0,0.06)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text-primary);min-height:100vh;overflow-x:hidden}
.topbar{height:var(--header-h);background:var(--surface);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 24px;gap:16px;position:fixed;top:0;left:0;right:0;z-index:300;box-shadow:0 1px 4px rgba(0,0,0,0.05)}
.logo{display:flex;align-items:center;gap:10px;text-decoration:none}
.logo-mark{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--blue-500),#0ea5e9);display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(37,99,235,0.35)}
.logo-mark svg{width:18px;height:18px;fill:none;stroke:white;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.logo-text{font-family:'DM Serif Display',serif;font-size:1.35rem;color:var(--text-primary)}
.logo-text span{color:var(--accent)}
.topbar-spacer{flex:1}
.topbar-right{display:flex;align-items:center;gap:14px}
.topbar-pill{display:flex;align-items:center;gap:6px;padding:6px 14px;border-radius:100px;background:var(--accent-light);border:1px solid var(--blue-200);font-size:.75rem;font-weight:600;color:var(--accent)}
.status-dot{width:6px;height:6px;border-radius:50%;background:var(--green-500);box-shadow:0 0 6px var(--green-400);animation:pulse-dot 2s ease-in-out infinite}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.6;transform:scale(1.3)}}
.topbar-sep{width:1px;height:22px;background:var(--border)}
.topbar-user{display:flex;align-items:center;gap:9px;cursor:pointer}
.topbar-user-name{font-size:.83rem;font-weight:600;color:var(--text-secondary)}
.avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--blue-400),#0ea5e9);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:white;border:2px solid var(--blue-200)}
.topbar-logout{display:flex;align-items:center;gap:5px;font-size:.75rem;font-weight:500;color:var(--text-muted);background:none;border:none;cursor:pointer;font-family:inherit;transition:color .2s;padding:6px 10px;border-radius:var(--radius-md)}
.topbar-logout:hover{color:var(--coral-400)}
.topbar-logout svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2}
.layout{display:flex;padding-top:var(--header-h);min-height:100vh}
.sidebar{width:var(--sidebar-w);background:var(--surface);border-right:1px solid var(--border);position:fixed;left:0;top:var(--header-h);bottom:0;display:flex;flex-direction:column;z-index:200;padding:20px 12px;overflow-y:auto}
.nav-section-label{font-size:.6rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text-muted);padding:0 10px;margin:14px 0 6px}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:var(--radius-md);text-decoration:none;font-size:.83rem;font-weight:500;color:var(--text-secondary);transition:all .18s;margin-bottom:2px;border:1px solid transparent;cursor:pointer;background:none;width:100%;font-family:inherit;position:relative}
.nav-item:hover{background:var(--blue-50);color:var(--accent)}
.nav-item.active{background:var(--accent);color:white;border-color:var(--accent);box-shadow:0 2px 10px rgba(37,99,235,0.3)}
.nav-item svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0}
.nav-badge{margin-left:auto;font-size:.58rem;font-weight:700;padding:2px 7px;border-radius:100px;background:var(--coral-400);color:white}
.nav-item.active .nav-badge{background:rgba(255,255,255,0.3);color:white}
.nav-divider{height:1px;background:var(--border);margin:10px 4px}
.main{margin-left:var(--sidebar-w);flex:1;padding:28px 28px 48px;min-height:calc(100vh - var(--header-h))}
.welcome-banner{position:relative;border-radius:var(--radius-2xl);overflow:hidden;margin-bottom:24px;padding:28px 32px;background:linear-gradient(130deg,#1e40af 0%,#2563eb 45%,#3b82f6 80%,#0ea5e9 100%);border:none;box-shadow:0 8px 32px rgba(37,99,235,0.35);display:flex;align-items:center;justify-content:space-between;gap:20px}
.welcome-banner::before{content:'';position:absolute;right:-80px;top:-80px;width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,0.1) 0%,transparent 70%);pointer-events:none}
.welcome-banner::after{content:'';position:absolute;bottom:0;left:0;right:0;height:1px;background:rgba(255,255,255,0.2)}
.welcome-ecg{position:absolute;bottom:12px;right:32px;opacity:.12;pointer-events:none}
.welcome-greeting{font-size:.72rem;color:rgba(255,255,255,0.75);font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-bottom:6px}
.welcome-name{font-family:'DM Serif Display',serif;font-size:2rem;color:white;line-height:1.1;margin-bottom:7px}
.welcome-name em{font-style:italic;color:rgba(255,255,255,0.85)}
.welcome-sub{font-size:.8rem;color:rgba(255,255,255,0.7)}
.welcome-actions{display:flex;gap:10px;flex-wrap:wrap;flex-shrink:0}
.btn{display:inline-flex;align-items:center;gap:7px;padding:10px 20px;border-radius:var(--radius-md);font-family:'DM Sans',sans-serif;font-size:.8rem;font-weight:600;cursor:pointer;text-decoration:none;border:none;transition:all .18s;white-space:nowrap}
.btn svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round}
.btn-white{background:white;color:var(--accent);box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.btn-white:hover{background:var(--blue-50);transform:translateY(-1px);box-shadow:0 4px 16px rgba(0,0,0,0.15)}
.btn-white-outline{background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.3);backdrop-filter:blur(4px)}
.btn-white-outline:hover{background:rgba(255,255,255,0.25)}
.btn-primary{background:var(--accent);color:white;box-shadow:0 2px 10px rgba(37,99,235,0.3)}
.btn-primary:hover{background:var(--accent-hover);transform:translateY(-1px);box-shadow:0 4px 16px rgba(37,99,235,0.4)}
.btn-outline{background:transparent;color:var(--accent);border:1px solid var(--blue-200)}
.btn-outline:hover{background:var(--blue-50);border-color:var(--accent)}
.btn-ghost{background:var(--surface-2);color:var(--text-secondary);border:1px solid var(--border)}
.btn-ghost:hover{color:var(--text-primary);background:var(--blue-50)}
.btn-success{background:var(--green-light);color:var(--green-500);border:1px solid #a7f3d0}
.btn-success:hover{background:#d1fae5}
.btn-sm{padding:6px 13px;font-size:.72rem}
.btn-full{width:100%;justify-content:center}
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-xl);padding:20px;box-shadow:var(--shadow-card);transition:all .22s;position:relative;overflow:hidden}
.stat-card:hover{border-color:var(--blue-200);transform:translateY(-3px);box-shadow:var(--shadow-lg)}
.stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center}
.stat-icon svg{width:19px;height:19px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.si-blue{background:var(--accent-light);color:var(--accent)}
.si-amber{background:var(--amber-light);color:var(--amber-400)}
.si-green{background:var(--green-light);color:var(--green-500)}
.si-purple{background:var(--purple-light);color:var(--purple-400)}
.stat-delta{font-size:.62rem;font-weight:700;padding:3px 9px;border-radius:100px}
.delta-up{background:var(--green-light);color:var(--green-500)}
.delta-down{background:var(--amber-light);color:var(--amber-400)}
.stat-num{font-family:'DM Serif Display',serif;font-size:2.2rem;color:var(--text-primary);line-height:1;display:block;margin-bottom:4px;letter-spacing:-.02em}
.stat-label{font-size:.72rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.05em}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-xl);box-shadow:var(--shadow-card);overflow:hidden}
.card:hover{box-shadow:var(--shadow-md)}
.card-header{display:flex;align-items:center;justify-content:space-between;padding:16px 22px 13px;border-bottom:1px solid var(--border-soft)}
.card-title{font-weight:700;font-size:.93rem;color:var(--text-primary);display:flex;align-items:center;gap:9px}
.card-title-dot{width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0}
.card-body{padding:18px 22px}
.grid-main{display:grid;grid-template-columns:1fr 300px;gap:20px;margin-bottom:20px}
.med-summary-box{background:var(--blue-50);border:1px solid var(--blue-100);border-radius:var(--radius-lg);padding:16px;margin-bottom:16px}
.med-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--blue-100);font-size:.83rem}
.med-row:last-child{border-bottom:none}
.med-label{font-weight:700;color:var(--text-secondary);min-width:140px;font-size:.74rem;letter-spacing:.02em}
.med-val{color:var(--text-primary);font-weight:500}
.btn-download-sm{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;margin-left:auto;background:var(--blue-50);color:var(--accent);border-radius:var(--radius);transition:all .2s}.btn-download-sm:hover{background:var(--accent);color:#fff}
.blood-badge{display:inline-flex;align-items:center;padding:3px 12px;border-radius:100px;background:var(--coral-light);color:var(--coral-400);font-size:.72rem;font-weight:700;border:1px solid #fecdd3}
.service-item{display:flex;align-items:center;gap:13px;padding:12px 6px;border-bottom:1px solid var(--border-soft);cursor:pointer;transition:all .18s;border-radius:var(--radius-md)}
.service-item:last-child{border-bottom:none}
.service-item:hover{background:var(--blue-50);padding-left:12px}
.svc-icon{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.svc-icon svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.svc-text{flex:1}
.svc-name{font-size:.83rem;font-weight:600;color:var(--text-primary)}
.svc-sub{font-size:.68rem;color:var(--text-muted);margin-top:2px}
.svc-badge{font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:100px;background:var(--accent);color:white}
.svc-arrow svg{width:14px;height:14px;stroke:var(--text-muted);fill:none;stroke-width:2.5;stroke-linecap:round}
.pay-item{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border-soft)}
.pay-item:last-child{border-bottom:none}
.pay-icon{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:900;flex-shrink:0}
.pay-ok{background:var(--green-light);color:var(--green-500);border:1px solid #a7f3d0}
.pay-fail{background:var(--coral-light);color:var(--coral-400);border:1px solid #fecdd3}
.pay-desc{flex:1;min-width:0}
.pay-desc strong{font-size:.8rem;font-weight:600;color:var(--text-primary);display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pay-desc span{font-size:.65rem;color:var(--text-muted)}
.pay-amount{font-size:.8rem;font-weight:700;white-space:nowrap}
.amount-ok{color:var(--green-500)}
.amount-fail{color:var(--coral-400)}
.nearby-item{display:flex;align-items:center;gap:10px;padding:9px 4px;border-bottom:1px solid var(--border-soft);cursor:pointer;transition:all .18s;border-radius:8px}
.nearby-item:last-child{border-bottom:none}
.nearby-item:hover{background:var(--blue-50);padding-left:8px}
.nearby-pin{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.nearby-pin svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round}
.nearby-name{font-size:.8rem;font-weight:600;color:var(--text-primary)}
.nearby-sub{font-size:.64rem;color:var(--text-muted);margin-top:1px}
.nearby-dist{font-size:.73rem;font-weight:700;color:var(--accent);margin-left:auto;white-space:nowrap}
.download-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;border-radius:var(--radius-md);margin-top:14px;background:var(--accent);color:white;font-family:'DM Sans',sans-serif;font-size:.83rem;font-weight:700;border:none;cursor:pointer;transition:all .18s;letter-spacing:.02em;box-shadow:0 2px 10px rgba(37,99,235,0.3)}
.download-btn:hover{background:var(--accent-hover);transform:translateY(-1px);box-shadow:0 4px 16px rgba(37,99,235,0.4)}
.download-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.2;stroke-linecap:round}
.modal-backdrop{display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);backdrop-filter:blur(6px);z-index:600;align-items:center;justify-content:center}
.modal-backdrop.open{display:flex}
.modal{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-2xl);width:100%;max-width:480px;margin:20px;box-shadow:0 24px 64px rgba(0,0,0,0.18);animation:modalIn .3s cubic-bezier(.34,1.3,.64,1);overflow:hidden;max-height:calc(100vh - 40px);overflow-y:auto}
@keyframes modalIn{from{opacity:0;transform:translateY(20px) scale(.96)}to{opacity:1;transform:none}}
.modal-header{padding:20px 22px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:13px;position:sticky;top:0;background:var(--surface);z-index:2}
.modal-hicon{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.modal-title{font-family:'DM Serif Display',serif;font-size:1.1rem;color:var(--text-primary)}
.modal-sub{font-size:.72rem;color:var(--text-muted);margin-top:2px}
.modal-close{margin-left:auto;width:30px;height:30px;border:1px solid var(--border);background:var(--surface-2);border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-muted);transition:all .18s;flex-shrink:0}
.modal-close:hover{background:var(--coral-light);border-color:#fecdd3;color:var(--coral-400)}
.modal-close svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2.5}
.modal-body{padding:18px 22px}
.modal-footer{padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:9px;justify-content:flex-end;position:sticky;bottom:0;background:var(--surface)}
.mpesa-brand{background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0;border-radius:13px;padding:14px 16px;display:flex;align-items:center;gap:13px;margin-bottom:18px}
.mpesa-m{font-family:'DM Serif Display',serif;font-size:2rem;color:var(--green-500);line-height:1}
.mpesa-info strong{color:var(--text-primary);font-size:.88rem;display:block;margin-bottom:2px}
.mpesa-info span{color:var(--text-muted);font-size:.7rem}
.amount-display{background:var(--green-light);border:1px solid #a7f3d0;border-radius:12px;padding:16px;text-align:center;margin-bottom:14px}
.amount-label{font-size:.62rem;font-weight:700;color:var(--green-500);letter-spacing:.1em;text-transform:uppercase}
.amount-big{font-family:'DM Serif Display',serif;font-size:2.2rem;color:var(--text-primary);line-height:1.1;margin-top:3px}
.form-group{margin-bottom:14px}
.form-label{display:block;font-size:.73rem;font-weight:600;color:var(--text-secondary);margin-bottom:6px;letter-spacing:.02em}
.form-input{width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);font-family:'DM Sans',sans-serif;font-size:.84rem;color:var(--text-primary);background:var(--surface);outline:none;transition:all .18s}
.form-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(37,99,235,0.1)}
.form-input::placeholder{color:var(--text-muted)}
.form-select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px}
.input-prefix-wrap{display:flex;align-items:center;border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;transition:all .18s}
.input-prefix-wrap:focus-within{border-color:var(--accent);box-shadow:0 0 0 3px rgba(37,99,235,0.1)}
.input-prefix{padding:10px 12px;background:var(--surface-2);border-right:1px solid var(--border);font-size:.72rem;font-weight:700;color:var(--accent);white-space:nowrap}
.input-prefix-wrap input{flex:1;padding:10px 12px;border:none;outline:none;font-family:'DM Sans',sans-serif;font-size:.84rem;color:var(--text-primary);background:var(--surface)}
.status-pill{display:inline-flex;align-items:center;gap:7px;padding:7px 16px;border-radius:100px;font-size:.74rem;font-weight:600}
.status-waiting{background:var(--amber-light);color:var(--amber-400);border:1px solid #fde68a}
.status-success{background:var(--green-light);color:var(--green-500);border:1px solid #a7f3d0}
.status-failed{background:var(--coral-light);color:var(--coral-400);border:1px solid #fecdd3}
.status-dot-pill{width:7px;height:7px;border-radius:50%;background:currentColor}
.status-waiting .status-dot-pill{animation:pulse-dot 1.2s ease-in-out infinite}
.stk-bar{height:4px;border-radius:100px;background:var(--border);overflow:hidden;margin:12px 0}
.stk-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--accent),var(--green-500));width:0%;transition:width .4s}
.expire-hint{background:var(--amber-light);border:1px solid #fde68a;border-radius:var(--radius-md);padding:10px 13px;font-size:.72rem;color:var(--amber-400);margin-top:13px;display:flex;align-items:center;gap:7px}
.expire-hint svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0}
.txn-receipt{background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-md);padding:13px;margin-top:13px}
.txn-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid var(--border-soft);font-size:.76rem}
.txn-row:last-child{border-bottom:none}
.txn-row span{color:var(--text-muted)}
.txn-row strong{color:var(--text-primary);font-family:monospace;font-size:.74rem}
.daraja-error{background:var(--coral-light);border:1px solid #fecdd3;border-radius:var(--radius-md);padding:11px 13px;font-size:.76rem;color:var(--coral-400);margin-top:11px;display:none}
.daraja-error.show{display:flex;gap:7px;align-items:flex-start}
.daraja-error svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;margin-top:1px}
</style>
</head>
<body>
<header class="topbar">
<a href="{{ route('dashboard') }}" class="logo"><div class="logo-mark"><svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div><div class="logo-text">Afya<span>Link</span></div></a>
<div class="topbar-spacer"></div>
<div class="topbar-right">
<div class="topbar-pill"><span class="status-dot"></span>Kenya</div>
<div class="topbar-sep"></div>
<form method="POST" action="{{ route('logout') }}" style="display:inline;">@csrf<button type="submit" class="topbar-logout"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Logout</button></form>
<div class="topbar-sep"></div>
<div class="topbar-user"><span class="topbar-user-name">{{ Auth::user()->first_name ?? 'User' }}</span><div class="avatar">{{ strtoupper(substr(Auth::user()->first_name ?? 'G', 0, 1)) }}</div></div>
</div>
</header>
<div class="layout">
<aside class="sidebar">
<div class="nav-section-label">Menu</div>
<a href="{{ route('dashboard') }}" class="nav-item active"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>Dashboard</a>
<a href="{{ route('patient.records') }}" class="nav-item"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Medical Records</a>
<a href="{{ route('patient.referrals') }}" class="nav-item"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Referrals<span class="nav-badge">2</span></a>
<a href="{{ route('patient.nearby-hospitals') }}" class="nav-item"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Hospitals Nearby</a>
<div class="nav-divider"></div>
<div class="nav-section-label">Account</div>
<a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Profile</a>
<a href="{{ route('profile') }}" class="nav-item"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2v2M12 20v2M2 12h2m16 0h2"/></svg>Settings</a>
<div style="margin-top:auto;padding-top:16px;border-top:1px solid var(--border)"><form method="POST" action="{{ route('logout') }}" style="display:inline;">@csrf<button type="submit" class="nav-item" style="color:#ef4444;border:none;background:none;cursor:pointer;width:100%;text-align:left;"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Logout</button></form></div>
</aside>
<main class="main">
<div class="welcome-banner">
<svg class="welcome-ecg" width="200" height="50" viewBox="0 0 200 50"><polyline class="ecg-path" points="0,25 20,25 30,25 40,5 50,45 60,25 80,25 90,20 100,25 120,25 130,25 140,5 150,45 160,25 180,25 200,25" fill="none" stroke="white" stroke-width="1.5"/></svg>
<div><div class="welcome-greeting">{{ now()->format('l, d F Y') }}</div><div class="welcome-name">Welcome back, <em>{{ Auth::user()->first_name ?? 'User' }}</em> 👋</div><div class="welcome-sub">Here's your health summary and today's updates</div>@if(Auth::user()->patient_id)<div style="margin-top:8px;font-size:.75rem;color:rgba(255,255,255,0.6)"><span style="background:rgba(255,255,255,0.15);padding:2px 8px;border-radius:4px">Patient ID: {{ Auth::user()->patient_id }}</span></div>@endif</div>
<div class="welcome-actions"><button class="btn btn-white" onclick="openMpesaModal()"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Make a Payment</button></div>
</div>
<div class="metrics-row">
<div class="stat-card"><div class="stat-top"><div class="stat-icon si-blue"><svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg></div><span class="stat-delta delta-up">Your records</span></div><span class="stat-value">{{ $stats['my_records'] ?? 0 }}</span><div class="stat-label">My Medical Records</div></div>
<div class="stat-card"><div class="stat-top"><div class="stat-icon si-amber"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div><span class="stat-delta delta-down">{{ $stats['my_referrals'] ?? 0 }} total</span></div><span class="stat-value">{{ $stats['my_referrals'] ?? 0 }}</span><div class="stat-label">My Referrals</div></div>
<div class="stat-card"><div class="stat-top"><div class="stat-icon si-green"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div></div><span class="stat-value">{{ $stats['total_hospitals'] ?? 0 }}</span><div class="stat-label">Available Hospitals</div></div>
<div class="stat-card"><div class="stat-top"><div class="stat-icon si-purple"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><span class="stat-delta delta-up">System-wide</span></div><span class="stat-value">{{ $stats['total_doctors'] ?? 0 }}</span><div class="stat-label">Total Doctors</div></div>
</div>
<div class="grid-main">
<div style="display:flex;flex-direction:column;gap:20px">
<div class="card"><div class="card-header"><span class="card-title"><span class="card-title-dot"></span>Medical Summary</span><a href="{{ route('patient.records') }}" class="btn btn-ghost btn-sm">View All Records</a></div><div class="card-body"><div class="med-summary-box">@if($patientRecords->isEmpty())
<div class="med-row"><span class="med-val" style="color:var(--text-secondary);font-style:italic">No medical records found. Visit a healthcare facility to get started.</span></div>
@else
@foreach($patientRecords as $record)
<div class="med-row"><span class="med-label">Date</span><span class="med-val">{{ $record->visit_date ? $record->visit_date->format('d M Y') : 'N/A' }}</span><a href="{{ route('patient.record.download', $record->id) }}" class="btn-download-sm" title="Download PDF"><svg viewBox="0 0 24 24" width="14" height="14"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></a></div><div class="med-row"><span class="med-label">Facility</span><span class="med-val">{{ $record->facility->name ?? 'N/A' }}</span></div><div class="med-row"><span class="med-label">Doctor</span><span class="med-val">{{ $record->doctor ? $record->doctor->first_name . ' ' . $record->doctor->last_name : 'N/A' }}</span></div><div class="med-row"><span class="med-label">Diagnosis</span><span class="med-val">{{ $record->diagnosis ?? 'N/A' }}</span></div><div class="med-row"><span class="med-label">Treatment</span><span class="med-val">{{ $record->treatment_plan ?? 'N/A' }}</span></div>
@if($loop->iteration < $patientRecords->count())
<div style="border-bottom:1px dashed var(--blue-100);margin:8px 0"></div>
@endif
@endforeach
@endif</div><a href="{{ route('patient.records') }}" class="download-btn"><svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>View & Download Full Medical History</a></div></div>
<div class="card"><div class="card-header"><span class="card-title"><span class="card-title-dot"></span>Services</span></div><div class="card-body" style="padding-top:10px;padding-bottom:10px"><a href="{{ route('patient.nearby-hospitals') }}" class="service-item"><div class="svc-icon" style="background:var(--blue-50);color:var(--accent)"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div><div class="svc-text"><div class="svc-name">Find Nearby Hospitals</div><div class="svc-sub">Locate healthcare facilities near you</div></div><div class="svc-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div></a><div class="service-item"><div class="svc-icon" style="background:var(--amber-light);color:var(--amber-400)"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><div class="svc-text"><div class="svc-name">View Lab Results</div><div class="svc-sub">My Referrals & Test Results</div></div><span class="svc-badge">2 new</span></div><div class="service-item" onclick="openMpesaModal()"><div class="svc-icon" style="background:var(--green-light);color:var(--green-500)"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div><div class="svc-text"><div class="svc-name">M-Pesa Payments</div><div class="svc-sub">Pay hospital bills via STK Push</div></div><div class="svc-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div></div><a href="{{ route('patient.records') }}" class="service-item"><div class="svc-icon" style="background:var(--purple-light);color:var(--purple-400)"><svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg></div><div class="svc-text"><div class="svc-name">Medical Records</div><div class="svc-sub">View your full health history</div></div><div class="svc-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div></a></div></div>
<div class="card"><div class="card-header"><span class="card-title"><span class="card-title-dot"></span>Payment History</span><button class="btn btn-success btn-sm" onclick="openMpesaModal()">+ New Payment</button></div><div class="card-body"><div class="pay-item"><div class="pay-icon pay-ok">M</div><div class="pay-desc"><strong>Consultation Fee — Dr. Kimani</strong><span>0712 *** 456 · 2 hrs ago · QGH3A1B2C</span></div><div class="pay-amount amount-ok">KSh 500</div></div><div class="pay-item"><div class="pay-icon pay-ok">M</div><div class="pay-desc"><strong>Laboratory Tests — Kenyatta Hospital</strong><span>0712 *** 456 · Yesterday · QGH7D8E9F</span></div><div class="pay-amount amount-ok">KSh 1,200</div></div><div class="pay-item"><div class="pay-icon pay-ok">M</div><div class="pay-desc"><strong>Pharmacy — Goodlife Westlands</strong><span>0712 *** 456 · 2 days ago · QGH2K3L4M</span></div><div class="pay-amount amount-ok">KSh 850</div></div></div></div>
</div>
<div style="display:flex;flex-direction:column;gap:18px">
<div class="card"><div class="card-header"><span class="card-title"><span class="card-title-dot"></span>Quick Actions</span></div><div class="card-body" style="display:flex;flex-direction:column;gap:9px"><a href="{{ route('patient.referrals') }}" class="btn btn-primary btn-full"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>My Referrals</a><a href="{{ route('patient.records') }}" class="btn btn-outline btn-full"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>My Medical Records</a><a href="{{ route('patient.nearby-hospitals') }}" class="btn btn-outline btn-full"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Find Nearby Hospital</a><button onclick="openMpesaModal()" class="btn btn-success btn-full"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>M-Pesa Payment</button></div></div>
<div class="card"><div class="card-header"><span class="card-title"><span class="card-title-dot"></span>Nearby Hospitals</span><a href="{{ route('patient.nearby-hospitals') }}" class="btn btn-outline btn-sm">View All</a></div><div class="card-body" style="padding:10px">@if($facilitiesWithDistance->isEmpty())
<div style="text-align:center;padding:20px;color:var(--text-muted)"><svg viewBox="0 0 24 24" width="32" height="32" style="stroke:var(--text-muted);fill:none;stroke-width:1.5;margin-bottom:8px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg><div>No hospitals with location data found</div></div>
@else
@foreach($facilitiesWithDistance as $facility)
<div class="nearby-item"><div class="nearby-pin" style="background:var(--blue-50);color:var(--accent)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div class="nearby-name">{{ $facility['name'] }}</div><div class="nearby-dist">{{ $facility['distance'] }} km</div></div>
@endforeach
<a href="{{ route('patient.nearby-hospitals') }}" class="btn btn-outline btn-full btn-sm" style="margin-top:12px"><svg viewBox="0 0 24 24" width="14" height="14"><circle cx="12" cy="12" r="3"/><path d="M12 2v4m0 12v4M2 12h4m12 0h4"/></svg> Find More Hospitals</a>
@endif</div></div>
</div>
</div>
</main>
</div>
<div class="modal-backdrop" id="mpesaModal"><div class="modal"><div class="modal-header"><div class="modal-hicon" style="background:var(--green-light);border:1px solid #a7f3d0"><svg viewBox="0 0 24 24" style="stroke:var(--green-500);fill:none;stroke-width:2;stroke-linecap:round;width:20px;height:20px"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div><div><div class="modal-title">M-Pesa Payment</div><div class="modal-sub">Daraja STK Push · Safaricom</div></div><button class="modal-close" onclick="closeMpesa()"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div><div id="mp-form"><div class="modal-body"><div class="mpesa-brand"><div class="mpesa-m">M</div><div class="mpesa-info"><strong>Lipa Na M-Pesa</strong><span>Powered by Daraja API · AfyaLink Health</span></div></div><div class="form-group"><label class="form-label">Service / Bill Type</label><select class="form-input form-select" id="mp-service"><option value="500">Consultation Fee — KSh 500</option><option value="1200">Laboratory Tests — KSh 1,200</option><option value="850">Pharmacy — KSh 850</option><option value="2500">Imaging / X-Ray — KSh 2,500</option><option value="5000">Specialist Consultation — KSh 5,000</option></select></div><div class="amount-display"><div class="amount-label">Total Amount</div><div class="amount-big" id="mp-amount-label">KSh 500</div></div><div class="form-group"><label class="form-label">M-Pesa Phone Number</label><div class="input-prefix-wrap"><span class="input-prefix">+254</span><input type="tel" id="mp-phone" placeholder="7XX XXX XXX" maxlength="9"></div></div><div class="daraja-error" id="mp-init-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>Something went wrong.</span></div></div><div class="modal-footer"><button class="btn btn-ghost" onclick="closeMpesa()">Cancel</button><button class="btn btn-primary" onclick="initiateMpesaPayment()"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Send STK Push</button></div></div></div></div>
<script>function $(id){return document.getElementById(id)}function openMpesaModal(){$('mpesaModal').classList.add('open');$('mp-phone').value='';$('mp-amount-label').textContent='KSh 500';$('mp-service').value='500'}function closeMpesa(){$('mpesaModal').classList.remove('open');$('mp-init-error').style.display='none'}$('mpesaModal').addEventListener('click',e=>{if(e.target===$('mpesaModal'))closeMpesa()});$('mp-service').addEventListener('change',e=>{$('mp-amount-label').textContent='KSh '+parseInt(e.target.value).toLocaleString()});async function initiateMpesaPayment(){const phone=$('mp-phone').value.trim();const amount=$('mp-service').value;const serviceType=$('mp-service option:checked').text.split('—')[0].trim();if(!phone||phone.length<9){$('mp-init-error').style.display='flex';$('mp-init-error').querySelector('span').textContent='Please enter a valid phone number';return}$('mp-init-error').style.display='none';const btn=event.target;btn.disabled=true;btn.innerHTML='<svg class="spin" viewBox="0 0 24 24" width="16" height="16"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="30 70"/></svg> Sending...';try{const response=await fetch('{{ route("payment.initiate") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({phone:phone,amount:amount,service_type:serviceType})});const data=await response.json();if(data.success){closeMpesa();alert('Payment request sent to '+phone+'. Please complete the payment on your phone.');}else{alert('Error: '+(data.message||'Failed to initiate payment'));btn.disabled=false;btn.innerHTML='<svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Send STK Push';}}catch(error){alert('Network error. Please try again.');btn.disabled=false;btn.innerHTML='<svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Send STK Push';}}</script>
</body>
</html>
