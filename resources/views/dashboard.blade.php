<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard — AfyaLink</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════
   DESIGN TOKENS — Clinical Light (matches screenshot)
═══════════════════════════════════════════ */
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
  --shadow-lg:  0 8px 32px rgba(37,99,235,0.12), 0 2px 8px rgba(0,0,0,0.06);
  --shadow-card:0 2px 12px rgba(0,0,0,0.06);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text-primary);min-height:100vh;overflow-x:hidden}

/* ─── TOPBAR ─── */
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
.avatar img{width:100%;height:100%;object-fit:cover}
.topbar-logout{
  display:flex;align-items:center;gap:5px;
  font-size:.75rem;font-weight:500;color:var(--text-muted);
  background:none;border:none;cursor:pointer;
  font-family:inherit;transition:color .2s;padding:6px 10px;border-radius:var(--radius-md);
}
.topbar-logout:hover{color:var(--coral-400)}
.topbar-logout svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2}

/* ─── LAYOUT ─── */
.layout{display:flex;padding-top:var(--header-h);min-height:100vh}

/* ─── SIDEBAR ─── */
.sidebar{
  width:var(--sidebar-w);
  background:var(--surface);
  border-right:1px solid var(--border);
  position:fixed;left:0;top:var(--header-h);bottom:0;
  display:flex;flex-direction:column;z-index:200;
  padding:20px 12px;overflow-y:auto;
}
.nav-section-label{
  font-size:.6rem;font-weight:700;letter-spacing:.12em;
  text-transform:uppercase;color:var(--text-muted);
  padding:0 10px;margin:14px 0 6px;
}
.nav-item{
  display:flex;align-items:center;gap:10px;
  padding:9px 12px;border-radius:var(--radius-md);
  text-decoration:none;font-size:.83rem;font-weight:500;
  color:var(--text-secondary);transition:all .18s;
  margin-bottom:2px;border:1px solid transparent;
  cursor:pointer;background:none;width:100%;font-family:inherit;position:relative;
}
.nav-item:hover{background:var(--blue-50);color:var(--accent)}
.nav-item.active{
  background:var(--accent);color:white;
  border-color:var(--accent);
  box-shadow:0 2px 10px rgba(37,99,235,0.3);
}
.nav-item svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0}
.nav-badge{
  margin-left:auto;font-size:.58rem;font-weight:700;
  padding:2px 7px;border-radius:100px;
  background:var(--coral-400);color:white;
}
.nav-item.active .nav-badge{background:rgba(255,255,255,0.3);color:white}
.nav-divider{height:1px;background:var(--border);margin:10px 4px}

/* ─── MAIN ─── */
.main{margin-left:var(--sidebar-w);flex:1;padding:28px 28px 48px;min-height:calc(100vh - var(--header-h))}

/* ─── WELCOME BANNER ─── */
.welcome-banner{
  position:relative;border-radius:var(--radius-2xl);overflow:hidden;
  margin-bottom:24px;padding:28px 32px;
  background:linear-gradient(130deg,#1e40af 0%,#2563eb 45%,#3b82f6 80%,#0ea5e9 100%);
  border:none;box-shadow:0 8px 32px rgba(37,99,235,0.35);
  display:flex;align-items:center;justify-content:space-between;gap:20px;
  animation:fadeSlideDown .6s cubic-bezier(.22,1,.36,1) both;
}
.welcome-banner::before{
  content:'';position:absolute;right:-80px;top:-80px;
  width:320px;height:320px;border-radius:50%;
  background:radial-gradient(circle,rgba(255,255,255,0.1) 0%,transparent 70%);
  pointer-events:none;
}
.welcome-banner::after{
  content:'';position:absolute;
  bottom:0;left:0;right:0;height:1px;
  background:rgba(255,255,255,0.2);
}
.welcome-ecg{position:absolute;bottom:12px;right:32px;opacity:.12;pointer-events:none}
.welcome-greeting{font-size:.72rem;color:rgba(255,255,255,0.75);font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-bottom:6px}
.welcome-name{font-family:'DM Serif Display',serif;font-size:2rem;color:white;line-height:1.1;margin-bottom:7px}
.welcome-name em{font-style:italic;color:rgba(255,255,255,0.85)}
.welcome-sub{font-size:.8rem;color:rgba(255,255,255,0.7)}
.welcome-actions{display:flex;gap:10px;flex-wrap:wrap;flex-shrink:0}

/* ─── BUTTONS ─── */
.btn{
  display:inline-flex;align-items:center;gap:7px;
  padding:10px 20px;border-radius:var(--radius-md);
  font-family:'DM Sans',sans-serif;font-size:.8rem;font-weight:600;
  cursor:pointer;text-decoration:none;border:none;transition:all .18s;white-space:nowrap;
}
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
.btn-danger{background:var(--coral-light);color:var(--coral-400);border:1px solid #fecdd3}
.btn-danger:hover{background:#ffe4e6}
.btn-sm{padding:6px 13px;font-size:.72rem}
.btn-xs{padding:4px 10px;font-size:.68rem}
.btn-full{width:100%;justify-content:center}

/* ─── STATS ROW ─── */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}
.stat-card{
  background:var(--surface);
  border:1px solid var(--border);
  border-radius:var(--radius-xl);
  padding:20px;box-shadow:var(--shadow-card);
  transition:all .22s;position:relative;overflow:hidden;
  animation:fadeSlideUp .5s cubic-bezier(.22,1,.36,1) both;
}
.stat-card:nth-child(1){animation-delay:.05s}.stat-card:nth-child(2){animation-delay:.1s}.stat-card:nth-child(3){animation-delay:.15s}.stat-card:nth-child(4){animation-delay:.2s}
.stat-card:hover{border-color:var(--blue-200);transform:translateY(-3px);box-shadow:var(--shadow-lg)}
.stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center}
.stat-icon svg{width:19px;height:19px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.si-blue{background:var(--accent-light);color:var(--accent)}
.si-amber{background:var(--amber-light);color:var(--amber-400)}
.si-green{background:var(--green-light);color:var(--green-500)}
.si-coral{background:var(--coral-light);color:var(--coral-400)}
.si-purple{background:var(--purple-light);color:var(--purple-400)}
.stat-delta{font-size:.62rem;font-weight:700;padding:3px 9px;border-radius:100px}
.delta-up{background:var(--green-light);color:var(--green-500)}
.delta-down{background:var(--amber-light);color:var(--amber-400)}
.stat-num{font-family:'DM Serif Display',serif;font-size:2.2rem;color:var(--text-primary);line-height:1;display:block;margin-bottom:4px;letter-spacing:-.02em}
.stat-label{font-size:.72rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.05em}

/* ─── CARDS ─── */
.card{
  background:var(--surface);
  border:1px solid var(--border);
  border-radius:var(--radius-xl);
  box-shadow:var(--shadow-card);
  overflow:hidden;
  animation:fadeSlideUp .55s cubic-bezier(.22,1,.36,1) both;
  animation-delay:.25s;
}
.card:hover{box-shadow:var(--shadow-md)}
.card-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:16px 22px 13px;
  border-bottom:1px solid var(--border-soft);
}
.card-title{
  font-weight:700;font-size:.93rem;color:var(--text-primary);
  display:flex;align-items:center;gap:9px;
}
.card-title-dot{width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0}
.card-body{padding:18px 22px}

/* ─── GRID ─── */
.grid-main{display:grid;grid-template-columns:1fr 300px;gap:20px;margin-bottom:20px}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}

