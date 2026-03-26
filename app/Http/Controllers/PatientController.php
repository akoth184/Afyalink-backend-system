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
        $referrals = \App\Models\Referral::where('patient_id', $user->id)
            ->with(['referringFacility', 'receivingFacility'])
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

        // Get medical records for this patient (now stored in users table)
        $records = \App\Models\MedicalRecord::where('patient_id', $user->id)
            ->with('doctor')
            ->latest()
            ->get();

        return view('patient.records', compact('records'));
    }

    public function index(Request $request)
    {
        $query = $request->get('search');
        $patients = \App\Models\User::where('role', 'patient')
            ->when($query, function($q) use ($query) {
                $q->where(function($q2) use ($query) {
                    $q2->where('first_name', 'like', "%{$query}%")
                       ->orWhere('last_name', 'like', "%{$query}%")
                       ->orWhere('email', 'like', "%{$query}%")
                       ->orWhere('patient_id', 'like', "%{$query}%");
                });
            })
            ->orderBy('first_name')
            ->paginate(10);
        return view('patients.index', compact('patients', 'query'));
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

    public function show($id)
    {
        $patient = \App\Models\User::where('role', 'patient')
            ->findOrFail($id);
        $records = \App\Models\MedicalRecord::where('patient_id', $id)
            ->latest()->get();
        $referrals = \App\Models\Referral::where('patient_id', $id)
            ->latest()->get();
        return view('patients.show', compact('patient', 'records', 'referrals'));
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
        if (!request()->wantsJson() && !request()->ajax()) {
            return response()->json([]);
        }
        $query = $request->get('query', '');
        $patients = \App\Models\User::where('role', 'patient')
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('patient_id', 'like', "%{$query}%");
            })
            ->take(10)
            ->get(['id','first_name','last_name','email','patient_id']);
        return response()->json($patients);
    }
}
