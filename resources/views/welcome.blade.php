<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>AfyaLink — Digital Patient Referral & Health Record Platform</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;background:white;color:#0f172a}
.accent{background:linear-gradient(135deg,#2563eb,#3b82f6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.badge-dot{width:7px;height:7px;border-radius:50%;background:#2563eb;display:inline-block;margin-right:6px;animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
.feat-card{transition:all .2s}
.feat-card:hover{border-color:#bfdbfe !important;box-shadow:0 12px 40px rgba(37,99,235,.08);transform:translateY(-3px)}
.step-card::before{content:attr(data-num);position:absolute;top:-10px;right:16px;font-size:80px;font-weight:900;color:#f0f6ff;line-height:1;pointer-events:none;z-index:0}
.modal-bg{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,.6);backdrop-filter:blur(4px);z-index:200;align-items:center;justify-content:center}
.modal-bg.show{display:flex}
.tab{flex:1;padding:9px;text-align:center;font-size:13px;font-weight:600;cursor:pointer;border-radius:8px;color:#64748b;transition:all .15s}
.tab.on{background:white;color:#2563eb;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.mform{display:none}.mform.on{display:block}
.fi{width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:9px;padding:11px 14px;font-size:13px;font-family:'Inter',sans-serif;outline:none;margin-bottom:14px;transition:border-color .15s}
.fi:focus{border-color:#2563eb;background:white}
.flink{font-size:13px;color:rgba(255,255,255,.3);margin-bottom:10px;cursor:pointer;display:block;transition:color .15s;text-decoration:none}
.flink:hover{color:rgba(255,255,255,.7)}
.pro-btn{display:flex;align-items:center;gap:10px;padding:16px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;width:100%;border:1.5px solid;margin-bottom:10px;text-align:left;text-decoration:none}
.pro-btn:last-child{margin-bottom:0}
</style>
</head>
<body>

<!-- NAV -->
<nav style="background:rgba(255,255,255,.92);backdrop-filter:blur(12px);padding:0 56px;display:flex;align-items:center;justify-content:space-between;height:64px;border-bottom:1px solid rgba(0,0,0,.06);position:sticky;top:0;z-index:100;">
  <div style="display:flex;align-items:center;gap:10px;">
    <div style="width:34px;height:34px;background:linear-gradient(135deg,#1e3a5f,#2563eb);border-radius:9px;display:flex;align-items:center;justify-content:center;">
      <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    </div>
    <span style="font-size:19px;font-weight:800;color:#0f172a;letter-spacing:-.5px;">Afya<span style="color:#2563eb;">Link</span></span>
  </div>
  <div style="display:flex;gap:32px;align-items:center;">
    <a href="#how" style="font-size:13px;color:#64748b;font-weight:500;text-decoration:none;">How It Works</a>
    <a href="#features" style="font-size:13px;color:#64748b;font-weight:500;text-decoration:none;">Features</a>
    <a href="#about" style="font-size:13px;color:#64748b;font-weight:500;text-decoration:none;">About</a>
    <button onclick="openModal()" style="background:#2563eb;color:white;border:none;padding:9px 22px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">Get Started</button>
  </div>
</nav>

<!-- HERO -->
<div style="background:white;padding:100px 56px 80px;display:grid;grid-template-columns:1.1fr 1fr;gap:64px;align-items:center;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-200px;right:-200px;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(37,99,235,.05) 0%,transparent 65%);pointer-events:none;"></div>
  <div>
    <div style="display:inline-flex;align-items:center;background:#f0f6ff;border:1px solid #bfdbfe;color:#1d4ed8;padding:7px 14px;border-radius:20px;font-size:12px;font-weight:600;margin-bottom:28px;"><span class="badge-dot"></span>Kenya's Digital Health Platform</div>
    <h1 style="font-size:54px;font-weight:900;line-height:1.08;letter-spacing:-1.5px;margin-bottom:22px;color:#0f172a;">Better Healthcare<br>Starts With<br><span class="accent">Better Access</span></h1>
    <p style="font-size:16px;color:#64748b;line-height:1.75;margin-bottom:36px;max-width:480px;">AfyaLink connects you with doctors, hospitals, and your complete medical history — anywhere in Kenya, anytime.</p>
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:48px;">
      <button onclick="openModal()" style="background:#2563eb;color:white;border:none;padding:14px 32px;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;box-shadow:0 4px 24px rgba(37,99,235,.28);display:flex;align-items:center;gap:8px;">Get Started — Free <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
      <button onclick="openModal()" style="background:none;border:none;color:#64748b;font-size:14px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;display:flex;align-items:center;gap:6px;">Already have an account? <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>
    </div>
    <div style="display:flex;align-items:center;gap:24px;padding-top:20px;border-top:1px solid #f1f5f9;">
      <div style="display:flex;align-items:center;gap:7px;font-size:12px;color:#64748b;font-weight:500;"><div style="width:20px;height:20px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:9px;color:#2563eb;font-weight:700;">✓</div>Free to register</div>
      <div style="display:flex;align-items:center;gap:7px;font-size:12px;color:#64748b;font-weight:500;"><div style="width:20px;height:20px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:9px;color:#2563eb;font-weight:700;">✓</div>Secure and private</div>
      <div style="display:flex;align-items:center;gap:7px;font-size:12px;color:#64748b;font-weight:500;"><div style="width:20px;height:20px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:9px;color:#2563eb;font-weight:700;">✓</div>Works across Kenya</div>
    </div>
  </div>
</div>

<!-- TRUST BAR -->
<div style="padding:24px 56px;background:#f8fafc;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:32px;">
  <div style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;">Integrated with</div>
  <div style="display:flex;gap:40px;align-items:center;">
    <div style="font-size:12px;font-weight:700;color:#94a3b8;">Safaricom M-PESA</div>
    <div style="font-size:12px;font-weight:700;color:#94a3b8;">Google Maps API</div>
    <div style="font-size:12px;font-weight:700;color:#94a3b8;">Kenya MFL</div>
    <div style="font-size:12px;font-weight:700;color:#94a3b8;">256-bit Encryption</div>
  </div>
</div>

<!-- HOW IT WORKS -->
<div id="how" style="padding:80px 56px;background:#f8fafc;">
  <div style="display:inline-block;background:#f0f6ff;color:#2563eb;padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:14px;">Simple Process</div>
  <h2 style="font-size:34px;font-weight:800;color:#0f172a;letter-spacing:-.5px;margin-bottom:10px;">How AfyaLink Works</h2>
  <p style="font-size:15px;color:#64748b;line-height:1.7;max-width:480px;margin-bottom:48px;">From registration to referral acceptance in 4 simple steps</p>
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;">
    <div class="step-card" data-num="1" style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0;position:relative;overflow:hidden;">
      <div style="width:36px;height:36px;border-radius:10px;background:#2563eb;color:white;font-size:14px;font-weight:800;display:flex;align-items:center;justify-content:center;margin-bottom:14px;position:relative;z-index:1;">1</div>
      <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;position:relative;z-index:1;">Register Once</div>
      <div style="font-size:12px;color:#64748b;line-height:1.6;position:relative;z-index:1;">Create your free account and receive a unique Patient ID automatically</div>
    </div>
    <div class="step-card" data-num="2" style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0;position:relative;overflow:hidden;">
      <div style="width:36px;height:36px;border-radius:10px;background:#2563eb;color:white;font-size:14px;font-weight:800;display:flex;align-items:center;justify-content:center;margin-bottom:14px;position:relative;z-index:1;">2</div>
      <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;position:relative;z-index:1;">Visit a Doctor</div>
      <div style="font-size:12px;color:#64748b;line-height:1.6;position:relative;z-index:1;">Doctor creates your digital medical record and referral if specialist care is needed</div>
    </div>
    <div class="step-card" data-num="3" style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0;position:relative;overflow:hidden;">
      <div style="width:36px;height:36px;border-radius:10px;background:#2563eb;color:white;font-size:14px;font-weight:800;display:flex;align-items:center;justify-content:center;margin-bottom:14px;position:relative;z-index:1;">3</div>
      <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;position:relative;z-index:1;">Track Your Care</div>
      <div style="font-size:12px;color:#64748b;line-height:1.6;position:relative;z-index:1;">Monitor referral status, view records and find nearby hospitals in real time</div>
    </div>
    <div class="step-card" data-num="4" style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0;position:relative;overflow:hidden;">
      <div style="width:36px;height:36px;border-radius:10px;background:#2563eb;color:white;font-size:14px;font-weight:800;display:flex;align-items:center;justify-content:center;margin-bottom:14px;position:relative;z-index:1;">4</div>
      <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;position:relative;z-index:1;">Pay via M-PESA</div>
      <div style="font-size:12px;color:#64748b;line-height:1.6;position:relative;z-index:1;">Settle consultation, lab and pharmacy bills from your phone instantly</div>
    </div>
  </div>
</div>

<!-- FEATURES -->
<div id="features" style="padding:80px 56px;background:white;">
  <div style="display:inline-block;background:#f0f6ff;color:#2563eb;padding:5px 14px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:14px;">Platform Features</div>
  <h2 style="font-size:34px;font-weight:800;color:#0f172a;letter-spacing:-.5px;margin-bottom:10px;">Everything You Need,<br>Nothing You Don't</h2>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:48px;">
    <div class="feat-card" style="background:white;border-radius:14px;padding:28px;border:1px solid #e2e8f0;">
      <div style="width:50px;height:50px;border-radius:14px;background:#dbeafe;margin-bottom:18px;"></div>
      <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:8px;">Digital Referrals</h3>
      <p style="font-size:13px;color:#64748b;line-height:1.65;">Instant referrals from doctor to hospital. Real-time accept or reject. Status visible to patient immediately.</p>
    </div>
    <div class="feat-card" style="background:white;border-radius:14px;padding:28px;border:1px solid #e2e8f0;">
      <div style="width:50px;height:50px;border-radius:14px;background:#dcfce7;margin-bottom:18px;"></div>
      <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:8px;">Medical Records</h3>
      <p style="font-size:13px;color:#64748b;line-height:1.65;">Your complete health history — diagnoses, prescriptions, lab results. Download as PDF anytime.</p>
    </div>
    <div class="feat-card" style="background:white;border-radius:14px;padding:28px;border:1px solid #e2e8f0;">
      <div style="width:50px;height:50px;border-radius:14px;background:#fef3c7;margin-bottom:18px;"></div>
      <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:8px;">GPS Hospital Finder</h3>
      <p style="font-size:13px;color:#64748b;line-height:1.65;">Find verified hospitals near you using GPS. See working hours, contact details and specialties.</p>
    </div>
    <div class="feat-card" style="background:white;border-radius:14px;padding:28px;border:1px solid #e2e8f0;">
      <div style="width:50px;height:50px;border-radius:14px;background:#f0fdf4;margin-bottom:18px;"></div>
      <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:8px;">M-PESA Payments</h3>
      <p style="font-size:13px;color:#64748b;line-height:1.65;">Pay consultation, pharmacy, lab tests and inpatient bills directly from your phone via M-PESA.</p>
    </div>
    <div class="feat-card" style="background:white;border-radius:14px;padding:28px;border:1px solid #e2e8f0;">
      <div style="width:50px;height:50px;border-radius:14px;background:#fce7f3;margin-bottom:18px;"></div>
      <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:8px;">Hospital Network</h3>
      <p style="font-size:13px;color:#64748b;line-height:1.65;">Verified hospital network across Kenya. Seamless hospital-to-hospital transfers when required.</p>
    </div>
    <div class="feat-card" style="background:white;border-radius:14px;padding:28px;border:1px solid #e2e8f0;">
      <div style="width:50px;height:50px;border-radius:14px;background:#f5f3ff;margin-bottom:18px;"></div>
      <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:8px;">Private and Secure</h3>
      <p style="font-size:13px;color:#64748b;line-height:1.65;">Role-based access means only you and your authorized care team can view your health data.</p>
    </div>
  </div>
</div>
<!-- CTA -->
<div id="about" style="background:linear-gradient(135deg,#0f1f3d 0%,#1e3a5f 40%,#2563eb 100%);padding:80px 56px;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;">
  <div>
    <h2 style="font-size:38px;font-weight:800;color:white;line-height:1.2;letter-spacing:-.5px;margin-bottom:14px;">Ready to Take Control of Your Health?</h2>
    <p style="font-size:15px;color:rgba(255,255,255,.7);line-height:1.7;margin-bottom:32px;">Join AfyaLink today and experience a smarter way to access healthcare across Kenya.</p>
    <button onclick="openModal()" style="background:white;color:#2563eb;border:none;padding:14px 32px;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;display:inline-flex;align-items:center;gap:8px;">
      Create Free Account
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </button>
  </div>
  <div style="display:flex;flex-direction:column;gap:14px;">
    <div style="display:flex;align-items:center;gap:12px;"><div style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:12px;color:white;flex-shrink:0;">✓</div><div style="font-size:14px;color:rgba(255,255,255,.85);">Free to register — no credit card needed</div></div>
    <div style="display:flex;align-items:center;gap:12px;"><div style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:12px;color:white;flex-shrink:0;">✓</div><div style="font-size:14px;color:rgba(255,255,255,.85);">Unique Patient ID generated instantly</div></div>
    <div style="display:flex;align-items:center;gap:12px;"><div style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:12px;color:white;flex-shrink:0;">✓</div><div style="font-size:14px;color:rgba(255,255,255,.85);">Access your records from any device</div></div>
    <div style="display:flex;align-items:center;gap:12px;"><div style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:12px;color:white;flex-shrink:0;">✓</div><div style="font-size:14px;color:rgba(255,255,255,.85);">Pay bills securely via M-PESA</div></div>
    <div style="display:flex;align-items:center;gap:12px;"><div style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:12px;color:white;flex-shrink:0;">✓</div><div style="font-size:14px;color:rgba(255,255,255,.85);">Works across all 47 counties in Kenya</div></div>
  </div>
</div>
<!-- FOOTER -->
<div style="background:#0a0f1e;padding:56px 56px 28px;">
  <div style="display:grid;grid-template-columns:1.5fr 1fr 1fr 1.2fr;gap:40px;margin-bottom:48px;">
    <div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
        <div style="width:34px;height:34px;background:linear-gradient(135deg,#1e3a5f,#2563eb);border-radius:9px;display:flex;align-items:center;justify-content:center;">
          <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <span style="font-size:20px;font-weight:800;letter-spacing:-.5px;color:white;">Afya<span style="color:#3b82f6;">Link</span></span>
      </div>
      <div style="font-size:13px;color:rgba(255,255,255,.35);line-height:1.7;max-width:240px;">Digital Patient Referral and Health Record Platform built for Kenya's healthcare system.</div>
    </div>
    <div>
      <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;">Platform</div>
      <a href="#" class="flink">About AfyaLink</a>
      <a href="#how" class="flink">How It Works</a>
      <a href="#features" class="flink">Features</a>
      <a href="#" class="flink">Privacy Policy</a>
      <a href="#" class="flink">Terms of Service</a>
    </div>
    <div>
      <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;">Support</div>
      <a href="#" class="flink">Help Center</a>
      <a href="#" class="flink">Contact Us</a>
      <a href="#" class="flink">System Status</a>
      <a href="#" class="flink">Report an Issue</a>
    </div>
    <div>
      <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;">Healthcare Professionals</div>
      <div style="font-size:13px;color:rgba(255,255,255,.35);line-height:1.7;margin-bottom:14px;">Are you a doctor, hospital administrator or system admin?</div>
      <button onclick="document.getElementById('pro-modal').classList.add('show')" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);padding:11px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;display:flex;align-items:center;gap:8px;transition:all .15s;">
        Professional Portal
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </button>
    </div>

    <!-- PROFESSIONAL MODAL -->
    <div class="modal-bg" id="pro-modal">
      <div style="background:white;border-radius:20px;padding:36px;width:380px;position:relative;box-shadow:0 24px 80px rgba(0,0,0,.2);">
        <button onclick="document.getElementById('pro-modal').classList.remove('show')" style="position:absolute;top:16px;right:16px;width:30px;height:30px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;color:#64748b;border:none;font-family:'Inter',sans-serif;">✕</button>
        <div style="text-align:center;margin-bottom:24px;">
          <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:8px;">
            <div style="width:40px;height:40px;background:linear-gradient(135deg,#1e3a5f,#2563eb);border-radius:11px;display:flex;align-items:center;justify-content:center;">
              <svg width="20" height="20" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <div style="font-size:20px;font-weight:800;color:#0f172a;">Professional Access</div>
          </div>
          <div style="font-size:13px;color:#64748b;">Select your portal to continue</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <button onclick="document.getElementById('pro-modal').classList.remove('show');document.getElementById('doctor-modal').classList.add('show');" style="display:flex;align-items:center;gap:12px;padding:16px;border-radius:10px;font-size:13px;font-weight:700;background:#f0fdf4;color:#15803d;border:1.5px solid #bbf7d0;width:100%;cursor:pointer;font-family:'Inter',sans-serif;text-align:left;">
            <div style="width:36px;height:36px;background:#dcfce7;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
              <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
              <div>Doctor Portal</div>
              <div style="font-size:11px;font-weight:400;color:#16a34a;margin-top:2px;">Sign in or apply to register</div>
            </div>
          </button>
          <a href="{{ route('hospital.login') }}" style="display:flex;align-items:center;gap:12px;padding:16px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;background:#eff6ff;color:#1d4ed8;border:1.5px solid #bfdbfe;">
            <div style="width:36px;height:36px;background:#dbeafe;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
              <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div><div>Hospital Portal</div><div style="font-size:11px;font-weight:400;color:#2563eb;margin-top:2px;">Register your facility or sign in</div></div>
          </a>
          <a href="{{ route('admin.login') }}" style="display:flex;align-items:center;gap:12px;padding:16px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;background:#fdf2f8;color:#9d174d;border:1.5px solid #fbcfe8;">
            <div style="width:36px;height:36px;background:#fce7f3;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
              <svg width="18" height="18" fill="none" stroke="#be185d" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div><div>Admin Portal</div><div style="font-size:11px;font-weight:400;color:#be185d;margin-top:2px;">System administration access</div></div>
          </a>
        </div>
      </div>
    </div>
  </div>

<div class="modal-bg" id="doctor-modal">
  <div style="background:white;border-radius:20px;padding:36px;width:380px;position:relative;box-shadow:0 24px 80px rgba(0,0,0,.2);">
    <button onclick="document.getElementById('doctor-modal').classList.remove('show');document.getElementById('pro-modal').classList.add('show');" style="position:absolute;top:16px;left:16px;width:30px;height:30px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;color:#64748b;border:none;font-family:'Inter',sans-serif;">←</button>
    <button onclick="document.getElementById('doctor-modal').classList.remove('show')" style="position:absolute;top:16px;right:16px;width:30px;height:30px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;color:#64748b;border:none;font-family:'Inter',sans-serif;">✕</button>
    <div style="text-align:center;margin-bottom:28px;margin-top:8px;">
      <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px;">
        <div style="width:40px;height:40px;background:linear-gradient(135deg,#1e3a5f,#2563eb);border-radius:11px;display:flex;align-items:center;justify-content:center;">
          <svg width="20" height="20" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div style="font-size:20px;font-weight:800;color:#0f172a;">Doctor Portal</div>
      </div>
      <div style="font-size:13px;color:#64748b;">Sign in or apply to join AfyaLink</div>
    </div>
    <div style="display:flex;flex-direction:column;gap:10px;">
      <a href="{{ route('doctor.login') }}" style="display:flex;align-items:center;gap:12px;padding:16px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;background:#f0fdf4;color:#15803d;border:1.5px solid #bbf7d0;">
        <div style="width:36px;height:36px;background:#dcfce7;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
          <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
        </div>
        <div>
          <div>Sign In</div>
          <div style="font-size:11px;font-weight:400;color:#16a34a;margin-top:2px;">Already have an approved account</div>
        </div>
      </a>
      <a href="{{ route('doctor.apply') }}" style="display:flex;align-items:center;gap:12px;padding:16px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;background:#eff6ff;color:#1d4ed8;border:1.5px solid #bfdbfe;">
        <div style="width:36px;height:36px;background:#dbeafe;border-radius:8px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
          <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        </div>
        <div>
          <div>Apply to Register</div>
          <div style="font-size:11px;font-weight:400;color:#2563eb;margin-top:2px;">New doctor — submit your application</div>
        </div>
      </a>
    </div>
  </div>
</div>

  <div style="border-top:1px solid rgba(255,255,255,.06);padding-top:24px;display:flex;align-items:center;justify-content:space-between;">
    <div style="font-size:12px;color:rgba(255,255,255,.2);">© 2026 AfyaLink · Digital Health Platform · Kenya</div>
    <div style="font-size:12px;color:rgba(255,255,255,.2);">Built for Kenya's Healthcare</div>
  </div>
</div>

<!-- PATIENT MODAL -->
<div class="modal-bg" id="modal">
  <div style="background:white;border-radius:20px;padding:36px;width:400px;position:relative;box-shadow:0 24px 80px rgba(0,0,0,.2);">
    <button onclick="closeModal()" style="position:absolute;top:16px;right:16px;width:30px;height:30px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;color:#64748b;border:none;font-family:'Inter',sans-serif;">✕</button>
    <div style="text-align:center;margin-bottom:24px;">
      <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:8px;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#1e3a5f,#2563eb);border-radius:10px;"></div>
        <div style="font-size:20px;font-weight:800;color:#0f172a;">AfyaLink</div>
      </div>
      <div style="font-size:13px;color:#64748b;">Your health journey starts here</div>
    </div>
    <div style="display:flex;background:#f8fafc;border-radius:10px;padding:4px;margin-bottom:24px;">
      <div class="tab on" onclick="st('login',this)">Sign In</div>
      <div class="tab" onclick="st('register',this)">Create Account</div>
    </div>
    <div id="f-login" class="mform on">
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <label style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:5px;display:block;">Email Address</label>
        <input class="fi" type="email" name="email" placeholder="your@email.com" required>
        <label style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:5px;display:block;">Password</label>
        <input class="fi" type="password" name="password" placeholder="••••••••" required>
        <button type="submit" style="width:100%;background:#2563eb;color:white;border:none;padding:13px;border-radius:9px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;">Sign In to AfyaLink</button>
      </form>
      <p style="font-size:11px;color:#94a3b8;text-align:center;margin-top:12px;">Forgot password? <a href="#" style="color:#2563eb;">Reset here</a></p>
    </div>
    <div id="f-register" class="mform">
      <form method="POST" action="{{ route('register') }}?role=patient">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
          <div><label style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:5px;display:block;">First Name</label><input class="fi" type="text" name="first_name" placeholder="John" required></div>
          <div><label style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:5px;display:block;">Last Name</label><input class="fi" type="text" name="last_name" placeholder="Doe" required></div>
        </div>
        <label style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:5px;display:block;">Email Address</label>
        <input class="fi" type="email" name="email" placeholder="your@email.com" required>
        <label style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:5px;display:block;">Phone Number</label>
        <input class="fi" type="text" name="phone" placeholder="e.g. 0712345678" required>
        <label style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:5px;display:block;">Password</label>
        <input class="fi" type="password" name="password" placeholder="Create a strong password" required>
        <label style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:5px;display:block;">Confirm Password</label>
        <input class="fi" type="password" name="password_confirmation" placeholder="Re-enter your password" required>
        <button type="submit" style="width:100%;background:#2563eb;color:white;border:none;padding:13px;border-radius:9px;font-size:14px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;">Create My Account</button>
      </form>
      <p style="font-size:11px;color:#94a3b8;text-align:center;margin-top:12px;">By creating an account you agree to our <a href="#" style="color:#2563eb;">Terms</a></p>
    </div>
  </div>
</div>

<!-- SCRIPTS -->
<script>
function openModal(){document.getElementById('modal').classList.add('show')}
function closeModal(){document.getElementById('modal').classList.remove('show')}
function st(n,el){
  document.querySelectorAll('.tab').forEach(t=>t.classList.remove('on'));
  document.querySelectorAll('.mform').forEach(f=>f.classList.remove('on'));
  el.classList.add('on');
  document.getElementById('f-'+n).classList.add('on');
}
document.addEventListener('DOMContentLoaded',function(){
  var form = document.querySelector('#f-register form');
  if(form) form.addEventListener('submit',function(){
    var pwd = this.querySelector('[name=password]').value;
    var conf = document.getElementById('pwd-confirm');
    if(conf) conf.value = pwd;
  });
  var modal = document.getElementById('modal');
  if(modal) modal.addEventListener('click',function(e){if(e.target===this)closeModal();});
});
</script>
</body>
</html>
