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
        if ($user->role === 'patient') {
            $records = \App\Models\MedicalRecord::where('patient_id', $user->id)
                ->latest()->paginate(10);
        } elseif ($user->role === 'doctor' || $user->role === 'nurse') {
            $records = \App\Models\MedicalRecord::where('doctor_id', $user->id)
                ->latest()->paginate(10);
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
            $patient = Patient::where('email', $user->email)->first();

            if (!$patient || $record->patient_id !== $patient->id) {
                abort(403, 'You are not authorized to view this medical record.');
            }
        }

        // Doctors can only view records from their facility
        if ($user->role === 'doctor' && $user->facility_id) {
            if ($record->facility_id !== $user->facility_id) {
                abort(403, 'You are not authorized to view this medical record.');
            }
        }

        // Hospital/facility can only view records for their facility
        if (in_array($user->role, ['hospital', 'facility']) && $user->facility_id) {
            if ($record->facility_id !== $user->facility_id) {
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

        $patients = Patient::orderBy('first_name')->get();
        $facilities = \App\Models\Facility::orderBy('name')->get();
        return view('records.create', compact('patients', 'facilities'));
    }

    /**
     * Store new medical record - Only doctors can create
     */
    public function store(Request $request)
    {
        // Check if user can create medical records
        if (!Gate::allows('create-medical-records')) {
            abort(403, 'Only doctors can create medical records.');
        }

        $validator = Validator::make($request->all(), [
            'patient_id'                  => 'required|exists:patients,id',
            'facility_id'                 => 'required|exists:facilities,id',
            'doctor_id'                   => 'required|exists:users,id',
            'visit_date'                  => 'required|date',
            'chief_complaint'             => 'required|string',
            'history_of_present_illness'  => 'nullable|string',
            'vital_signs'                 => 'nullable|array',
            'examination_findings'        => 'nullable|string',
            'diagnosis'                   => 'nullable|string',
            'treatment_plan'              => 'nullable|string',
            'medications'                 => 'nullable|string',
            'lab_results'                 => 'nullable|array',
            'notes'                      => 'nullable|string',
            'status'                     => 'nullable|string',
            'file'                       => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
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

        $recordData = $request->all();

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/medical_records', $filename);
            $recordData['file_path'] = 'medical_records/' . $filename;
        }

        $record = MedicalRecord::create($recordData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Medical record created successfully.',
                'data'    => $record->load(['patient', 'facility', 'doctor']),
            ], 201);
        }

        return redirect()->route('records.show', $record)
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

        $patients = Patient::orderBy('first_name')->get();

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
            'patient_id'                  => 'sometimes|exists:patients,id',
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
            $patient = Patient::where('email', $user->email)->first();

            if (!$patient || $record->patient_id !== $patient->id) {
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

        // Hospital/facility can only download records for their facility
        if (in_array($user->role, ['hospital', 'facility']) && $user->facility_id) {
            if ($record->facility_id !== $user->facility_id) {
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

        // Generate HTML for PDF
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Medical Record - {$record->id}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0d6e6e; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #0d6e6e; }
        .title { font-size: 18px; margin-top: 10px; }
        .record-info { margin: 20px 0; }
        .info-row { display: flex; margin-bottom: 10px; }
        .label { font-weight: bold; width: 180px; color: #666; }
        .value { flex: 1; }
        .section { margin-top: 25px; }
        .section-title { font-size: 14px; font-weight: bold; color: #0d6e6e; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        .content { line-height: 1.6; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AfyaLink</div>
        <div class="title">Medical Record</div>
    </div>

    <div class="record-info">
        <div class="info-row">
            <span class="label">Patient Name:</span>
            <span class="value">{$patientName}</span>
        </div>
        <div class="info-row">
            <span class="label">Patient ID:</span>
            <span class="value">{$patientId}</span>
        </div>
        <div class="info-row">
            <span class="label">Facility:</span>
            <span class="value">{$facilityName}</span>
        </div>
        <div class="info-row">
            <span class="label">Doctor:</span>
            <span class="value">{$doctorName}</span>
        </div>
        <div class="info-row">
            <span class="label">Date:</span>
            <span class="value">{$record->visit_date}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Chief Complaint</div>
        <div class="content">{$record->chief_complaint}</div>
    </div>

    <div class="section">
        <div class="section-title">History of Present Illness</div>
        <div class="content">{$record->history_of_present_illness}</div>
    </div>

    <div class="section">
        <div class="section-title">Examination Findings</div>
        <div class="content">{$record->examination_findings}</div>
    </div>

    <div class="section">
        <div class="section-title">Diagnosis</div>
        <div class="content">{$record->diagnosis}</div>
    </div>

    <div class="section">
        <div class="section-title">Treatment Plan</div>
        <div class="content">{$record->treatment_plan}</div>
    </div>

    <div class="section">
        <div class="section-title">Medications</div>
        <div class="content">{$record->medications}</div>
    </div>

    <div class="section">
        <div class="section-title">Notes</div>
        <div class="content">{$record->notes}</div>
    </div>

    <div class="footer">
        <p>This is an official medical record from AfyaLink Healthcare System.</p>
        <p>Generated on: {$record->updated_at}</p>
    </div>
</body>
</html>
HTML;

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
