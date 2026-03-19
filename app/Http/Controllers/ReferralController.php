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
            // Find patient record associated with this user
            $patient = Patient::where('email', $user->email)->first();

            if (!$patient) {
                return view('referrals.index', ['referrals' => collect()]);
            }

            $referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->paginate(15);

            return view('referrals.index', compact('referrals'));
        }

        // Doctors see referrals from their facility
        if ($user->role === 'doctor' && $user->facility_id) {
            $referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
                ->where(function($query) use ($user) {
                    $query->where('referring_facility_id', $user->facility_id)
                        ->orWhere('receiving_facility_id', $user->facility_id);
                })
                ->latest()
                ->paginate(15);

            return view('referrals.index', compact('referrals'));
        }

        // Hospital/facility admins see all referrals for their facility
        if (in_array($user->role, ['hospital', 'facility']) && $user->facility_id) {
            $referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
                ->where(function($query) use ($user) {
                    $query->where('referring_facility_id', $user->facility_id)
                        ->orWhere('receiving_facility_id', $user->facility_id);
                })
                ->latest()
                ->paginate(15);

            return view('referrals.index', compact('referrals'));
        }

        // Admins see all referrals
        if ($user->role === 'admin') {
            $referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
                ->latest()
                ->paginate(15);

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

        $referral->load(['patient', 'fromFacility', 'toFacility', 'referredBy']);
        return view('referrals.show', compact('referral'));
    }

    /**
     * Create referral form - Only doctors can access
     */
    public function create()
    {
        // Check if user can create referrals
        if (!Gate::allows('create-referrals')) {
            abort(403, 'Only doctors can create referrals.');
        }

        $patients   = Patient::orderBy('first_name')->get();
        $facilities = Facility::orderBy('name')->get();
        return view('referrals.create', compact('patients', 'facilities'));
    }

    /**
     * Store new referral - Only doctors can create
     */
    public function store(Request $request)
    {
        // Check if user can create referrals
        if (!Gate::allows('create-referrals')) {
            abort(403, 'Only doctors can create referrals.');
        }

        $data = $request->validate([
            'patient_id'            => 'required|exists:patients,id',
            'referring_facility_id' => 'required|exists:facilities,id',
            'receiving_facility_id' => 'required|exists:facilities,id|different:referring_facility_id',
            'reason'               => 'required|string',
            'notes'                => 'nullable|string',
        ]);

        $data['status']      = 'pending';
        $data['referred_by'] = auth()->id();

        $referral = Referral::create($data);

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
            'patient_id'            => 'required|exists:patients,id',
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
        $referral = Referral::findOrFail($id);
        $user = Auth::user();

        // Check authorization - only the receiving facility or admins can update status
        if ($user->role !== 'admin') {
            if (!in_array($user->role, ['hospital', 'facility']) ||
                $user->facility_id !== $referral->receiving_facility_id) {
                abort(403, 'You are not authorized to update this referral status.');
            }
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,completed',
        ]);

        $referral->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Referral status updated.');
    }
}