/* ─── MED SUMMARY ─── */
.med-summary-box{
  background:var(--blue-50);border:1px solid var(--blue-100);
  border-radius:var(--radius-lg);padding:16px;margin-bottom:16px;
}
.med-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--blue-100);font-size:.83rem}
.med-row:last-child{border-bottom:none}
.med-label{font-weight:700;color:var(--text-secondary);min-width:140px;font-size:.74rem;letter-spacing:.02em}
.med-val{color:var(--text-primary);font-weight:500}
.blood-badge{
  display:inline-flex;align-items:center;
  padding:3px 12px;border-radius:100px;
  background:var(--coral-light);color:var(--coral-400);
  font-size:.72rem;font-weight:700;border:1px solid #fecdd3;
}

/* ─── SERVICES ─── */
.service-item{
  display:flex;align-items:center;gap:13px;
  padding:12px 6px;border-bottom:1px solid var(--border-soft);
  cursor:pointer;transition:all .18s;border-radius:var(--radius-md);
}
.service-item:last-child{border-bottom:none}
.service-item:hover{background:var(--blue-50);padding-left:12px}
.svc-icon{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.svc-icon svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.svc-text{flex:1}
.svc-name{font-size:.83rem;font-weight:600;color:var(--text-primary)}
.svc-sub{font-size:.68rem;color:var(--text-muted);margin-top:2px}
.svc-badge{font-size:.6rem;font-weight:700;padding:2px 8px;border-radius:100px;background:var(--accent);color:white}
.svc-arrow svg{width:14px;height:14px;stroke:var(--text-muted);fill:none;stroke-width:2.5;stroke-linecap:round}

/* ─── BADGES ─── */
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:100px;font-size:.64rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase}
.badge-emerald{background:var(--green-light);color:var(--green-500);border:1px solid #a7f3d0}
.badge-amber{background:var(--amber-light);color:var(--amber-400);border:1px solid #fde68a}
.badge-coral{background:var(--coral-light);color:var(--coral-400);border:1px solid #fecdd3}
.badge-teal{background:var(--blue-50);color:var(--accent);border:1px solid var(--blue-200)}
.badge-muted{background:var(--surface-2);color:var(--text-muted);border:1px solid var(--border)}

/* ─── DOCTOR CARDS ─── */
.doctors-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.doctor-card{
  display:flex;align-items:center;gap:10px;padding:11px;
  border:1px solid var(--border);border-radius:var(--radius-md);
  cursor:pointer;transition:all .18s;background:var(--surface);
}
.doctor-card:hover{border-color:var(--blue-200);background:var(--blue-50);transform:translateY(-1px);box-shadow:var(--shadow-sm)}
.doc-av{width:38px;height:38px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:white;overflow:hidden}
.doc-av img{width:100%;height:100%;object-fit:cover}
.doc-name{font-size:.8rem;font-weight:700;color:var(--text-primary)}
.doc-hosp{font-size:.65rem;color:var(--text-muted);margin-top:1px}

/* ─── GPS / NEARBY ─── */
.gps-bar{
  display:flex;align-items:center;gap:8px;padding:8px 12px;
  background:var(--blue-50);border:1px solid var(--blue-100);
  border-radius:var(--radius-md);margin-bottom:12px;font-size:.74rem;color:var(--accent);
}
.gps-dot{width:7px;height:7px;border-radius:50%;background:var(--green-500);box-shadow:0 0 8px var(--green-400);animation:pulse-dot 2s ease-in-out infinite;flex-shrink:0}
.gps-coords{font-family:monospace;font-size:.63rem;color:var(--text-muted);margin-left:auto}
.nearby-item{
  display:flex;align-items:center;gap:10px;padding:9px 4px;
  border-bottom:1px solid var(--border-soft);cursor:pointer;transition:all .18s;border-radius:8px;
}
.nearby-item:last-child{border-bottom:none}
.nearby-item:hover{background:var(--blue-50);padding-left:8px}
.nearby-pin{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.nearby-pin svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round}
.nearby-name{font-size:.8rem;font-weight:600;color:var(--text-primary)}
.nearby-sub{font-size:.64rem;color:var(--text-muted);margin-top:1px}
.nearby-dist{font-size:.73rem;font-weight:700;color:var(--accent);margin-left:auto;white-space:nowrap}

/* ─── PAYMENTS ─── */
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

/* ─── TABLES ─── */
.data-table{width:100%;border-collapse:collapse}
.data-table th{
  font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
  color:var(--text-muted);padding:9px 16px;text-align:left;
  background:var(--surface-2);border-bottom:1px solid var(--border);
}
.data-table td{padding:11px 16px;font-size:.8rem;border-bottom:1px solid var(--border-soft);vertical-align:middle;color:var(--text-secondary)}
.data-table tr:last-child td{border-bottom:none}
.data-table tr:hover td{background:var(--blue-50)}
.td-name{font-weight:600;color:var(--text-primary);font-size:.83rem}
.td-sub{font-size:.67rem;color:var(--text-muted);margin-top:1px}
.avatar-sm{width:30px;height:30px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:.62rem;font-weight:700;margin-right:9px;flex-shrink:0;vertical-align:middle;color:white}

/* ─── FORMS ─── */
.form-group{margin-bottom:14px}
.form-label{display:block;font-size:.73rem;font-weight:600;color:var(--text-secondary);margin-bottom:6px;letter-spacing:.02em}
.form-input{
  width:100%;padding:10px 14px;
  border:1px solid var(--border);border-radius:var(--radius-md);
  font-family:'DM Sans',sans-serif;font-size:.84rem;
  color:var(--text-primary);background:var(--surface);
  outline:none;transition:all .18s;
}
.form-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(37,99,235,0.1)}
.form-input::placeholder{color:var(--text-muted)}
.form-select{
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 12px center;padding-right:34px;
}
.form-input option{background:white}
.form-textarea{resize:vertical;min-height:76px}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.input-row{display:flex;gap:8px}
.input-row .form-input{flex:1}
.input-prefix-wrap{
  display:flex;align-items:center;
  border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;transition:all .18s;
}
.input-prefix-wrap:focus-within{border-color:var(--accent);box-shadow:0 0 0 3px rgba(37,99,235,0.1)}
.input-prefix{padding:10px 12px;background:var(--surface-2);border-right:1px solid var(--border);font-size:.72rem;font-weight:700;color:var(--accent);white-space:nowrap}
.input-prefix-wrap input{flex:1;padding:10px 12px;border:none;outline:none;font-family:'DM Sans',sans-serif;font-size:.84rem;color:var(--text-primary);background:var(--surface)}

/* ─── AUDIT LOG ─── */
.audit-item{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--border-soft);font-size:.78rem}
.audit-item:last-child{border-bottom:none}
.audit-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.audit-icon svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round}
.audit-msg{flex:1;color:var(--text-secondary);font-weight:500}
.audit-time{font-size:.64rem;color:var(--text-muted)}

/* ─── REFERRAL ROWS ─── */
.referral-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border-soft)}
.referral-row:last-child{border-bottom:none}
.ref-info{flex:1}
.ref-name{font-size:.83rem;font-weight:600;color:var(--text-primary)}
.ref-sub{font-size:.68rem;color:var(--text-muted);margin-top:2px}

