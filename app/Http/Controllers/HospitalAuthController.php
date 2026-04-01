<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class HospitalAuthController extends Controller
{
    public function showLogin()
    {
        return view('hospital.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Only allow hospitals to login through this portal
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !in_array($user->role, ['hospital', 'facility'])) {
            throw ValidationException::withMessages([
                'email' => __('Only hospital/facility accounts can login through this portal.'),
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('hospital.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    public function showRegister()
    {
        return view('hospital.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'facility_name' => ['required', 'string', 'max:255'],
            'facility_type' => ['required', 'in:hospital,clinic,health_center,dispensary'],
            'mfl_code' => ['required', 'string', 'unique:facilities'],
            'county' => ['required', 'string'],
            'sub_county' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'admin_first_name' => ['required', 'string', 'max:255'],
            'admin_last_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Create facility first
        $facility = Facility::create([
            'name' => $data['facility_name'],
            'type' => $data['facility_type'],
            'mfl_code' => $data['mfl_code'],
            'county' => $data['county'],
            'sub_county' => $data['sub_county'],
            'phone' => $data['phone'],
            'is_active' => false, // Requires admin approval
        ]);

        // Create hospital admin user
        $user = User::create([
            'first_name' => $data['admin_first_name'],
            'last_name'  => $data['admin_last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'hospital',
            'facility_id' => $facility->id,
            'is_active'  => false, // Requires admin approval
        ]);

        return redirect()->route('hospital.login')
            ->with('success', 'Your facility has been registered. Please wait for admin approval.');
    }

    public function dashboard()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // First try to find facility by user's facility_id (most reliable)
        $facility = \App\Models\Facility::where('id', $user->facility_id)->first();

        // If not found, try by hospital_id
        if (!$facility && $user->hospital_id) {
            $facility = \App\Models\Facility::where('hospital_id', $user->hospital_id)->first();
        }

        // If still not found, try by email
        if (!$facility && $user->email) {
            $facility = \App\Models\Facility::where('email', $user->email)->first();
        }

        $facilityId = optional($facility)->id;

        $referrals = \App\Models\Referral::with(['patient','referringFacility','receivingFacility'])
            ->where('receiving_facility_id', $facilityId)
            ->latest()
            ->get();

        $outgoingReferrals = \App\Models\Referral::with(['patient','referringFacility','receivingFacility'])
            ->where('referring_facility_id', $facilityId)
            ->latest()
            ->get();

        $stats = [
            'incoming_today' => \App\Models\Referral::where('receiving_facility_id', $facilityId)
                ->whereDate('created_at', today())->count(),
            'accepted' => \App\Models\Referral::where('receiving_facility_id', $facilityId)
                ->where('status','accepted')->count(),
            'transferred_out' => \App\Models\Referral::where('referring_facility_id', $facilityId)->count(),
            'total_referrals' => \App\Models\Referral::where('receiving_facility_id', $facilityId)->count(),
        ];

        return view('hospital.dashboard', compact('facility', 'referrals', 'outgoingReferrals', 'stats'));
    }

    public function updateHours(Request $request)
    {
        $user = Auth::user();

        // First try to find facility by user's facility_id (most reliable)
        $facility = \App\Models\Facility::where('id', $user->facility_id)->first();

        // If not found, try by hospital_id
        if (!$facility && $user->hospital_id) {
            $facility = \App\Models\Facility::where('hospital_id', $user->hospital_id)->first();
        }

        if (!$facility) {
            return back()->with('error', 'Facility not found.');
        }
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $hours = [];
        foreach ($days as $day) {
            $open = $request->input('open_' . $day);
            $close = $request->input('close_' . $day);
            $closed = $request->input('closed_' . $day);
            $allday = $request->input('allday_' . $day);
            if ($closed) {
                $hours[$day] = 'Closed';
            } elseif ($allday) {
                $hours[$day] = 'Open 24 Hours';
            } elseif ($open && $close) {
                $hours[$day] = $open . ' - ' . $close;
            } else {
                $hours[$day] = 'Not set';
            }
        }
        $facility->working_hours = json_encode($hours);
        $facility->save();
        return back()->with('success', 'Working hours updated successfully!');
    }

    /**
     * Handle hospital logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('hospital.login');
    }

    public function downloadReport()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // First try to find facility by user's facility_id (most reliable)
        $facility = \App\Models\Facility::where('id', $user->facility_id)->first();

        // If not found, try by hospital_id
        if (!$facility && $user->hospital_id) {
            $facility = \App\Models\Facility::where('hospital_id', $user->hospital_id)->first();
        }

        $facilityId = optional($facility)->id;

        $incomingReferrals = \App\Models\Referral::with(['patient','referringFacility','receivingFacility'])
            ->where('receiving_facility_id', $facilityId)
            ->latest()->get();

        $outgoingReferrals = \App\Models\Referral::with(['patient','referringFacility','receivingFacility'])
            ->where('referring_facility_id', $facilityId)
            ->latest()->get();

        $allReferrals = $incomingReferrals->merge($outgoingReferrals);

        $stats = [
            'total' => $allReferrals->count(),
            'accepted' => $allReferrals->where('status','accepted')->count(),
            'pending' => $allReferrals->where('status','pending')->count(),
            'rejected' => $allReferrals->where('status','rejected')->count(),
            'incoming' => $incomingReferrals->count(),
            'outgoing' => $outgoingReferrals->count(),
        ];
        $generatedAt = now()->format('d M Y, h:i A');
        $facilityName = optional($facility)->name ?? 'Hospital';
        $rows = '';
        foreach($allReferrals as $r) {
            $patient = optional($r->patient)->first_name . ' ' . optional($r->patient)->last_name;
            $from = optional($r->referringFacility)->name ?? 'N/A';
            $to = optional($r->receivingFacility)->name ?? 'N/A';
            $statusColor = $r->status === 'accepted' ? '#16a34a' : ($r->status === 'rejected' ? '#dc2626' : '#d97706');
            $rows .= '<tr>
                <td style="padding:8px;border-bottom:1px solid #f1f5f9;">' . ($r->id ? 'REF-' . str_pad($r->id,5,'0',STR_PAD_LEFT) : 'N/A') . '</td>
                <td style="padding:8px;border-bottom:1px solid #f1f5f9;">' . $patient . '</td>
                <td style="padding:8px;border-bottom:1px solid #f1f5f9;">' . $from . '</td>
                <td style="padding:8px;border-bottom:1px solid #f1f5f9;">' . $to . '</td>
                <td style="padding:8px;border-bottom:1px solid #f1f5f9;">' . ($r->reason ?? 'N/A') . '</td>
                <td style="padding:8px;border-bottom:1px solid #f1f5f9;color:' . $statusColor . ';font-weight:700;">' . ucfirst($r->status ?? 'pending') . '</td>
                <td style="padding:8px;border-bottom:1px solid #f1f5f9;">' . ($r->created_at ? $r->created_at->format('d M Y') : 'N/A') . '</td>
            </tr>';
        }
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">
        <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; color: #0f172a; font-size: 12px; }
        .header { background: #1e3a5f; padding: 24px 32px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 22px; font-weight: 900; color: white; }
        .doc-info { text-align: right; color: white; }
        .doc-title { font-size: 15px; font-weight: 700; }
        .doc-meta { font-size: 10px; color: rgba(255,255,255,0.6); margin-top: 3px; }
        .stats-bar { display: flex; background: #f0f6ff; padding: 12px 32px; border-bottom: 2px solid #dbeafe; }
        .stat { flex: 1; text-align: center; }
        .stat-num { font-size: 22px; font-weight: 800; color: #2563eb; }
        .stat-label { font-size: 9px; color: #94a3b8; text-transform: uppercase; margin-top: 2px; }
        .body { padding: 24px 32px; }
        .section-title { font-size: 11px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #e2e8f0; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; padding: 8px; text-align: left; font-size: 9px; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.06em; }
        .footer { background: #0f172a; padding: 14px 32px; display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        .footer-logo { font-size: 14px; font-weight: 900; color: white; }
        .footer-text { font-size: 9px; color: rgba(255,255,255,0.4); margin-top: 3px; }
        </style></head><body>
        <div class="header">
            <div><div class="logo">AfyaLink</div><div class="doc-meta">Digital Health Record Platform</div></div>
            <div class="doc-info"><div class="doc-title">Referral Report</div><div class="doc-meta">' . $facilityName . '</div><div class="doc-meta">Generated: ' . $generatedAt . '</div></div>
        </div>
        <div class="stats-bar">
            <div class="stat"><div class="stat-num">' . $stats['total'] . '</div><div class="stat-label">Total</div></div>
            <div class="stat"><div class="stat-num" style="color:#16a34a">' . $stats['accepted'] . '</div><div class="stat-label">Accepted</div></div>
            <div class="stat"><div class="stat-num" style="color:#d97706">' . $stats['pending'] . '</div><div class="stat-label">Pending</div></div>
            <div class="stat"><div class="stat-num" style="color:#dc2626">' . $stats['rejected'] . '</div><div class="stat-label">Rejected</div></div>
            <div class="stat"><div class="stat-num" style="color:#2563eb">' . $stats['incoming'] . '</div><div class="stat-label">Incoming</div></div>
            <div class="stat"><div class="stat-num" style="color:#9333ea">' . $stats['outgoing'] . '</div><div class="stat-label">Outgoing</div></div>
        </div>
        <div class="body">
            <div class="section-title">All Referrals</div>
            <table><thead><tr><th>Ref #</th><th>Patient</th><th>From</th><th>To</th><th>Reason</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>' . $rows . '</tbody></table>
        </div>
        <div class="footer">
            <div><div class="footer-logo">AfyaLink</div><div class="footer-text">support@afyalink.ke · Kenya</div></div>
            <div style="text-align:right"><div class="footer-text">© 2026 AfyaLink. All rights reserved.</div></div>
        </div>
        </body></html>';
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return response()->make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="referral-report-' . date('Y-m-d') . '.pdf"',
        ]);
    }
}
