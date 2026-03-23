<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>AfyaLink System Report</title>
<style>
body{font-family:'Arial',sans-serif;color:#0f172a;margin:0;padding:20px;background:white;}
.header{background:#1e3a5f;color:white;padding:24px;border-radius:8px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;}
.header h1{font-size:22px;font-weight:700;margin:0;}
.header p{font-size:12px;color:rgba(255,255,255,.7);margin:4px 0 0;}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;}
.stat{background:#f0f6ff;border-radius:8px;padding:16px;text-align:center;border:1px solid #e2e8f0;}
.stat-num{font-size:28px;font-weight:700;color:#2563eb;}
.stat-label{font-size:11px;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-top:4px;}
.section{background:white;border:1px solid #e2e8f0;border-radius:8px;padding:20px;margin-bottom:20px;}
.section h2{font-size:15px;font-weight:700;color:#0f172a;margin:0 0 14px;padding-bottom:10px;border-bottom:2px solid #e2e8f0;}
table{width:100%;border-collapse:collapse;font-size:12px;}
th{text-align:left;padding:8px 10px;background:#f8fafc;color:#64748b;font-size:10px;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #e2e8f0;}
td{padding:10px;border-bottom:1px solid #f1f5f9;color:#0f172a;}
.badge{padding:2px 8px;border-radius:20px;font-size:10px;font-weight:600;}
.b-s{background:#dcfce7;color:#16a34a;}
.b-p{background:#fef3c7;color:#d97706;}
.b-r{background:#fee2e2;color:#dc2626;}
.print-btn{position:fixed;top:20px;right:20px;background:#2563eb;color:white;border:none;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;}
@media print{.print-btn{display:none;}}
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">Print / Save PDF</button>
<div class="header">
  <div>
    <h1>AfyaLink — System Report</h1>
    <p>Digital Patient Referral & Health Record Platform</p>
    <p>Generated: {{ $generated_at }}</p>
  </div>
  <div style="text-align:right;font-size:13px;">
    <div style="font-size:24px;font-weight:700;">AfyaLink</div>
    <div style="color:rgba(255,255,255,.7);">Kenya Health System</div>
  </div>
</div>
<div class="stats">
  <div class="stat"><div class="stat-num">{{ $total_patients }}</div><div class="stat-label">Total Patients</div></div>
  <div class="stat"><div class="stat-num">{{ $total_doctors }}</div><div class="stat-label">Total Doctors</div></div>
  <div class="stat"><div class="stat-num">{{ $total_facilities }}</div><div class="stat-label">Facilities</div></div>
  <div class="stat"><div class="stat-num">{{ $total_referrals }}</div><div class="stat-label">Total Referrals</div></div>
</div>
<div class="section">
  <h2>Referral Summary</h2>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">
    <div style="background:#f0fdf4;padding:14px;border-radius:8px;text-align:center;"><div style="font-size:22px;font-weight:700;color:#16a34a;">{{ $accepted_referrals }}</div><div style="font-size:11px;color:#64748b;">Accepted</div></div>
    <div style="background:#fef3c7;padding:14px;border-radius:8px;text-align:center;"><div style="font-size:22px;font-weight:700;color:#d97706;">{{ $pending_referrals }}</div><div style="font-size:11px;color:#64748b;">Pending</div></div>
    <div style="background:#fee2e2;padding:14px;border-radius:8px;text-align:center;"><div style="font-size:22px;font-weight:700;color:#dc2626;">{{ $rejected_referrals }}</div><div style="font-size:11px;color:#64748b;">Rejected</div></div>
  </div>
  <table>
    <thead><tr><th>Ref #</th><th>Patient</th><th>From Facility</th><th>To Facility</th><th>Reason</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
    @foreach($referrals as $referral)
    <tr>
      <td style="font-weight:600;">REF-{{ str_pad($referral->id,5,'0',STR_PAD_LEFT) }}</td>
      <td>{{ optional($referral->patient)->first_name ?? 'N/A' }} {{ optional($referral->patient)->last_name ?? '' }}</td>
      <td>{{ optional($referral->referringFacility)->name ?? 'N/A' }}</td>
      <td>{{ optional($referral->receivingFacility)->name ?? 'N/A' }}</td>
      <td>{{ $referral->reason ?? 'N/A' }}</td>
      <td><span class="badge b-{{ substr($referral->status ?? 'p',0,1) }}">{{ ucfirst($referral->status ?? 'pending') }}</span></td>
      <td>{{ $referral->created_at->format('d M Y') }}</td>
    </tr>
    @endforeach
    </tbody>
  </table>
</div>
<div style="text-align:center;font-size:11px;color:#94a3b8;margin-top:20px;padding-top:20px;border-top:1px solid #e2e8f0;">
  AfyaLink — Digital Patient Referral & Health Record Platform · Kenya · {{ $generated_at }}
</div>
</body>
</html>