/* ─── ALERTS ─── */
.alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius-lg);font-size:.83rem;margin-bottom:20px;border:1px solid}
.alert svg{width:14px;height:14px;flex-shrink:0;stroke:currentColor;fill:none;stroke-width:2}
.alert-success{background:var(--green-light);border-color:#a7f3d0;color:var(--green-500)}
.alert-error{background:var(--coral-light);border-color:#fecdd3;color:var(--coral-400)}

/* ─── EMPTY STATE ─── */
.empty-state{text-align:center;padding:32px 20px}
.empty-state svg{width:32px;height:32px;stroke:var(--text-muted);fill:none;stroke-width:1.5;margin-bottom:9px}
.empty-state p{font-size:.8rem;color:var(--text-muted)}

/* ─── DOWNLOAD BTN ─── */
.download-btn{
  display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;
  border-radius:var(--radius-md);margin-top:14px;
  background:var(--accent);color:white;
  font-family:'DM Sans',sans-serif;font-size:.83rem;font-weight:700;
  border:none;cursor:pointer;transition:all .18s;letter-spacing:.02em;
  box-shadow:0 2px 10px rgba(37,99,235,0.3);
}
.download-btn:hover{background:var(--accent-hover);transform:translateY(-1px);box-shadow:0 4px 16px rgba(37,99,235,0.4)}
.download-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.2;stroke-linecap:round}

/* ─── SECTION LABEL ─── */
.section-label{font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-muted);margin-bottom:10px;display:flex;align-items:center;gap:8px}
.section-label::after{content:'';flex:1;height:1px;background:var(--border)}

/* ─── M-PESA MODAL ─── */
.mpesa-brand{
  background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #a7f3d0;
  border-radius:13px;padding:14px 16px;display:flex;align-items:center;gap:13px;margin-bottom:18px;
}
.mpesa-m{font-family:'DM Serif Display',serif;font-size:2rem;color:var(--green-500);line-height:1}
.mpesa-info strong{color:var(--text-primary);font-size:.88rem;display:block;margin-bottom:2px}
.mpesa-info span{color:var(--text-muted);font-size:.7rem}
.amount-display{background:var(--green-light);border:1px solid #a7f3d0;border-radius:12px;padding:16px;text-align:center;margin-bottom:14px}
.amount-label{font-size:.62rem;font-weight:700;color:var(--green-500);letter-spacing:.1em;text-transform:uppercase}
.amount-big{font-family:'DM Serif Display',serif;font-size:2.2rem;color:var(--text-primary);line-height:1.1;margin-top:3px}
.step-list{margin:12px 0 8px}
.step-item{display:flex;align-items:flex-start;gap:10px;padding:8px 0;border-bottom:1px solid var(--border-soft);font-size:.79rem}
.step-item:last-child{border-bottom:none}
.step-num{width:22px;height:22px;border-radius:50%;background:var(--accent);color:white;font-size:.62rem;font-weight:800;flex-shrink:0;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(37,99,235,0.3)}
.step-item p{color:var(--text-primary);margin:0;font-weight:600}
.step-item span{color:var(--text-muted);font-size:.68rem;display:block;margin-top:1px}
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

/* ─── MODALS ─── */
.modal-backdrop{display:none;position:fixed;inset:0;background:rgba(15,23,42,0.5);backdrop-filter:blur(6px);z-index:600;align-items:center;justify-content:center}
.modal-backdrop.open{display:flex}
.modal{
  background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-2xl);
  width:100%;max-width:480px;margin:20px;
  box-shadow:0 24px 64px rgba(0,0,0,0.18);
  animation:modalIn .3s cubic-bezier(.34,1.3,.64,1);
  overflow:hidden;max-height:calc(100vh - 40px);overflow-y:auto;
}
.modal-map{max-width:760px}
@keyframes modalIn{from{opacity:0;transform:translateY(20px) scale(.96)}to{opacity:1;transform:none}}
.modal-header{
  padding:20px 22px 16px;border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:13px;
  position:sticky;top:0;background:var(--surface);z-index:2;
}
.modal-hicon{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.modal-title{font-family:'DM Serif Display',serif;font-size:1.1rem;color:var(--text-primary)}
.modal-sub{font-size:.72rem;color:var(--text-muted);margin-top:2px}
.modal-close{
  margin-left:auto;width:30px;height:30px;
  border:1px solid var(--border);background:var(--surface-2);
  border-radius:8px;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  color:var(--text-muted);transition:all .18s;flex-shrink:0;
}
.modal-close:hover{background:var(--coral-light);border-color:#fecdd3;color:var(--coral-400)}
.modal-close svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2.5}
.modal-body{padding:18px 22px}
.modal-footer{padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:9px;justify-content:flex-end;position:sticky;bottom:0;background:var(--surface)}

/* ─── MAP ─── */
.map-search-bar{display:flex;gap:7px;margin-bottom:12px}
.map-search-bar input{flex:1;padding:9px 13px;border:1px solid var(--border);border-radius:var(--radius-md);font-family:'DM Sans',sans-serif;font-size:.83rem;outline:none;transition:all .18s;background:var(--surface);color:var(--text-primary)}
.map-search-bar input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(37,99,235,0.1)}
.map-search-bar input::placeholder{color:var(--text-muted)}
.icon-btn{width:38px;height:38px;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;flex-shrink:0;transition:all .18s}
.icon-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2}
.ibb{background:var(--accent);color:white;box-shadow:0 2px 8px rgba(37,99,235,0.3)}
.ibb:hover{background:var(--accent-hover)}
.ibo{background:var(--surface-2);color:var(--accent);border:1px solid var(--blue-200)}
.ibo:hover{background:var(--blue-50)}
.filter-chips{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px}
.fchip{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:100px;background:var(--surface-2);border:1px solid var(--border);font-size:.7rem;font-weight:600;cursor:pointer;transition:all .18s;color:var(--text-secondary)}
.fchip:hover,.fchip.active{border-color:var(--accent);background:var(--blue-50);color:var(--accent)}
.fchip-dot{width:6px;height:6px;border-radius:50%}
.map-frame{width:100%;height:300px;border-radius:12px;overflow:hidden;border:1px solid var(--border)}
.map-frame iframe{width:100%;height:100%;border:0}

/* ─── ANIMATIONS ─── */
@keyframes fadeSlideDown{from{opacity:0;transform:translateY(-16px)}to{opacity:1;transform:none}}
@keyframes fadeSlideUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
.ecg-path{stroke-dasharray:300;stroke-dashoffset:300;animation:drawEcg 2.5s ease forwards .5s}
@keyframes drawEcg{to{stroke-dashoffset:0}}

/* ─── SCROLLBAR ─── */
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--blue-200);border-radius:3px}
::-webkit-scrollbar-thumb:hover{background:var(--blue-400)}

/* ─── MOBILE TOGGLE ─── */
.mobile-toggle{display:none;position:fixed;top:14px;left:14px;z-index:400;width:36px;height:36px;background:white;border:1px solid var(--border);border-radius:9px;cursor:pointer;align-items:center;justify-content:center;box-shadow:var(--shadow-sm)}
.mobile-toggle svg{width:18px;height:18px;stroke:var(--accent);fill:none;stroke-width:2.2}

/* ─── RESPONSIVE ─── */
@media(max-width:1200px){.stats-row{grid-template-columns:repeat(2,1fr)}}
@media(max-width:1000px){.grid-main,.grid-2{grid-template-columns:1fr}}
@media(max-width:768px){
  .sidebar{transform:translateX(-100%);transition:transform .3s}
  .sidebar.open{transform:none}
  .main{margin-left:0;padding:18px 14px}
  .topbar{padding:0 16px 0 54px}
  .welcome-banner{flex-direction:column;gap:16px;align-items:flex-start}
  .welcome-name{font-size:1.6rem}
  .mobile-toggle{display:flex}
}
@media(max-width:560px){.stats-row{grid-template-columns:1fr}}
</style>
</head>
<body>

<button class="mobile-toggle" id="sidebarToggle">
  <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
</button>

