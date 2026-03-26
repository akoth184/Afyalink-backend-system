<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Payments — AfyaLink</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
.fi{width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:10px 12px;font-size:13px;font-family:'Inter',sans-serif;outline:none;transition:border-color .15s;}
.fi:focus{border-color:#2563eb;background:white;}
.pay-type{border:2px solid #e2e8f0;border-radius:10px;padding:14px;cursor:pointer;transition:all .15s;background:white;}
.pay-type.selected{border-color:#2563eb;background:#f0f6ff;}
</style>
</head>
<body style="background:#f0f6ff;">
<div style="display:flex;min-height:100vh;">
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);">
    <div style="font-size:16px;font-weight:700;color:white;">AfyaLink</div>
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Patient Portal</div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->first_name ?? 'P', 0, 1)) }}</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">{{ Auth::user()->patient_id ?? 'Patient' }}</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Health</div>
    <a href="{{ route('patient.dashboard') }}" class="slink">Dashboard</a>
    <a href="{{ route('patient.records') }}" class="slink">Medical Records</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Referrals</div>
    <a href="{{ route('patient.referrals') }}" class="slink">My Referrals</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Payments</div>
    <a href="{{ route('patient.payments') }}" class="slink on">M-PESA Payments</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Explore</div>
    <a href="{{ route('patient.nearby-hospitals') }}" class="slink">Nearby Hospitals</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Account</div>
    <a href="{{ route('profile') }}" class="slink">Profile & Settings</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Logout</button>
    </form>
  </div>
</aside>
<div style="margin-left:220px;flex:1;">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:10;">
    <div style="font-size:20px;font-weight:700;color:#0f172a;">M-PESA Payments</div>
    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Pay for healthcare services securely via M-PESA</div>
  </div>
  <div style="padding:24px 28px;">
    @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;font-weight:500;">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fecaca;color:#dc2626;padding:12px 16px;border-radius:8px;font-size:13px;margin-bottom:16px;">{{ session('error') }}</div>
    @endif
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
      <!-- PAYMENT FORM -->
      <div style="background:white;border-radius:12px;padding:24px;border:1px solid #e2e8f0;">
        <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#16a34a;display:inline-block;"></span>Make a Payment</div>
        <div style="font-size:12px;color:#64748b;margin-bottom:20px;">Select payment type and enter your M-PESA number</div>
        <form method="POST" action="{{ route('patient.payments.initiate') }}">
          @csrf
          <input type="hidden" name="payment_type" id="selected-type" value="">
          <div style="font-size:11px;font-weight:600;color:#64748b;margin-bottom:8px;text-transform:uppercase;letter-spacing:.06em;">Payment Type</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">
            <div class="pay-type" onclick="selectType('consultation',this)">
              <div style="width:32px;height:32px;background:#dbeafe;border-radius:8px;margin-bottom:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <div style="font-size:12px;font-weight:700;color:#0f172a;">Consultation</div>
              <div style="font-size:10px;color:#64748b;margin-top:2px;">Doctor visit fee</div>
            </div>
            <div class="pay-type" onclick="selectType('pharmacy',this)">
              <div style="width:32px;height:32px;background:#dcfce7;border-radius:8px;margin-bottom:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
              </div>
              <div style="font-size:12px;font-weight:700;color:#0f172a;">Pharmacy</div>
              <div style="font-size:10px;color:#64748b;margin-top:2px;">Medicine purchase</div>
            </div>
            <div class="pay-type" onclick="selectType('lab',this)">
              <div style="width:32px;height:32px;background:#fef3c7;border-radius:8px;margin-bottom:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v11m0 0H5a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2h-4m-6 0h6"/></svg>
              </div>
              <div style="font-size:12px;font-weight:700;color:#0f172a;">Lab Test</div>
              <div style="font-size:10px;color:#64748b;margin-top:2px;">Laboratory tests</div>
            </div>
            <div class="pay-type" onclick="selectType('inpatient',this)">
              <div style="width:32px;height:32px;background:#fce7f3;border-radius:8px;margin-bottom:8px;display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="#be185d" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              </div>
              <div style="font-size:12px;font-weight:700;color:#0f172a;">Inpatient</div>
              <div style="font-size:10px;color:#64748b;margin-top:2px;">Hospital stay bill</div>
            </div>
          </div>
          <div style="margin-bottom:14px;">
            <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:.06em;">Amount (KES)</label>
            <input class="fi" type="number" name="amount" placeholder="e.g. 500" min="1" required>
          </div>
          <div style="margin-bottom:20px;">
            <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:.06em;">M-PESA Phone Number</label>
            <input class="fi" type="text" name="phone" placeholder="e.g. 0712345678" value="{{ Auth::user()->phone ?? '' }}" required>
            <div style="font-size:11px;color:#94a3b8;margin-top:5px;">Enter the number registered with M-PESA</div>
          </div>
          <button type="submit" id="pay-btn" disabled style="width:100%;background:#e2e8f0;color:#94a3b8;border:none;padding:13px;border-radius:9px;font-size:14px;font-weight:700;cursor:not-allowed;font-family:'Inter',sans-serif;transition:all .15s;">Select Payment Type First</button>
        </form>
        <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:16px;padding-top:16px;border-top:1px solid #f1f5f9;">
          <div style="width:20px;height:20px;background:#16a34a;border-radius:4px;display:flex;align-items:center;justify-content:center;">
            <svg width="12" height="12" fill="white" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <span style="font-size:11px;color:#64748b;">Secured by Safaricom M-PESA</span>
        </div>
      </div>
      <!-- PAYMENT HISTORY -->
      <div style="background:white;border-radius:12px;padding:24px;border:1px solid #e2e8f0;">
        <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;display:flex;align-items:center;gap:8px;"><span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>Payment History</div>
        <div style="font-size:12px;color:#64748b;margin-bottom:16px;">Your recent M-PESA transactions</div>
        @forelse($payments as $payment)
        <div style="padding:12px 0;border-bottom:1px solid #f1f5f9;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
            <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ ucfirst($payment->payment_type) }}</div>
            <div style="font-size:13px;font-weight:700;color:#0f172a;">KES {{ number_format($payment->amount, 2) }}</div>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:11px;color:#94a3b8;">{{ $payment->created_at->format('d M Y, h:i A') }} @if($payment->mpesa_receipt)· {{ $payment->mpesa_receipt }}@endif</div>
            <span style="padding:2px 8px;border-radius:20px;font-size:10px;font-weight:600;background:{{ $payment->status === 'completed' ? '#dcfce7' : ($payment->status === 'failed' ? '#fee2e2' : '#fef3c7') }};color:{{ $payment->status === 'completed' ? '#16a34a' : ($payment->status === 'failed' ? '#dc2626' : '#d97706') }};">{{ ucfirst($payment->status) }}</span>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:32px 0;color:#94a3b8;font-size:13px;">No payments yet</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
</div>
<script>
function selectType(type, el) {
  document.querySelectorAll('.pay-type').forEach(e => e.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('selected-type').value = type;
  var btn = document.getElementById('pay-btn');
  btn.disabled = false;
  btn.style.background = '#16a34a';
  btn.style.color = 'white';
  btn.style.cursor = 'pointer';
  btn.textContent = 'Pay via M-PESA';
}
</script>
</body>
</html>
