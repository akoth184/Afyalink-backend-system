<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Referral;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        // Require authentication
        $this->middleware('auth');
    }

    /**
     * Show patient referrals - for patients to view their own referrals
     * This is called when a patient visits /patient/referrals
     */
    public function myReferrals()
    {
        $user = Auth::user();

        // Only patients can access this page
        if ($user->role !== 'patient') {
            abort(403, 'This page is only for patients.');
        }

        // Find patient record associated with this user
        $patient = Patient::where('email', $user->email)->first();

        if (!$patient) {
            return view('patient.referrals', ['referrals' => collect()]);
        }

        // Get referrals for this patient
        $referrals = Referral::with(['patient', 'fromFacility', 'toFacility', 'referredBy'])
            ->where('patient_id', $patient->id)
            ->latest()
            ->get();

        return view('patient.referrals', compact('referrals'));
    }

    /**
     * Show patient medical records - for patients to view their own records
     * This is called when a patient visits /patient/records
     */
    public function myRecords()
    {
        $user = Auth::user();

        // Only patients can access this page
        if ($user->role !== 'patient') {
            abort(403, 'This page is only for patients.');
        }

        // Find patient record associated with this user
        $patient = Patient::where('email', $user->email)->first();

        if (!$patient) {
            return view('patient.records', ['records' => collect()]);
        }

        // Get medical records for this patient
        $records = MedicalRecord::with(['patient', 'facility', 'doctor'])
            ->where('patient_id', $patient->id)
            ->latest()
            ->get();

        return view('patient.records', compact('records'));
    }

    public function index()
    {
        $patients = Patient::with('facility')
            ->latest()
            ->paginate(15);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'nullable|email|unique:patients,email',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female,other',
            'address'       => 'nullable|string',
            'national_id'   => 'nullable|string|max:20',
            'blood_group'   => 'nullable|string|max:5',
            'facility_id'   => 'nullable|exists:facilities,id',
            'notes'         => 'nullable|string',
        ]);

        // Add registered_by automatically
        $data['registered_by'] = Auth::id();

        $patient = Patient::create($data);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Patient registered successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['facility', 'records', 'referrals']);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'nullable|email|unique:patients,email,' . $patient->id,
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female,other',
            'address'       => 'nullable|string',
            'national_id'   => 'nullable|string|max:20',
            'blood_group'   => 'nullable|string|max:5',
            'facility_id'   => 'nullable|exists:facilities,id',
            'notes'         => 'nullable|string',
        ]);

        $patient->update($data);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient deleted.');
    }

    public function records(Patient $patient)
    {
        $records = $patient->records()
            ->latest()
            ->get();

        return view('patients.records', compact('patient', 'records'));
    }

    /**
     * Search patients - API endpoint for doctor dashboard
     * Retrieves patient by ID or name and includes medical records
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Check if searching by exact patient ID
        $exactIdMatch = null;
        if (is_numeric($query) || str_starts_with($query, 'PAT-') || str_starts_with($query, 'AFL-')) {
            $exactIdMatch = Patient::where('patient_id', $query)
                ->orWhere('patient_number', $query)
                ->first();
        }

        // If exact match found, return it with full details
        if ($exactIdMatch) {
            $exactIdMatch->load('medicalRecords');
            return response()->json([$exactIdMatch]);
        }

        // Search by name, patient number, phone, or email
        $patients = Patient::where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('patient_number', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->withCount('medicalRecords')
            ->limit(20)
            ->get(['id', 'patient_id', 'patient_number', 'first_name', 'last_name', 'phone', 'email', 'date_of_birth', 'gender', 'blood_group']);

        return response()->json($patients);
    }
}