<!-- TOPBAR -->
<header class="topbar">
  <a href="#" class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24"><path d="M12 21C12 21 3 15.5 3 9a5 5 0 0110 0 5 5 0 0110 0c0 6.5-9 12-11 12z"/><line x1="12" y1="6" x2="12" y2="14"/><line x1="8" y1="10" x2="16" y2="10"/></svg>
    </div>
    <span class="logo-text">Afya<span>Link</span></span>
  </a>
  <div class="topbar-spacer"></div>
  <div class="topbar-right">
    <div class="topbar-pill">
      <div class="status-dot"></div>
      Patient Dashboard
    </div>
    <div class="topbar-sep"></div>
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
      @csrf
      <button type="submit" class="topbar-logout">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
    <div class="topbar-sep"></div>
    <div class="topbar-user">
      <span class="topbar-user-name">@auth {{ Auth::user()->first_name ?? 'User' }} @else Guest @endauth</span>
      <div class="avatar">@auth {{ strtoupper(substr(Auth::user()->first_name ?? 'G', 0, 1)) }} @else G @endauth</div>
    </div>
  </div>
</header>

<div class="layout">

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="nav-section-label">Menu</div>

  <a href="#" class="nav-item active">
    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
    Dashboard
  </a>
  <a href="{{ route('records.index') }}" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
    Medical Records
  </a>
  <a href="{{ route('patient.referrals') }}" class="nav-item">
    <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    Referrals
    <span class="nav-badge">2</span>
  </a>
  <a href="#" class="nav-item" onclick="openMapModal();return false">
    <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
    Hospitals Nearby
  </a>
  <a href="#" class="nav-item" onclick="openMpesaModal();return false">
    <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
    Payments
  </a>

  <div class="nav-divider"></div>
  <div class="nav-section-label">Account</div>

  <a href="#" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    Profile
  </a>
  <a href="#" class="nav-item">
    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M12 2v2M12 20v2M20 12h2M2 12h2M17.66 17.66l-1.41-1.41M6.34 17.66l1.41-1.41"/></svg>
    Settings
  </a>

  <div style="margin-top:auto;padding-top:16px;border-top:1px solid var(--border)">
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
      @csrf
      <button type="submit" class="nav-item" style="color:#ef4444;border:none;background:none;cursor:pointer;width:100%;text-align:left;">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<main class="main">

  <!-- WELCOME BANNER -->
  <div class="welcome-banner">
    <svg class="welcome-ecg" width="200" height="50" viewBox="0 0 200 50">
      <polyline class="ecg-path" points="0,25 20,25 30,25 40,5 50,45 60,25 80,25 90,20 100,25 120,25 130,25 140,5 150,45 160,25 180,25 200,25" fill="none" stroke="white" stroke-width="1.5"/>
    </svg>
    <div>
      <div class="welcome-greeting">Thursday, 12 March 2026</div>
      <div class="welcome-name">Welcome back, <em>Juliet</em> 👋</div>
      <div class="welcome-sub">Here's your health summary and today's updates</div>
    </div>
    <div class="welcome-actions">
      <button class="btn btn-white">
        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Download Medical History
      </button>
      <button class="btn btn-white-outline" onclick="openMpesaModal()">
        <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        Make a Payment
      </button>
    </div>
  </div>

  <!-- STATS ROW -->
  <div class="stats-row">
    @if($userRole === 'doctor')
    <div class="stat-card">
      <div class="stat-top">
        <div class="stat-icon si-blue"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
        <span class="stat-delta delta-up">At your facility</span>
      </div>
      <span class="stat-num">{{ $stats['patients_treated'] ?? 0 }}</span>
      <div class="stat-label">Patients Treated</div>
    </div>
    <div class="stat-card">
      <div class="stat-top">
        <div class="stat-icon si-amber"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <span class="stat-delta delta-down">Created</span>
      </div>
      <span class="stat-num">{{ $stats['referrals_created'] ?? 0 }}</span>
      <div class="stat-label">Referrals Created</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon si-green"><svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg></div></div>
      <span class="stat-num">{{ $stats['total_records'] ?? 0 }}</span>
      <div class="stat-label">Medical Records Created</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon si-purple"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg><span class="stat-delta delta-up">System-wide</span></div></div>
      <span class="stat-num">{{ $stats['total_hospitals'] ?? 0 }}</span>
      <div class="stat-label">Total Hospitals</div>
    </div>
    @elseif($userRole === 'hospital' || $userRole === 'facility')
    <div class="stat-card">
      <div class="stat-top">
        <div class="stat-icon si-amber"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <span class="stat-delta delta-down">Pending</span>
      </div>
      <span class="stat-num">{{ $stats['incoming_referrals'] ?? 0 }}</span>
      <div class="stat-label">Incoming Referrals</div>
    </div>
    <div class="stat-card">
      <div class="stat-top">
        <div class="stat-icon si-blue"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
        <span class="stat-delta delta-up">Active</span>
      </div>
      <span class="stat-num">{{ $stats['accepted_referrals'] ?? 0 }}</span>
      <div class="stat-label">Accepted Referrals</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon si-green"><svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div></div>
      <span class="stat-num">{{ $stats['completed_referrals'] ?? 0 }}</span>
      <div class="stat-label">Completed Referrals</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon si-purple"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg><span class="stat-delta delta-up">Your facility</span></div></div>
      <span class="stat-num">{{ $stats['total_patients'] ?? 0 }}</span>
      <div class="stat-label">Total Patients</div>
    </div>
    @elseif($userRole === 'admin')
    <div class="stat-card">
      <div class="stat-top">
        <div class="stat-icon si-blue"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
        <span class="stat-delta delta-up">Registered</span>
      </div>
      <span class="stat-num">{{ $stats['total_patients'] ?? 0 }}</span>
      <div class="stat-label">Total Patients</div>
    </div>
    <div class="stat-card">
      <div class="stat-top">
        <div class="stat-icon si-amber"><svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
        <span class="stat-delta delta-up">Active</span>
      </div>
      <span class="stat-num">{{ $stats['total_doctors'] ?? 0 }}</span>
      <div class="stat-label">Total Doctors</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon si-green"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div></div>
      <span class="stat-num">{{ $stats['total_hospitals'] ?? 0 }}</span>
      <div class="stat-label">Total Hospitals</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon si-purple"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg><span class="stat-delta delta-down">{{ $stats['pending_doctors'] ?? 0 }} pending</span></div></div>
      <span class="stat-num">{{ $stats['pending_doctors'] ?? 0 }}</span>
      <div class="stat-label">Pending Doctor Applications</div>
    </div>
    @else
    <div class="stat-card">
      <div class="stat-top">
        <div class="stat-icon si-blue"><svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg></div>
        <span class="stat-delta delta-up">+3 today</span>
      </div>
      <span class="stat-num">24</span>
      <div class="stat-label">Medical Records</div>
    </div>
    <div class="stat-card">
      <div class="stat-top">
        <div class="stat-icon si-amber"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <span class="stat-delta delta-down">2 pending</span>
      </div>
      <span class="stat-num">7</span>
      <div class="stat-label">My Referrals</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon si-green"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div></div>
      <span class="stat-num">12</span>
      <div class="stat-label">Facilities Nearby</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon si-purple"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><span class="stat-delta delta-up">Upcoming</span></div>
      <span class="stat-num" style="font-size:1.4rem;padding-top:4px">24 Mar</span>
      <div class="stat-label">Next Appointment</div>
    </div>
    @endif
  </div>

  <div class="grid-main">
    <!-- LEFT -->
    <div style="display:flex;flex-direction:column;gap:20px">

      <!-- MEDICAL SUMMARY -->
      <div class="card">
        <div class="card-header">
          <span class="card-title"><span class="card-title-dot"></span>Medical Summary</span>
          <a href="#" class="btn btn-ghost btn-sm">View All Records</a>
        </div>
        <div class="card-body">
          <div class="med-summary-box">
            <div class="med-row">
              <span class="med-label">Blood Group</span>
              <span class="blood-badge">O+</span>
            </div>
            <div class="med-row">
              <span class="med-label">Allergies</span>
              <span class="med-val">Penicillin</span>
            </div>
            <div class="med-row">
              <span class="med-label">Last Consultation</span>
              <span class="med-val" style="color:var(--accent);font-weight:600">10 Feb 2026</span>
            </div>
            <div class="med-row">
              <span class="med-label">Conditions</span>
              <span class="med-val">Hypertension (Stage 1)</span>
            </div>
            <div class="med-row">
              <span class="med-label">Current Medication</span>
              <span class="med-val">Amlodipine 5mg daily</span>
            </div>
          </div>
          <button class="download-btn">
            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Full Medical History (PDF)
          </button>
        </div>
      </div>

      <!-- SERVICES -->
      <div class="card">
        <div class="card-header">
          <span class="card-title"><span class="card-title-dot"></span>Services</span>
        </div>
        <div class="card-body" style="padding-top:10px;padding-bottom:10px">
          <div class="service-item" onclick="openMapModal()">
            <div class="svc-icon" style="background:var(--blue-50);color:var(--accent)"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div class="svc-text">
              <div class="svc-name">Find Nearby Hospitals</div>
              <div class="svc-sub">Kenyatta Hospital — <span id="svc-knh-dist">calculating…</span></div>
            </div>
            <div class="svc-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
          </div>
          <div class="service-item">
            <div class="svc-icon" style="background:var(--amber-light);color:var(--amber-400)"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
            <div class="svc-text">
              <div class="svc-name">View Lab Results</div>
              <div class="svc-sub">My Referrals &amp; Test Results</div>
            </div>
            <span class="svc-badge">2 new</span>
            <div class="svc-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
          </div>
          <div class="service-item" onclick="openMpesaModal()">
            <div class="svc-icon" style="background:var(--green-light);color:var(--green-500)"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div>
            <div class="svc-text">
              <div class="svc-name">M-Pesa Payments</div>
              <div class="svc-sub">Pay hospital bills via STK Push</div>
            </div>
            <div class="svc-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
          </div>
          <div class="service-item">
            <div class="svc-icon" style="background:var(--purple-light);color:var(--purple-400)"><svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg></div>
            <div class="svc-text">
              <div class="svc-name">Medical Records</div>
              <div class="svc-sub">View your full health history</div>
            </div>
            <div class="svc-arrow"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></div>
          </div>
        </div>
      </div>

      <!-- PAYMENT HISTORY -->
      <div class="card">
        <div class="card-header">
          <span class="card-title"><span class="card-title-dot"></span>Payment History</span>
          <button class="btn btn-success btn-sm" onclick="openMpesaModal()">+ New Payment</button>
        </div>
        <div class="card-body">
          <div class="pay-item"><div class="pay-icon pay-ok">M</div><div class="pay-desc"><strong>Consultation Fee — Dr. Kimani</strong><span>0712 *** 456 · 2 hrs ago · QGH3A1B2C</span></div><div class="pay-amount amount-ok">KSh 500</div></div>
          <div class="pay-item"><div class="pay-icon pay-ok">M</div><div class="pay-desc"><strong>Laboratory Tests — Kenyatta Hospital</strong><span>0712 *** 456 · Yesterday · QGH7D8E9F</span></div><div class="pay-amount amount-ok">KSh 1,200</div></div>
          <div class="pay-item"><div class="pay-icon pay-ok">M</div><div class="pay-desc"><strong>Pharmacy — Goodlife Westlands</strong><span>0712 *** 456 · 2 days ago · QGH2K3L4M</span></div><div class="pay-amount amount-ok">KSh 850</div></div>
          <div class="pay-item"><div class="pay-icon pay-fail">!</div><div class="pay-desc"><strong>Specialist Consultation Fee</strong><span>0712 *** 456 · 3 days ago · Timeout</span></div><div class="pay-amount amount-fail">Failed</div></div>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div style="display:flex;flex-direction:column;gap:18px">

      <!-- RECENT DOCTORS -->
      <div class="card">
        <div class="card-header">
          <span class="card-title"><span class="card-title-dot"></span>Recent Doctors</span>
        </div>
        <div class="card-body">
          <div class="doctors-grid">
            <div class="doctor-card">
              <div class="doc-av" style="background:linear-gradient(135deg,var(--blue-400),#0ea5e9)">DK</div>
              <div><div class="doc-name">Dr. Kimani</div><div class="doc-hosp">Kenyatta Hospital</div></div>
            </div>
            <div class="doctor-card">
              <div class="doc-av" style="background:linear-gradient(135deg,var(--purple-400),#7c3aed)">DO</div>
              <div><div class="doc-name">Dr. Otieno</div><div class="doc-hosp">Nairobi Hospital</div></div>
            </div>
            <div class="doctor-card">
              <div class="doc-av" style="background:linear-gradient(135deg,var(--green-400),#059669)">DW</div>
              <div><div class="doc-name">Dr. Wanjiku</div><div class="doc-hosp">Aga Khan Hospital</div></div>
            </div>
            <div class="doctor-card">
              <div class="doc-av" style="background:linear-gradient(135deg,var(--coral-400),#fb923c)">DS</div>
              <div><div class="doc-name">Dr. Sihan</div><div class="doc-hosp">Nairobi Hospital</div></div>
            </div>
          </div>
        </div>
      </div>

      <!-- NEARBY HOSPITALS -->
      <div class="card">
        <div class="card-header">
          <span class="card-title"><span class="card-title-dot"></span>Nearby Hospitals</span>
          <button class="btn btn-outline btn-sm" onclick="openMapModal()">Open Map</button>
        </div>
        <div class="card-body">
          <div class="gps-bar">
            <div class="gps-dot"></div>
            <span><strong>GPS Active</strong> — Locating…</span>
            <span class="gps-coords" id="gpsCoords">—</span>
          </div>
          <div class="nearby-item" onclick="openMapModal()"><div class="nearby-pin" style="background:var(--blue-50);color:var(--accent)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">Kenyatta National Hospital</div><div class="nearby-sub">Upper Hill, Nairobi</div></div><div class="nearby-dist" id="d-knh">— km</div></div>
          <div class="nearby-item" onclick="openMapModal()"><div class="nearby-pin" style="background:#eff9ff;color:#38bdf8"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">Nairobi Hospital</div><div class="nearby-sub">Upper Hill, Nairobi</div></div><div class="nearby-dist" id="d-nairobi">— km</div></div>
          <div class="nearby-item" onclick="openMapModal()"><div class="nearby-pin" style="background:var(--green-light);color:var(--green-500)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">Aga Khan University Hospital</div><div class="nearby-sub">Parklands, Nairobi</div></div><div class="nearby-dist" id="d-agakhan">— km</div></div>
          <div class="nearby-item" onclick="openMapModal()"><div class="nearby-pin" style="background:var(--amber-light);color:var(--amber-400)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">MP Shah Hospital</div><div class="nearby-sub">Parklands, Nairobi</div></div><div class="nearby-dist" id="d-mpshah">— km</div></div>
        </div>
      </div>

      <!-- QUICK ACTIONS -->
      <div class="card">
        <div class="card-header">
          <span class="card-title"><span class="card-title-dot"></span>Quick Actions</span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:9px">
          @auth
            @if(Auth::user()->role === 'doctor')
          <a href="{{ route('records.create') }}" class="btn btn-primary btn-full">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            Create Medical Record
          </a>
          <a href="{{ route('referrals.create') }}" class="btn btn-outline btn-full">
            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Create Referral
          </a>
          <a href="{{ route('patients.index') }}" class="btn btn-outline btn-full">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            View All Patients
          </a>
            @elseif(Auth::user()->role === 'hospital' || Auth::user()->role === 'facility')
          <a href="{{ route('referrals.index') }}" class="btn btn-primary btn-full">
            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            View Incoming Referrals
          </a>
          <a href="{{ route('patients.index') }}" class="btn btn-outline btn-full">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Manage Patients
          </a>
          <a href="{{ route('facility.nearby') }}" class="btn btn-outline btn-full">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            View Nearby Facilities
          </a>
            @elseif(Auth::user()->role === 'admin')
          <a href="{{ route('admin.doctors.pending') }}" class="btn btn-primary btn-full">
            <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            Verify Doctors
          </a>
          <a href="{{ route('admin.facilities.pending') }}" class="btn btn-outline btn-full">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
            Verify Facilities
          </a>
            @else
          <button class="btn btn-primary btn-full">
            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Medical History
          </button>
          <button class="btn btn-outline btn-full" onclick="openMapModal()">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Find Nearby Hospital
          </button>
          <button onclick="openMpesaModal()" class="btn btn-success btn-full">
            <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            M-Pesa Payment
          </button>
          @endif
          @endauth
        </div>
      </div>

    </div>
  </div>

