<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
                             ->latest()->paginate(15);
        return view('referrals.index', compact('referrals'));
    }

    public function create()
    {
        $patients   = Patient::orderBy('first_name')->get();
        $facilities = Facility::orderBy('name')->get();
        return view('referrals.create', compact('patients', 'facilities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'            => 'required|exists:patients,id',
            'referring_facility_id' => 'required|exists:facilities,id',
            'receiving_facility_id' => 'required|exists:facilities,id|different:referring_facility_id',
            'reason'                => 'required|string',
            'notes'                 => 'nullable|string',
        ]);

        $data['status']      = 'pending';
        $data['referred_by'] = auth()->id();

        $referral = Referral::create($data);

        return redirect()->route('referrals.show', $referral)
                         ->with('success', 'Referral created successfully.');
    }

    public function show(Referral $referral)
    {
        $referral->load(['patient', 'fromFacility', 'toFacility', 'referredBy']);
        return view('referrals.show', compact('referral'));
    }

    public function edit(Referral $referral)
    {
        $patients   = Patient::orderBy('first_name')->get();
        $facilities = Facility::orderBy('name')->get();
        return view('referrals.edit', compact('referral', 'patients', 'facilities'));
    }

    public function update(Request $request, Referral $referral)
    {
        $data = $request->validate([
            'patient_id'            => 'required|exists:patients,id',
            'referring_facility_id' => 'required|exists:facilities,id',
            'receiving_facility_id' => 'required|exists:facilities,id',
            'reason'                => 'required|string',
            'notes'                 => 'nullable|string',
            'status'                => 'required|in:pending,accepted,rejected,completed',
        ]);

        $referral->update($data);

        return redirect()->route('referrals.show', $referral)
                         ->with('success', 'Referral updated successfully.');
    }

    public function destroy(Referral $referral)
    {
        $referral->delete();
        return redirect()->route('referrals.index')
                         ->with('success', 'Referral deleted.');
    }

    public function updateStatus(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,completed',
        ]);
        $referral->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Referral status updated.');
    }
}