<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function __construct()
    {
        // Require authentication for all referral routes
        $this->middleware('auth');
    }

    /**
     * List referrals - filtered by user role
     * - Patients: Only see their own referrals
     * - Doctors/Hospitals: See all referrals they have access to
     */
    public function index()
    {
        $user = Auth::user();

        // Patients can only see their own referrals
        if ($user->role === 'patient') {
            $referrals = Referral::with(['patient', 'referringFacility', 'receivingFacility'])
                ->where('patient_id', $user->id)
                ->latest()
                ->paginate(10);
            return view('referrals.index', compact('referrals'));
        }

        // Doctors see referrals they created
        if ($user->role === 'doctor') {
            $referrals = Referral::with(['patient', 'referringFacility', 'receivingFacility'])
                ->where('referred_by', $user->id)
                ->latest()
                ->paginate(10);
            return view('referrals.index', compact('referrals'));
        }

        // Hospital/facility admins see all referrals for their facility
        if (in_array($user->role, ['hospital', 'facility']) && $user->facility_id) {
            $referrals = Referral::with(['patient', 'referringFacility', 'receivingFacility'])
                ->where(function($query) use ($user) {
                    $query->where('referring_facility_id', $user->facility_id)
                        ->orWhere('receiving_facility_id', $user->facility_id);
                })
                ->latest()
                ->paginate(10);

            return view('referrals.index', compact('referrals'));
        }

        // Admins see all referrals
        if ($user->role === 'admin') {
            $referrals = Referral::with(['patient', 'referringFacility', 'receivingFacility'])
                ->latest()
                ->paginate(10);

            return view('referrals.index', compact('referrals'));
        }

        // Fallback - no referrals
        return view('referrals.index', ['referrals' => collect()]);
    }

    /**
     * Show referral details - filtered by user role
     * Patients can only view their own referrals
     */
    public function show(Referral $referral)
    {
        $user = Auth::user();

        // Patients can only view their own referrals
        if ($user->role === 'patient') {
            $patient = Patient::where('email', $user->email)->first();

            if (!$patient || $referral->patient_id !== $patient->id) {
                abort(403, 'You are not authorized to view this referral.');
            }
        }

        // Doctors can only view referrals from their facility
        if ($user->role === 'doctor' && $user->facility_id) {
            if ($referral->referring_facility_id !== $user->facility_id &&
                $referral->receiving_facility_id !== $user->facility_id) {
                abort(403, 'You are not authorized to view this referral.');
            }
        }

        // Hospital/facility can only view referrals for their facility
        if (in_array($user->role, ['hospital', 'facility']) && $user->facility_id) {
            if ($referral->referring_facility_id !== $user->facility_id &&
                $referral->receiving_facility_id !== $user->facility_id) {
                abort(403, 'You are not authorized to view this referral.');
            }
        }

        $referral->load(['patient', 'referringFacility', 'receivingFacility', 'referredBy']);
        return view('referrals.show', compact('referral'));
    }

    /**
     * Create referral form - Only doctors can access
     */
    public function create()
    {
        $patients = \App\Models\User::where('role', 'patient')
            ->orderBy('first_name')->get();
        $facilities = \App\Models\Facility::where('is_active', true)
            ->orderBy('name')->get();
        return view('referrals.create', compact('patients', 'facilities'));
    }

    /**
     * Store new referral - Only doctors can create
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!Gate::allows('create-referrals') && !in_array($user->role, ['hospital', 'facility'])) {
            abort(403, 'You are not authorized to create referrals.');
        }

        $data = $request->validate([
            'patient_id'            => 'required|exists:users,id',
            'referring_facility_id' => 'required|exists:facilities,id',
            'receiving_facility_id' => 'required|exists:facilities,id|different:referring_facility_id',
            'reason'               => 'required|string',
            'notes'                => 'nullable|string',
            'priority'             => 'nullable|string|in:routine,urgent,emergency',
            'appointment_date'     => 'nullable|date|after_or_equal:today',
            'clinical_summary'     => 'nullable|string',
            'attachment'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data['status']      = 'pending';
        $data['referred_by'] = auth()->id();
        $data['priority'] = $request->priority ?? 'routine';
        $data['appointment_date'] = $request->appointment_date;
        $data['clinical_summary'] = $request->clinical_summary;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/referral_attachments', $filename);
            $attachmentPath = 'referral_attachments/' . $filename;
        }
        $data['attachment_path'] = $attachmentPath;

        $referral = Referral::create($data);

        // Get patient name for notification
        $patient = \App\Models\User::find($request->patient_id);
        $patientName = $patient ? ($patient->first_name . ' ' . $patient->last_name) : 'Unknown Patient';

        // Notify receiving hospital
        $receivingFacility = \App\Models\Facility::find($request->receiving_facility_id);
        if ($receivingFacility) {
            $hospitalUser = \App\Models\User::where('facility_id', $receivingFacility->id)
                ->where('role', 'hospital')
                ->first();
            if ($hospitalUser) {
                \App\Models\Notification::send(
                    $hospitalUser->id,
                    'new_referral',
                    'New Referral Received',
                    'A new referral has been sent to ' . $receivingFacility->name . ' for patient ' . $patientName . '.',
                    $referral->id
                );
            }
        }

        $receivingHospitalUser = \App\Models\User::where('role','hospital')
            ->where('facility_id', $request->receiving_facility_id)
            ->first();
        if ($receivingHospitalUser) {
            \App\Models\Notification::send(
                $receivingHospitalUser->id,
                'new_referral',
                'New Referral Request',
                'A new referral has been sent to your facility for patient ' . optional(\App\Models\User::find($request->patient_id))->first_name . '. Please review and respond.',
                $referral->id
            );
        }

        $user = Auth::user();
        if (in_array($user->role, ['hospital', 'facility'])) {
            return redirect()->route('hospital.dashboard')
                            ->with('success', 'Transfer initiated successfully!');
        }
        return redirect()->route('referrals.show', $referral)
                        ->with('success', 'Referral created successfully.');
    }

    public function edit(Referral $referral)
    {
        // Check if user can create referrals (same permission)
        if (!Gate::allows('create-referrals')) {
            abort(403, 'You are not authorized to edit referrals.');
        }

        $patients   = Patient::orderBy('first_name')->get();
        $facilities = Facility::orderBy('name')->get();
        return view('referrals.edit', compact('referral', 'patients', 'facilities'));
    }

    public function update(Request $request, Referral $referral)
    {
        // Check if user can create referrals
        if (!Gate::allows('create-referrals')) {
            abort(403, 'You are not authorized to update referrals.');
        }

        $data = $request->validate([
            'patient_id'            => 'required|exists:users',
            'referring_facility_id' => 'required|exists:facilities,id',
            'receiving_facility_id' => 'required|exists:facilities,id',
            'reason'               => 'required|string',
            'notes'                => 'nullable|string',
            'status'               => 'required|in:pending,accepted,rejected,completed',
        ]);

        $referral->update($data);

        return redirect()->route('referrals.show', $referral)
                        ->with('success', 'Referral updated successfully.');
    }

    public function destroy(Referral $referral)
    {
        // Check if user can create referrals (admin/doctor only)
        if (!Gate::allows('create-referrals')) {
            abort(403, 'You are not authorized to delete referrals.');
        }

        $referral->delete();
        return redirect()->route('referrals.index')
                        ->with('success', 'Referral deleted.');
    }

    /**
     * Update referral status - Only receiving facility can update
     */
    public function updateStatus(Request $request, $id)
    {
        $referral = \App\Models\Referral::with(['patient','referringFacility','receivingFacility'])->findOrFail($id);
        $user = Auth::user();

        // Only the receiving facility can accept/reject referrals
        if (in_array($user->role, ['hospital', 'facility']) && $user->facility_id) {
            if ($referral->receiving_facility_id !== $user->facility_id) {
                abort(403, 'Only the receiving facility can accept or reject this referral.');
            }
        }

        $status = $request->input('status');
        $rejectionReason = $request->input('rejection_reason');

        $referral->status = $status;
        $referral->status_updated_at = now();
        $referral->status_updated_by = Auth::id();

        if ($status === 'rejected' && $rejectionReason) {
            $referral->rejection_reason = $rejectionReason;
        }
        $referral->save();

        \App\Models\AuditLog::recordUpdated($referral, "Referral status changed to {$status}");

        $patientName = optional($referral->patient)->first_name . ' ' . optional($referral->patient)->last_name;
        $receivingHospital = optional($referral->receivingFacility)->name ?? 'Hospital';
        $referringFacility = optional($referral->referringFacility)->name ?? 'Facility';

        // Notify patient
        if ($referral->patient_id) {
            if ($status === 'accepted') {
                \App\Models\Notification::send(
                    $referral->patient_id,
                    'referral_accepted',
                    'Referral Accepted',
                    'Your referral to ' . $receivingHospital . ' has been accepted.',
                    $referral->id
                );
            } elseif ($status === 'rejected') {
                \App\Models\Notification::send(
                    $referral->patient_id,
                    'referral_rejected',
                    'Referral Rejected',
                    'Your referral to ' . $receivingHospital . ' was rejected. Reason: ' . ($rejectionReason ?? 'No reason provided.'),
                    $referral->id
                );
            }
        }

        // Notify referring doctor or hospital
        if ($referral->referred_by) {
            $referredByUser = \App\Models\User::find($referral->referred_by);
            if ($referredByUser) {
                if ($status === 'accepted') {
                    \App\Models\Notification::send(
                        $referredByUser->id,
                        'referral_accepted',
                        'Referral Accepted',
                        'Your referral for ' . $patientName . ' to ' . $receivingHospital . ' has been accepted.',
                        $referral->id
                    );
                } elseif ($status === 'rejected') {
                    \App\Models\Notification::send(
                        $referredByUser->id,
                        'referral_rejected',
                        'Referral Rejected',
                        $receivingHospital . ' rejected the referral for ' . $patientName . '. Reason: ' . ($rejectionReason ?? 'No reason provided.'),
                        $referral->id
                    );
                }
            }
        }

        // Notify receiving hospital when new referral is created
        $redirectTo = $request->input('redirect_to', '');
        return redirect()->back()->with('success', 'Referral ' . $status . ' successfully.')->withFragment($redirectTo);
    }

    public function giveConsent($id)
    {
        $referral = \App\Models\Referral::findOrFail($id);
        $user = Auth::user();

        if ($referral->patient_id !== $user->id) {
            abort(403, 'You are not authorized to consent to this referral.');
        }

        if ($referral->patient_consented) {
            return back()->with('info', 'You have already consented to this referral.');
        }

        $referral->patient_consented = true;
        $referral->consented_at = now();
        $referral->save();

        $referringDoctor = \App\Models\User::find($referral->referred_by);
        if ($referringDoctor) {
            \App\Models\Notification::send(
                $referringDoctor->id,
                'consent_given',
                'Patient Consented',
                'Patient ' . $user->first_name . ' ' . $user->last_name . ' has consented to the referral.',
                $referral->id
            );
        }

        $receivingFacility = \App\Models\Facility::find($referral->receiving_facility_id);
        if ($receivingFacility) {
            $hospitalUser = \App\Models\User::where('facility_id', $receivingFacility->id)
                ->where('role', 'hospital')
                ->first();
            if ($hospitalUser) {
                \App\Models\Notification::send(
                    $hospitalUser->id,
                    'consent_given',
                    'Patient Consented',
                    'Patient ' . $user->first_name . ' ' . $user->last_name . ' has consented to the referral.',
                    $referral->id
                );
            }
        }

        return back()->with('success', 'You have successfully consented to this referral.');
    }

    public function submitFeedback(Request $request, $id)
    {
        $referral = \App\Models\Referral::findOrFail($id);
        $user = Auth::user();

        if (!in_array($user->role, ['hospital', 'facility']) || $user->facility_id !== $referral->receiving_facility_id) {
            abort(403, 'You are not authorized to submit feedback for this referral.');
        }

        $request->validate([
            'feedback' => 'required|string',
        ]);

        $referral->status_detail = $request->feedback;
        $referral->save();

        $referringDoctor = \App\Models\User::find($referral->referred_by);
        if ($referringDoctor) {
            \App\Models\Notification::send(
                $referringDoctor->id,
                'treatment_feedback',
                'Treatment Feedback Received',
                'You have received treatment feedback for patient referral REF-' . str_pad($referral->id, 5, '0', STR_PAD_LEFT) . '.',
                $referral->id
            );
        }

        return back()->with('success', 'Feedback sent to referring doctor.');
    }

    public function updateTransport(Request $request, $id)
    {
        $referral = \App\Models\Referral::findOrFail($id);
        $referral->update(['transport_status' => $request->transport_status]);
        $statusLabels = [
            'in_transit'      => 'Patient is on the way to your facility',
            'arrived'         => 'Patient has arrived at the facility',
            'under_treatment' => 'Patient is currently under treatment',
            'discharged'      => 'Patient has been discharged',
        ];
        $label = $statusLabels[$request->transport_status] ?? 'Status updated';
        \App\Models\Notification::send(
            $referral->patient_id,
            'transport_update',
            'Referral Status Update',
            $label . ' at ' . optional($referral->receivingFacility)->name . '.',
            $referral->id
        );
        if ($referral->referred_by) {
            \App\Models\Notification::send(
                $referral->referred_by,
                'transport_update',
                'Patient Transport Update',
                'Patient ' . optional($referral->patient)->first_name . ': ' . $label . '.',
                $referral->id
            );
        }
        return back()->with('success', 'Transport status updated. Patient and referring doctor have been notified.');
    }

    public function downloadLetter($id)
    {
        $referral = \App\Models\Referral::with(['patient','referringFacility','receivingFacility','referredByUser'])->findOrFail($id);
        $refNumber = 'REF-' . str_pad($referral->id, 5, '0', STR_PAD_LEFT);
        $date = $referral->created_at->format('d F Y');
        $patientName = optional($referral->patient)->first_name . ' ' . optional($referral->patient)->last_name;
        $patientId = optional($referral->patient)->patient_id ?? 'N/A';
        $fromFacility = optional($referral->referringFacility)->name ?? 'N/A';
        $toFacility = optional($referral->receivingFacility)->name ?? 'N/A';
        $doctor = optional(\App\Models\User::find($referral->referred_by));
        $doctorName = 'Dr. ' . ($doctor->first_name ?? 'N/A') . ' ' . ($doctor->last_name ?? '');
        $priority = ucfirst($referral->priority ?? 'Routine');
        $reason = $referral->reason ?? 'N/A';
        $clinicalSummary = $referral->clinical_summary ?? 'See attached medical records.';
        $consentStatus = $referral->patient_consented ? 'Patient has consented to this referral on ' . ($referral->consented_at ? \Carbon\Carbon::parse($referral->consented_at)->format('d M Y') : 'N/A') . '.' : 'Consent pending.';

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">
        <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:Arial,sans-serif;color:#0f172a;font-size:12px;padding:40px;}
        .header{background:#1e3a5f;padding:20px 32px;display:flex;justify-content:space-between;align-items:center;margin-bottom:0;}
        .logo{font-size:22px;font-weight:900;color:white;}
        .logo-sub{font-size:10px;color:rgba(255,255,255,0.6);margin-top:2px;}
        .ref-badge{background:rgba(255,255,255,0.15);padding:8px 16px;border-radius:6px;text-align:right;}
        .ref-num{font-size:16px;font-weight:700;color:white;}
        .ref-date{font-size:10px;color:rgba(255,255,255,0.6);margin-top:2px;}
        .priority-bar{padding:8px 32px;font-size:11px;font-weight:700;text-align:center;background:' . ($referral->priority === 'emergency' ? '#fee2e2' : ($referral->priority === 'urgent' ? '#fef3c7' : '#dbeafe')) . ';color:' . ($referral->priority === 'emergency' ? '#dc2626' : ($referral->priority === 'urgent' ? '#d97706' : '#1d4ed8')) . ';}
        .body{padding:24px 0;}
        .title{font-size:16px;font-weight:700;color:#0f172a;margin-bottom:4px;}
        .subtitle{font-size:11px;color:#64748b;margin-bottom:20px;}
        .section{margin-bottom:18px;}
        .section-title{font-size:10px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:0.08em;border-bottom:1.5px solid #e2e8f0;padding-bottom:5px;margin-bottom:10px;}
        .info-grid{display:flex;gap:0;}
        .info-item{flex:1;margin-bottom:8px;}
        .info-label{font-size:9px;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:2px;}
        .info-value{font-size:12px;font-weight:600;color:#0f172a;}
        .clinical-box{background:#f8fafc;border-left:3px solid #2563eb;padding:10px 14px;border-radius:0 6px 6px 0;margin-bottom:8px;}
        .clinical-label{font-size:9px;color:#2563eb;text-transform:uppercase;font-weight:700;margin-bottom:4px;}
        .clinical-value{font-size:11px;color:#0f172a;line-height:1.6;}
        .consent-box{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;padding:10px 14px;margin-top:14px;}
        .signature-area{display:flex;justify-content:space-between;margin-top:28px;padding-top:16px;border-top:1px solid #e2e8f0;}
        .sig-block{text-align:center;width:45%;}
        .sig-line{border-bottom:1px solid #0f172a;margin-bottom:6px;height:40px;}
        .sig-label{font-size:10px;color:#64748b;}
        .disclaimer{background:#fef3c7;border:1px solid #fde68a;border-radius:6px;padding:10px 14px;margin-top:16px;font-size:10px;color:#92400e;}
        .footer{background:#0f172a;padding:12px 32px;display:flex;justify-content:space-between;align-items:center;margin-top:20px;}
        .footer-text{font-size:9px;color:rgba(255,255,255,0.4);}
        </style></head><body>
        <div class="header">
          <div><div class="logo">AfyaLink</div><div class="logo-sub">Digital Patient Referral & Health Record Platform</div></div>
          <div class="ref-badge"><div class="ref-num">' . $refNumber . '</div><div class="ref-date">' . $date . '</div></div>
        </div>
        <div class="priority-bar">PRIORITY: ' . strtoupper($priority) . ' REFERRAL</div>
        <div class="body">
          <div class="title">PATIENT REFERRAL LETTER</div>
          <div class="subtitle">This is an official digital referral letter generated by AfyaLink Health System</div>
          <div class="section">
            <div class="section-title">Patient Information</div>
            <table style="width:100%;border-collapse:collapse;">
              <tr>
                <td style="width:50%;padding-bottom:8px;"><div class="info-label">Full Name</div><div class="info-value">' . $patientName . '</div></td>
                <td style="width:50%;padding-bottom:8px;"><div class="info-label">Patient ID</div><div class="info-value">' . $patientId . '</div></td>
              </tr>
              <tr>
                <td><div class="info-label">Date of Referral</div><div class="info-value">' . $date . '</div></td>
                <td><div class="info-label">Priority Level</div><div class="info-value" style="color:' . ($referral->priority === 'emergency' ? '#dc2626' : ($referral->priority === 'urgent' ? '#d97706' : '#2563eb')) . ';">' . $priority . '</div></td>
              </tr>
            </table>
          </div>
          <div class="section">
            <div class="section-title">Referral Details</div>
            <table style="width:100%;border-collapse:collapse;">
              <tr>
                <td style="width:50%;padding-bottom:8px;"><div class="info-label">Referring Facility</div><div class="info-value">' . $fromFacility . '</div></td>
                <td style="width:50%;padding-bottom:8px;"><div class="info-label">Receiving Facility</div><div class="info-value">' . $toFacility . '</div></td>
              </tr>
              <tr>
                <td><div class="info-label">Referring Doctor</div><div class="info-value">' . $doctorName . '</div></td>
                <td><div class="info-label">Appointment Date</div><div class="info-value">' . ($referral->appointment_date ? \Carbon\Carbon::parse($referral->appointment_date)->format('d M Y') : 'To be scheduled') . '</div></td>
              </tr>
            </table>
          </div>
          <div class="section">
            <div class="section-title">Clinical Information</div>
            <div class="clinical-box"><div class="clinical-label">Reason for Referral</div><div class="clinical-value">' . htmlspecialchars($reason) . '</div></div>
            <div class="clinical-box"><div class="clinical-label">Clinical Summary</div><div class="clinical-value">' . htmlspecialchars($clinicalSummary) . '</div></div>
          </div>
          <div class="consent-box">
            <div style="font-size:10px;font-weight:700;color:#16a34a;margin-bottom:3px;">PATIENT CONSENT STATUS</div>
            <div style="font-size:11px;color:#166534;">' . $consentStatus . '</div>
          </div>
          <div class="signature-area">
            <div class="sig-block"><div class="sig-line"></div><div class="sig-label">Referring Doctor: ' . $doctorName . '</div><div class="sig-label">' . $fromFacility . '</div></div>
            <div class="sig-block"><div class="sig-line"></div><div class="sig-label">Receiving Doctor / Stamp</div><div class="sig-label">' . $toFacility . '</div></div>
          </div>
          <div class="disclaimer">CONFIDENTIAL: This referral letter contains private medical information. It is intended solely for the receiving healthcare provider named above. Please present this letter upon arrival at ' . $toFacility . '.</div>
        </div>
        <div class="footer">
          <div class="footer-text">AfyaLink · Digital Health Platform · Kenya</div>
          <div class="footer-text">Generated: ' . now()->format('d M Y, H:i A') . ' · ' . $refNumber . '</div>
        </div>
        </body></html>';

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return response()->make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="referral-letter-' . $refNumber . '.pdf"',
        ]);
    }
}