</main>
</div>

<!-- M-PESA MODAL -->
<div class="modal-backdrop" id="mpesaModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-hicon" style="background:var(--green-light);border:1px solid #a7f3d0">
        <svg viewBox="0 0 24 24" style="stroke:var(--green-500);fill:none;stroke-width:2;stroke-linecap:round;width:20px;height:20px"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      </div>
      <div><div class="modal-title">M-Pesa Payment</div><div class="modal-sub">Daraja STK Push · Safaricom</div></div>
      <button class="modal-close" onclick="closeMpesa()"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div id="mp-form">
      <div class="modal-body">
        <div class="mpesa-brand"><div class="mpesa-m">M</div><div class="mpesa-info"><strong>Lipa Na M-Pesa</strong><span>Powered by Daraja API · AfyaLink Health</span></div></div>
        <div class="form-group">
          <label class="form-label">Service / Bill Type</label>
          <select class="form-input form-select" id="mp-service" onchange="mpUpdateAmount()">
            <option value="500">Consultation Fee — KSh 500</option>
            <option value="1200">Laboratory Tests — KSh 1,200</option>
            <option value="850">Pharmacy — KSh 850</option>
            <option value="2500">Imaging / X-Ray — KSh 2,500</option>
            <option value="5000">Specialist Consultation — KSh 5,000</option>
            <option value="custom">Custom Amount…</option>
          </select>
        </div>
        <div class="form-group" id="mp-custom-wrap" style="display:none">
          <label class="form-label">Custom Amount (KSh)</label>
          <div class="input-prefix-wrap"><span class="input-prefix">KSh</span><input type="number" id="mp-custom" placeholder="0" min="1" oninput="mpUpdateAmount()"></div>
        </div>
        <div class="amount-display"><div class="amount-label">Total Amount</div><div class="amount-big" id="mp-amount-label">KSh 500</div></div>
        <div class="form-group">
          <label class="form-label">Patient M-Pesa Phone Number</label>
          <div class="input-prefix-wrap" id="mp-phone-wrap"><span class="input-prefix">+254</span><input type="tel" id="mp-phone" placeholder="7XX XXX XXX" maxlength="9" pattern="[0-9]{9}"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Patient Name <span style="color:var(--text-muted);font-weight:400">(optional)</span></label>
          <input class="form-input" type="text" id="mp-patient" placeholder="e.g. John Doe">
        </div>
        <div class="daraja-error" id="mp-init-error"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span id="mp-init-error-text">Something went wrong.</span></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-ghost" onclick="closeMpesa()">Cancel</button>
        <button class="btn btn-primary" id="mp-send-btn" onclick="mpSend()">
          <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
          Send STK Push
        </button>
      </div>
    </div>
    <div id="mp-waiting" style="display:none">
      <div class="modal-body">
        <div style="text-align:center;padding:6px 0 16px">
          <div style="width:64px;height:64px;border-radius:50%;background:var(--blue-50);margin:0 auto 13px;display:flex;align-items:center;justify-content:center;border:1px solid var(--blue-200);box-shadow:var(--shadow-md)">
            <svg viewBox="0 0 24 24" style="width:28px;height:28px;stroke:var(--accent);fill:none;stroke-width:2;stroke-linecap:round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
          </div>
          <div style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:var(--text-primary);margin-bottom:5px">Check Your Phone</div>
          <div style="font-size:.78rem;color:var(--text-muted)">STK Push sent to <strong id="mp-target" style="color:var(--accent)">+254 7XX XXX XXX</strong></div>
        </div>
        <div class="stk-bar"><div class="stk-fill" id="mp-progress"></div></div>
        <div class="step-list">
          <div class="step-item"><div class="step-num">1</div><div><p>Open the M-Pesa prompt on your phone</p><span>Push notification sent via Daraja</span></div></div>
          <div class="step-item"><div class="step-num">2</div><div><p>Enter your 4-digit M-Pesa PIN</p><span>Authorise the transaction securely</span></div></div>
          <div class="step-item"><div class="step-num">3</div><div><p>Confirm <strong id="mp-confirm-amount" style="color:var(--accent)">KSh 500</strong></p><span>SMS receipt will be sent to your phone</span></div></div>
        </div>
        <div style="text-align:center;margin-top:12px"><span class="status-pill status-waiting" id="mp-status-pill"><span class="status-dot-pill"></span>Polling Daraja for confirmation…</span></div>
        <div class="expire-hint"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>Prompt expires in <strong id="mp-countdown" style="color:var(--amber-400)">60s</strong> — do not close this window.</div>
      </div>
      <div class="modal-footer"><button class="btn btn-ghost" onclick="closeMpesa()">Cancel</button></div>
    </div>
    <div id="mp-success" style="display:none">
      <div class="modal-body">
        <div style="text-align:center;padding:10px 0 16px">
          <div style="width:64px;height:64px;border-radius:50%;background:var(--green-light);margin:0 auto 13px;display:flex;align-items:center;justify-content:center;border:1px solid #a7f3d0;box-shadow:0 4px 16px rgba(16,185,129,0.15)">
            <svg viewBox="0 0 24 24" style="width:26px;height:26px;stroke:var(--green-500);fill:none;stroke-width:2.5;stroke-linecap:round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div style="font-family:'DM Serif Display',serif;font-size:1.2rem;color:var(--text-primary);margin-bottom:4px">Payment Confirmed!</div>
          <div style="font-size:.78rem;color:var(--text-muted)">Transaction processed via Daraja · Safaricom</div>
        </div>
        <div class="txn-receipt">
          <div class="txn-row"><span>M-Pesa Receipt No.</span><strong id="mp-receipt">—</strong></div>
          <div class="txn-row"><span>Amount</span><strong id="mp-suc-amount">KSh 500</strong></div>
          <div class="txn-row"><span>Phone</span><strong id="mp-suc-phone">—</strong></div>
          <div class="txn-row"><span>Date &amp; Time</span><strong id="mp-suc-time">—</strong></div>
          <div class="txn-row"><span>Checkout ID</span><strong id="mp-suc-cid" style="font-size:.66rem">—</strong></div>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary" onclick="closeMpesa()">Done</button></div>
    </div>
    <div id="mp-failed" style="display:none">
      <div class="modal-body">
        <div style="text-align:center;padding:10px 0 16px">
          <div style="width:64px;height:64px;border-radius:50%;background:var(--coral-light);margin:0 auto 13px;display:flex;align-items:center;justify-content:center;border:1px solid #fecdd3;box-shadow:0 4px 16px rgba(244,63,94,0.1)">
            <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:var(--coral-400);fill:none;stroke-width:2.5;stroke-linecap:round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </div>
          <div style="font-family:'DM Serif Display',serif;font-size:1.1rem;color:var(--text-primary);margin-bottom:4px">Payment Failed</div>
          <div style="font-size:.78rem;color:var(--text-muted)" id="mp-fail-msg">The transaction was not completed.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-ghost" onclick="closeMpesa()">Close</button>
        <button class="btn btn-primary" onclick="mpShowStep('form')">Try Again</button>
      </div>
    </div>
  </div>
