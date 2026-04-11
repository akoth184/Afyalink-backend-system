<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class MedicalRecordController extends Controller
{
    public function __construct()
    {
        // Require authentication for all routes
        $this->middleware('auth');
    }

    /**
     * List medical records - filtered by user role
     * - Patients: Only see their own records
     * - Doctors: See records from their facility
     * - Admins: See all records
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $request->get('search');
        $patientFilter = $request->get('patient');
        
        if ($user->role === 'patient') {
            $records = \App\Models\MedicalRecord::where('patient_id', $user->id)
                ->latest()->paginate(10);
        } elseif ($user->role === 'doctor' || $user->role === 'nurse') {
            $queryBuilder = \App\Models\MedicalRecord::query();
            
            // If filtering by specific patient
            if ($patientFilter) {
                $queryBuilder->where('patient_id', $patientFilter);
            } else {
                $queryBuilder->where('doctor_id', $user->id);
            }
            
            $records = $queryBuilder->latest()->paginate(10);
        } else {
            $records = \App\Models\MedicalRecord::latest()->paginate(10);
        }
        return view('records.index', compact('records'));
    }

    /**
     * Show medical record details - filtered by user role
     * Patients can only view their own records
     */
    public function show($id)
    {
        // Try to find by ID or slug
        $record = MedicalRecord::with(['patient', 'facility', 'doctor'])->find($id);

        if (!$record) {
            abort(404, 'Medical record not found.');
        }

        $user = Auth::user();

        // Patients can only view their own records
        if ($user->role === 'patient') {
            if ($record->patient_id !== $user->id) {
                abort(403, 'You are not authorized to view this medical record.');
            }
        }

        // Doctors can only view records from their facility
        if ($user->role === 'doctor' && $user->facility_id) {
            if ($record->facility_id !== $user->facility_id) {
                abort(403, 'You are not authorized to view this medical record.');
            }
        }

        // Hospital/facility can view records for their facility or accepted referred patients
        if (in_array($user->role, ['hospital', 'facility'])) {
            $facilityId = $user->facility_id;
            $facility = \App\Models\Facility::where('id', $facilityId)
                ->orWhere('hospital_id', $user->hospital_id)
                ->first();
            $facilityId = optional($facility)->id;
            $acceptedPatientIds = \App\Models\Referral::where('receiving_facility_id', $facilityId)
                ->where('status', 'accepted')
                ->pluck('patient_id')
                ->toArray();
            if ($record->facility_id !== $facilityId && !in_array($record->patient_id, $acceptedPatientIds)) {
                abort(403, 'You are not authorized to view this medical record.');
            }
        }

        // Return JSON for API requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $record,
            ], 200);
        }

        // Return view for web requests
        return view('records.show', compact('record'));
    }

    /**
     * Create medical record form - Only doctors can access
     */
    public function create()
    {
        // Check if user can create medical records
        if (!Gate::allows('create-medical-records')) {
            abort(403, 'Only doctors can create medical records.');
        }

        $patients = \App\Models\User::where('role', 'patient')->orderBy('first_name')->get();
        $facilities = \App\Models\Facility::orderBy('name')->get();
        
        $prefillPatient = null;
        
        // Handle lab_test_id parameter
        if (request()->has('lab_test_id')) {
            $labTest = \App\Models\LabTest::find(request('lab_test_id'));
            if ($labTest) {
                $prefillPatient = $labTest->patient_id;
            }
        }
        
        // Handle patient_id parameter (from appointment)
        if (request()->has('patient_id') && !$prefillPatient) {
            $prefillPatient = request('patient_id');
        }
        
        return view('records.create', compact('patients', 'facilities', 'prefillPatient'));
    }

    /**
     * Store new medical record - Only doctors can create
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'patient_id'    => 'required|exists:users,id',
            'visit_date'    => 'required|date',
            'chief_complaint' => 'nullable|string',
            'diagnosis'     => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'medications'   => 'nullable|string',
            'notes'         => 'nullable|string',
            'file'          => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $data = [
            'patient_id'     => $request->patient_id,
            'doctor_id'      => $user->id,
            'facility_id'    => $request->facility_id ?? $user->facility_id ?? 1,
            'visit_date'     => $request->visit_date,
            'chief_complaint'=> $request->title ?? $request->diagnosis ?? 'General Visit',
            'diagnosis'      => $request->diagnosis,
            'treatment_plan' => $request->treatment ?? '',
            'medications'    => $request->prescription ?? '',
            'notes'          => $request->notes ?? '',
            'history_of_present_illness' => '',
            'examination_findings'       => '',
            'status'         => 'finalized',
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/medical_records', $filename);
            $data['file_path'] = 'medical_records/' . $filename;
        }

        $record = \App\Models\MedicalRecord::create($data);

        // Notify patient
        \App\Models\Notification::send(
            $record->patient_id,
            'record_created',
            'New Medical Record Added',
            'Dr. ' . $user->first_name . ' ' . $user->last_name . ' has added a medical record for your visit on ' . $request->visit_date . '. You can view and download it from your dashboard.',
            null
        );

        return redirect()->route('records.index')
            ->with('success', 'Medical record created successfully.');
    }

    /**
     * Edit medical record - Only doctors can access (their own records)
     */
    public function edit($id)
    {
        if (!Gate::allows('edit-medical-records')) {
            abort(403, 'Only doctors can edit medical records.');
        }

        $record = MedicalRecord::findOrFail($id);

        // Doctors can only edit records they created
        $user = Auth::user();
        if ($user->role === 'doctor' && $record->doctor_id !== $user->id) {
            abort(403, 'You can only edit medical records you created.');
        }

        $patients = \App\Models\User::where('role', 'patient')->orderBy('first_name')->get();

        return view('records.edit', compact('record', 'patients'));
    }

    /**
     * Update medical record - Only doctors can access (their own records)
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('edit-medical-records')) {
            abort(403, 'Only doctors can update medical records.');
        }

        $record = MedicalRecord::find($id);

        // Doctors can only update records they created
        $user = Auth::user();
        if ($user->role === 'doctor' && $record->doctor_id !== $user->id) {
            abort(403, 'You can only update medical records you created.');
        }

        if (!$record) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Medical record not found.',
                ], 404);
            }
            abort(404, 'Medical record not found.');
        }

        $validator = Validator::make($request->all(), [
            'patient_id'                  => 'sometimes|exists:users',
            'facility_id'                 => 'sometimes|exists:facilities,id',
            'doctor_id'                   => 'sometimes|exists:users,id',
            'visit_date'                  => 'sometimes|date',
            'chief_complaint'             => 'sometimes|string',
            'history_of_present_illness'  => 'nullable|string',
            'vital_signs'                 => 'nullable|array',
            'examination_findings'        => 'nullable|string',
            'diagnosis'                   => 'nullable|string',
            'treatment_plan'              => 'nullable|string',
            'medications'                 => 'nullable|string',
            'lab_results'                 => 'nullable|array',
            'notes'                       => 'nullable|string',
            'status'                      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $record->update($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Medical record updated successfully.',
                'data'    => $record->load(['patient', 'facility', 'doctor']),
            ], 200);
        }

        return redirect()->route('records.show', $record)
            ->with('success', 'Medical record updated successfully.');
    }

    /**
     * Delete medical record - Only admins can delete
     */
    public function destroy($id)
    {
        if (!Gate::allows('delete-medical-records')) {
            abort(403, 'Only admins can delete medical records.');
        }

        $record = MedicalRecord::find($id);

        if (!$record) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Medical record not found.',
                ], 404);
            }
            abort(404, 'Medical record not found.');
        }

        $record->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Medical record deleted successfully.',
            ], 200);
        }

        return redirect()->route('records.index')
            ->with('success', 'Medical record deleted successfully.');
    }

    /**
     * Download the uploaded file attached to a medical record
     */
    public function downloadFile($id)
    {
        $record = MedicalRecord::find($id);

        if (!$record) {
            abort(404, 'Medical record not found.');
        }

        $user = Auth::user();

        // Patients can only download their own records
        if ($user->role === 'patient') {
            $patient = Patient::where('email', $user->email)->first();

            if (!$patient || $record->patient_id !== $patient->id) {
                abort(403, 'You are not authorized to download this file.');
            }
        }

        // Doctors can only download records from their facility
        if ($user->role === 'doctor' && $user->facility_id) {
            if ($record->facility_id !== $user->facility_id &&
                $record->doctor_id !== $user->id) {
                abort(403, 'You are not authorized to download this file.');
            }
        }

        // Hospital/facility can only download records for their facility
        if (in_array($user->role, ['hospital', 'facility']) && $user->facility_id) {
            if ($record->facility_id !== $user->facility_id) {
                abort(403, 'You are not authorized to download this file.');
            }
        }

        // Check if file exists
        if (!$record->file_path || !Storage::exists('public/' . $record->file_path)) {
            abort(404, 'File not found.');
        }

        // Get the file
        $filePath = storage_path('app/public/' . $record->file_path);
        $fileName = basename($record->file_path);

        return response()->download($filePath, $fileName);
    }

    /**
     * Download medical record as PDF
     * Only the patient who owns the record, or doctors/admins can download
     */
    public function downloadPDF($id)
    {
        $record = MedicalRecord::with(['patient', 'facility', 'doctor'])->find($id);

        if (!$record) {
            abort(404, 'Medical record not found.');
        }

        $user = Auth::user();

        // Patients can only download their own records
        if ($user->role === 'patient') {
            if ($record->patient_id !== $user->id) {
                abort(403, 'You are not authorized to download this record.');
            }
        }

        // Doctors can only download records from their facility
        if ($user->role === 'doctor' && $user->facility_id) {
            if ($record->facility_id !== $user->facility_id &&
                $record->doctor_id !== $user->id) {
                abort(403, 'You are not authorized to download this record.');
            }
        }

        // Hospital/facility can download records for their facility or accepted referred patients
        if (in_array($user->role, ['hospital', 'facility'])) {
            $facilityId = $user->facility_id;
            $facility = \App\Models\Facility::where('id', $facilityId)
                ->orWhere('hospital_id', $user->hospital_id)
                ->first();
            $facilityId = optional($facility)->id;
            $acceptedPatientIds = \App\Models\Referral::where('receiving_facility_id', $facilityId)
                ->where('status', 'accepted')
                ->pluck('patient_id')
                ->toArray();
            if ($record->facility_id !== $facilityId && !in_array($record->patient_id, $acceptedPatientIds)) {
                abort(403, 'You are not authorized to download this record.');
            }
        }

        // Admin can download any record

        // Get patient name and ID
        $patientName = $record->patient
            ? $record->patient->first_name . ' ' . $record->patient->last_name
            : 'N/A';
        $patientId = optional($record->patient)->patient_id ?? 'N/A';

        // Get doctor name
        $doctorName = $record->doctor
            ? 'Dr. ' . $record->doctor->first_name . ' ' . $record->doctor->last_name
            : 'N/A';

        // Get facility name
        $facilityName = $record->facility ? $record->facility->name : 'N/A';

        $generatedAt = now()->format('d M Y, h:i A');
        $recordNumber = 'MR-' . str_pad($record->id, 5, '0', STR_PAD_LEFT);
        $statusColor = $record->status === 'finalized' ? '#16a34a' : '#d97706';
        $labTests = \App\Models\LabTest::where('patient_id', $record->patient_id)
            ->where('status','completed')
            ->get();
        $labRows = '';
        foreach($labTests as $lt) {
            $labRows .= '<tr>
                <td style="padding:6px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">' . htmlspecialchars($lt->test_name) . '</td>
                <td style="padding:6px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">' . ucfirst($lt->test_category) . '</td>
                <td style="padding:6px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">' . ($lt->result_notes ?? 'See attached file') . '</td>
                <td style="padding:6px 0;border-bottom:1px solid #f1f5f9;font-size:11px;">' . ($lt->result_date ? date('d M Y', strtotime($lt->result_date)) : 'N/A') . '</td>
            </tr>';
        }
        $labSection = $labTests->count() > 0 ? '
        <div class="section">
          <div class="section-header"><div class="section-dot"></div><div class="section-title">Lab Test Results</div></div>
          <table style="width:100%;border-collapse:collapse;">
            <thead><tr>
              <th style="text-align:left;padding:6px 0;font-size:9px;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Test</th>
              <th style="text-align:left;padding:6px 0;font-size:9px;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Category</th>
              <th style="text-align:left;padding:6px 0;font-size:9px;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Result</th>
              <th style="text-align:left;padding:6px 0;font-size:9px;color:#94a3b8;text-transform:uppercase;border-bottom:1px solid #e2e8f0;">Date</th>
            </tr></thead>
            <tbody>' . $labRows . '</tbody>
          </table>
        </div>' : '';
        $html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; color: #0f172a; font-size: 12px; }
.header { background: #1e3a5f; padding: 24px 36px; }
.header-inner { display: flex; justify-content: space-between; align-items: flex-start; }
.logo-text { font-size: 22px; font-weight: 900; color: white; }
.logo-sub { font-size: 10px; color: rgba(255,255,255,0.6); margin-top: 3px; }
.doc-title { font-size: 15px; font-weight: 700; color: white; text-align: right; }
.doc-meta { font-size: 10px; color: rgba(255,255,255,0.6); margin-top: 3px; text-align: right; }
.status-bar { background: #f0f6ff; padding: 10px 36px; border-bottom: 2px solid #dbeafe; }
.status-inner { display: flex; justify-content: space-between; }
.sb-label { font-size: 8px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; }
.sb-value { font-size: 11px; font-weight: 700; color: #0f172a; margin-top: 2px; }
.body { padding: 24px 36px; }
.section { margin-bottom: 18px; }
.section-header { display: flex; align-items: center; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1.5px solid #e2e8f0; }
.section-dot { width: 8px; height: 8px; border-radius: 4px; background: #2563eb; margin-right: 8px; }
.section-title { font-size: 10px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.08em; }
.info-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
.info-table td { padding: 5px 0; width: 50%; vertical-align: top; }
.info-label { font-size: 8px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 2px; }
.info-value { font-size: 12px; font-weight: 600; color: #0f172a; }
.clinical-item { background: #f8fafc; border-left: 3px solid #2563eb; padding: 10px 14px; margin-bottom: 8px; }
.clinical-label { font-size: 8px; color: #2563eb; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; margin-bottom: 4px; }
.clinical-value { font-size: 11px; color: #0f172a; line-height: 1.6; }
.disclaimer { background: #fef3c7; border-left: 3px solid #f59e0b; padding: 10px 14px; margin-top: 16px; }
.disclaimer-title { font-size: 9px; font-weight: 700; color: #d97706; margin-bottom: 3px; text-transform: uppercase; }
.disclaimer-text { font-size: 9px; color: #92400e; line-height: 1.5; }
.footer { background: #0f172a; padding: 14px 36px; margin-top: 20px; }
.footer-inner { display: flex; justify-content: space-between; align-items: center; }
.footer-logo { font-size: 14px; font-weight: 900; color: white; }
.footer-text { font-size: 8px; color: rgba(255,255,255,0.4); margin-top: 3px; }
.footer-right { text-align: right; }
</style>
</head>
<body>
<div class="header">
  <div class="header-inner">
    <div>
      <div class="logo-text">AfyaLink</div>
      <div class="logo-sub">Digital Health Record Platform &middot; Kenya</div>
    </div>
    <div>
      <div class="doc-title">Medical Record</div>
      <div class="doc-meta">Record #' . $recordNumber . '</div>
      <div class="doc-meta">Generated: ' . $generatedAt . '</div>
    </div>
  </div>
</div>
<div class="status-bar">
  <div class="status-inner">
    <div><div class="sb-label">Status</div><div class="sb-value" style="color:' . $statusColor . ';">' . ucfirst($record->status ?? "draft") . '</div></div>
    <div><div class="sb-label">Visit Date</div><div class="sb-value">' . ($record->visit_date ? date("d M Y", strtotime($record->visit_date)) : "N/A") . '</div></div>
    <div><div class="sb-label">Facility</div><div class="sb-value">' . ($facilityName) . '</div></div>
    <div><div class="sb-label">Doctor</div><div class="sb-value">' . ($doctorName) . '</div></div>
  </div>
</div>
<div class="body">
  <div class="section">
    <div class="section-header"><div class="section-dot"></div><div class="section-title">Patient Information</div></div>
    <table class="info-table">
      <tr>
        <td><div class="info-label">Full Name</div><div class="info-value">' . $patientName . '</div></td>
        <td><div class="info-label">Patient ID</div><div class="info-value">' . $patientId . '</div></td>
      </tr>
      <tr>
        <td><div class="info-label">Attending Doctor</div><div class="info-value">' . $doctorName . '</div></td>
        <td><div class="info-label">Facility</div><div class="info-value">' . $facilityName . '</div></td>
      </tr>
    </table>
  </div>
  <div class="section">
    <div class="section-header"><div class="section-dot"></div><div class="section-title">Clinical Details</div></div>
    ' . ($record->chief_complaint ? '<div class="clinical-item"><div class="clinical-label">Chief Complaint</div><div class="clinical-value">' . htmlspecialchars($record->chief_complaint) . '</div></div>' : '') . '
    ' . ($record->diagnosis ? '<div class="clinical-item"><div class="clinical-label">Diagnosis</div><div class="clinical-value">' . htmlspecialchars($record->diagnosis) . '</div></div>' : '') . '
    ' . ($record->treatment_plan ? '<div class="clinical-item"><div class="clinical-label">Treatment Plan</div><div class="clinical-value">' . htmlspecialchars($record->treatment_plan) . '</div></div>' : '') . '
    ' . ($record->medications ? '<div class="clinical-item"><div class="clinical-label">Medications</div><div class="clinical-value">' . htmlspecialchars($record->medications) . '</div></div>' : '') . '
    ' . ($record->notes ? '<div class="clinical-item"><div class="clinical-label">Notes</div><div class="clinical-value">' . htmlspecialchars($record->notes) . '</div></div>' : '') . '
  </div>
  ' . $labSection . '
  <div class="disclaimer">
    <div class="disclaimer-title">Confidential Medical Document</div>
    <div class="disclaimer-text">This document contains confidential medical information protected under the Kenya Health Act. It is intended solely for the patient named above and authorized healthcare providers. Unauthorized disclosure is prohibited.</div>
  </div>
</div>
<div class="footer">
  <div class="footer-inner">
    <div>
      <div class="footer-logo">AfyaLink</div>
      <div class="footer-text">Digital Patient Referral &amp; Health Record Platform</div>
      <div class="footer-text">support@afyalink.ke &middot; www.afyalink.ke &middot; Kenya</div>
    </div>
    <div class="footer-right">
      <div class="footer-text">Computer-generated document &middot; ' . $generatedAt . '</div>
      <div class="footer-text">&copy; 2026 AfyaLink. All rights reserved.</div>
    </div>
  </div>
</div>
</body>
</html>';

        // Generate PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Download the PDF
        $pdf = $dompdf->output();
        return response()->make($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="medical_record_' . $record->id . '.pdf"',
        ]);
    }
}
