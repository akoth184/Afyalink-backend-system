<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
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

        // 🔥 FIX: Add registered_by automatically
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
}