</div>

<!-- MAP MODAL -->
<div class="modal-backdrop" id="mapModal">
  <div class="modal modal-map">
    <div class="modal-header">
      <div class="modal-hicon" style="background:var(--blue-50);border:1px solid var(--blue-200)">
        <svg viewBox="0 0 24 24" style="stroke:var(--accent);fill:none;stroke-width:2;stroke-linecap:round;width:20px;height:20px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      <div><div class="modal-title">Locate Hospitals &amp; Clinics</div><div class="modal-sub" id="map-subtitle">GPS-powered facility finder</div></div>
      <button class="modal-close" onclick="closeMap()"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <div class="modal-body">
      <div class="map-search-bar">
        <input type="text" id="mapQ" placeholder="Search hospital, clinic or area…" oninput="mapFilter()" onkeydown="if(event.key==='Enter')mapSearch()">
        <button class="icon-btn ibb" onclick="mapSearch()"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></button>
        <button class="icon-btn ibo" onclick="mapLocateMe()" title="Use my GPS"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg></button>
      </div>
      <div class="filter-chips">
        <div class="fchip active" onclick="mapChip('all',this)"><span class="fchip-dot" style="background:var(--accent)"></span>All</div>
        <div class="fchip" onclick="mapChip('hospital',this)"><span class="fchip-dot" style="background:var(--accent)"></span>Hospitals</div>
        <div class="fchip" onclick="mapChip('clinic',this)"><span class="fchip-dot" style="background:var(--green-500)"></span>Clinics</div>
        <div class="fchip" onclick="mapChip('pharmacy',this)"><span class="fchip-dot" style="background:var(--amber-400)"></span>Pharmacies</div>
      </div>
      <div class="map-frame"><iframe id="googleMapFrame" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d63820!2d36.8219!3d-1.2921!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1shospitals+nairobi!5e0!3m2!1sen!2ske!4v1699000000000!5m2!1sen!2ske"></iframe></div>
      <div style="margin-top:14px">
        <div class="section-label">Nearby Results</div>
        <div style="max-height:180px;overflow-y:auto" id="mapFacList">
          <div class="nearby-item" data-type="hospital" onclick="mapZoom(-1.3010,36.8073)"><div class="nearby-pin" style="background:var(--blue-50);color:var(--accent)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">Kenyatta National Hospital</div><div class="nearby-sub">Hospital · Upper Hill</div></div><div class="nearby-dist" data-lat="-1.3010" data-lng="36.8073">— km</div></div>
          <div class="nearby-item" data-type="hospital" onclick="mapZoom(-1.2938,36.8106)"><div class="nearby-pin" style="background:#eff9ff;color:#38bdf8"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">Nairobi Hospital</div><div class="nearby-sub">Hospital · Upper Hill</div></div><div class="nearby-dist" data-lat="-1.2938" data-lng="36.8106">— km</div></div>
          <div class="nearby-item" data-type="hospital" onclick="mapZoom(-1.2644,36.8162)"><div class="nearby-pin" style="background:var(--green-light);color:var(--green-500)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">Aga Khan University Hospital</div><div class="nearby-sub">Hospital · Parklands</div></div><div class="nearby-dist" data-lat="-1.2644" data-lng="36.8162">— km</div></div>
          <div class="nearby-item" data-type="hospital" onclick="mapZoom(-1.2591,36.8126)"><div class="nearby-pin" style="background:var(--amber-light);color:var(--amber-400)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">MP Shah Hospital</div><div class="nearby-sub">Hospital · Parklands</div></div><div class="nearby-dist" data-lat="-1.2591" data-lng="36.8126">— km</div></div>
          <div class="nearby-item" data-type="clinic" onclick="mapZoom(-1.2866,36.8233)"><div class="nearby-pin" style="background:var(--green-light);color:var(--green-500)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">City Clinic — CBD</div><div class="nearby-sub">Clinic · CBD, Nairobi</div></div><div class="nearby-dist" data-lat="-1.2866" data-lng="36.8233">— km</div></div>
          <div class="nearby-item" data-type="pharmacy" onclick="mapZoom(-1.2617,36.8075)"><div class="nearby-pin" style="background:var(--amber-light);color:var(--amber-400)"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></div><div><div class="nearby-name">Goodlife Pharmacy</div><div class="nearby-sub">Pharmacy · Westlands</div></div><div class="nearby-dist" data-lat="-1.2617" data-lng="36.8075">— km</div></div>
        </div>
      </div>
    </div>
    <div class="modal-footer" style="justify-content:space-between">
      <div style="font-size:.7rem;color:var(--text-muted)" id="mapGpsStatus">📍 Detecting location…</div>
      <div style="display:flex;gap:8px">
        <button class="btn btn-ghost" onclick="closeMap()">Close</button>
        <button class="btn btn-primary" onclick="mapOpenGoogle()">
          <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          Open in Google Maps
        </button>
      </div>
    </div>
  </div>
</div>

<script>
const $ = id => document.getElementById(id);

document.getElementById('sidebarToggle')?.addEventListener('click', () => {
  document.getElementById('sidebar').classList.toggle('open');
});

/* GPS */
let userLat = -1.2921, userLng = 36.8219, gpsReady = false;

function haversine(la1,ln1,la2,ln2){
  const R=6371,dl=(la2-la1)*Math.PI/180,dln=(ln2-ln1)*Math.PI/180;
  const a=Math.sin(dl/2)**2+Math.cos(la1*Math.PI/180)*Math.cos(la2*Math.PI/180)*Math.sin(dln/2)**2;
  return(R*2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a))).toFixed(1);
}

function refreshDistances(){
  [['d-knh',-1.3010,36.8073],['d-nairobi',-1.2938,36.8106],['d-agakhan',-1.2644,36.8162],['d-mpshah',-1.2591,36.8126]]
    .forEach(([id,la,ln])=>{const e=$(id);if(e)e.textContent=haversine(userLat,userLng,la,ln)+' km';});
  const svcKnh=$('svc-knh-dist');
  if(svcKnh)svcKnh.textContent=haversine(userLat,userLng,-1.3010,36.8073)+' km';
  document.querySelectorAll('#mapFacList .nearby-dist').forEach(el=>{
    const la=parseFloat(el.dataset.lat),ln=parseFloat(el.dataset.lng);
    if(!isNaN(la))el.textContent=haversine(userLat,userLng,la,ln)+' km';
  });
}

function initGPS(){
  if(!navigator.geolocation){refreshDistances();return;}
  navigator.geolocation.getCurrentPosition(pos=>{
    userLat=pos.coords.latitude;userLng=pos.coords.longitude;gpsReady=true;
    const c=userLat.toFixed(4)+', '+userLng.toFixed(4);
    const gc=$('gpsCoords');if(gc)gc.textContent=c;
    const gs=$('mapGpsStatus');if(gs)gs.textContent='📍 '+c;
    const ms=$('map-subtitle');if(ms)ms.textContent='Showing facilities near your location';
    refreshDistances();
  },()=>refreshDistances(),{timeout:8000,enableHighAccuracy:true});
}
initGPS();

/* MAP MODAL */
function openMapModal(){$('mapModal').classList.add('open');refreshDistances();}
function closeMap(){$('mapModal').classList.remove('open');}
$('mapModal').addEventListener('click',e=>{if(e.target===$('mapModal'))closeMap();});
function mapLocateMe(){
  if(gpsReady){mapZoom(userLat,userLng);return;}
  navigator.geolocation&&navigator.geolocation.getCurrentPosition(pos=>{
    userLat=pos.coords.latitude;userLng=pos.coords.longitude;gpsReady=true;
    mapZoom(userLat,userLng);refreshDistances();
  });
}
function mapZoom(lat,lng){
  $('googleMapFrame').src=`https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2000!2d${lng}!3d${lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2ske!4v1699000000000!5m2!1sen!2ske`;
}
function mapChip(type,el){
  document.querySelectorAll('.fchip').forEach(c=>c.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('#mapFacList .nearby-item').forEach(i=>{
    i.style.display=(type==='all'||i.dataset.type===type)?'flex':'none';
  });
}
function mapFilter(){
  const q=$('mapQ').value.toLowerCase();
  document.querySelectorAll('#mapFacList .nearby-item').forEach(i=>{
    i.style.display=i.querySelector('.nearby-name').textContent.toLowerCase().includes(q)?'flex':'none';
  });
}
function mapSearch(){
  const q=encodeURIComponent($('mapQ').value||'hospitals nairobi');
  $('googleMapFrame').src=`https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d63820!2d${userLng}!3d${userLat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1s${q}!5e0!3m2!1sen!2ske!4v1699000000000!5m2!1sen!2ske`;
}
function mapOpenGoogle(){
  const q=encodeURIComponent($('mapQ').value||'hospitals near me');
  window.open(`https://www.google.com/maps/search/${q}/@${userLat},${userLng},14z`,'_blank');
}

/* M-PESA */
let mpAmount=500,mpCountdownTimer=null,mpProgressTimer=null,mpPollTimer=null,mpCheckoutId=null;

function openMpesaModal(){$('mpesaModal').classList.add('open');mpShowStep('form');}
function closeMpesa(){$('mpesaModal').classList.remove('open');mpClearTimers();setTimeout(()=>mpShowStep('form'),400);}
$('mpesaModal').addEventListener('click',e=>{if(e.target===$('mpesaModal'))closeMpesa();});

function mpShowStep(step){
  ['form','waiting','success','failed'].forEach(s=>{
    const el=$(s==='form'?'mp-form':'mp-'+s);
    if(el)el.style.display=s===step?'':'none';
  });
}
function mpClearTimers(){clearInterval(mpCountdownTimer);clearInterval(mpProgressTimer);clearInterval(mpPollTimer);mpCheckoutId=null;}

function mpUpdateAmount(){
  const sel=$('mp-service'),cw=$('mp-custom-wrap');
  if(sel.value==='custom'){cw.style.display='';mpAmount=parseInt($('mp-custom').value)||0;}
  else{cw.style.display='none';mpAmount=parseInt(sel.value);}
  $('mp-amount-label').textContent='KSh '+mpAmount.toLocaleString();
}

function mpStartProgress(){
  let prog=0;$('mp-progress').style.width='0%';
  mpProgressTimer=setInterval(()=>{prog+=1.2;$('mp-progress').style.width=Math.min(prog,90)+'%';if(prog>=90)clearInterval(mpProgressTimer);},900);
}

function mpStartCountdown(onExpire){
  let secs=60;$('mp-countdown').textContent=secs+'s';
  mpCountdownTimer=setInterval(()=>{secs--;$('mp-countdown').textContent=secs+'s';if(secs<=0){clearInterval(mpCountdownTimer);onExpire();}},1000);
}

function mpSend(){
  const ph=$('mp-phone').value.replace(/\s/g,'');
  const wrap=$('mp-phone-wrap');
  if(!ph||ph.length<9){
    wrap.style.borderColor='rgba(244,63,94,0.6)';
    wrap.style.boxShadow='0 0 0 3px rgba(244,63,94,0.1)';
    $('mp-phone').focus();return;
  }
  wrap.style.borderColor='';wrap.style.boxShadow='';
  $('mp-init-error').classList.remove('show');
  const btn=$('mp-send-btn');btn.disabled=true;btn.textContent='Sending…';

  fetch('/api/mpesa/stkpush',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({phone:'254'+ph,amount:mpAmount,description:$('mp-service').options[$('mp-service').selectedIndex].text,patient:$('mp-patient').value||'Patient'})
  })
  .then(r=>r.json())
  .then(data=>{
    btn.disabled=false;
    btn.innerHTML='<svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.2;stroke-linecap:round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg> Send STK Push';
    if(data.ResponseCode==='0'){
      mpCheckoutId=data.CheckoutRequestID;
      $('mp-target').textContent='+254 '+ph.slice(0,3)+' *** '+ph.slice(6);
      $('mp-confirm-amount').textContent='KSh '+mpAmount.toLocaleString();
      mpShowStep('waiting');mpStartProgress();
      mpStartCountdown(()=>{clearInterval(mpPollTimer);const p=$('mp-status-pill');p.className='status-pill status-failed';p.innerHTML='<span class="status-dot-pill"></span> Prompt expired';});
      mpPollTimer=setInterval(()=>{
        fetch('/api/mpesa/status',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({checkout_request_id:mpCheckoutId})})
        .then(r=>r.json()).then(qd=>{
          if(qd.ResultCode==='0'){
            mpClearTimers();$('mp-progress').style.width='100%';
            const items=(qd.CallbackMetadata?.Item)||[];
            const get=n=>(items.find(i=>i.Name===n)||{}).Value||'—';
            setTimeout(()=>{
              $('mp-receipt').textContent=get('MpesaReceiptNumber');
              $('mp-suc-amount').textContent='KSh '+mpAmount.toLocaleString();
              $('mp-suc-phone').textContent='+254 '+ph.slice(0,3)+' *** '+ph.slice(6);
              $('mp-suc-time').textContent=new Date().toLocaleString('en-KE',{dateStyle:'medium',timeStyle:'short'});
              $('mp-suc-cid').textContent=mpCheckoutId;
              mpShowStep('success');
            },400);
          }else if(qd.ResultCode&&qd.ResultCode!=='0'&&qd.ResultCode!==null){
            mpClearTimers();$('mp-fail-msg').textContent=qd.ResultDesc||'Payment was not completed.';mpShowStep('failed');
          }
        }).catch(()=>{});
      },4000);
    }else{
      $('mp-init-error-text').textContent=data.errorMessage||data.ResponseDescription||'Daraja rejected the request.';
      $('mp-init-error').classList.add('show');
    }
  })
  .catch(()=>{
    btn.disabled=false;btn.textContent='Send STK Push';
    $('mp-init-error-text').textContent='Network error — could not reach server.';
    $('mp-init-error').classList.add('show');
  });
}

mpUpdateAmount();

/* STAT COUNTER */
document.querySelectorAll('.stat-num').forEach(el=>{
  const target=parseInt(el.textContent.replace(/,/g,''));
  if(isNaN(target)||target===0)return;
  let current=0;const inc=target/40;
  const timer=setInterval(()=>{current=Math.min(current+inc,target);el.textContent=Math.floor(current).toLocaleString();if(current>=target)clearInterval(timer);},30);
});
</script>
</body>
</html>
