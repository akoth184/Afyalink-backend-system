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
            $referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
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
            $referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
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

        $referral->load(['patient', 'fromFacility', 'toFacility', 'referredBy']);
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
            'appointment_date'     => 'nullable|date|after:today',
            'clinical_summary'     => 'nullable|string',
        ]);

        $data['status']      = 'pending';
        $data['referred_by'] = auth()->id();
        $data['priority'] = $request->priority ?? 'routine';
        $data['appointment_date'] = $request->appointment_date;
        $data['clinical_summary'] = $request->clinical_summary;

        $referral = Referral::create($data);

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
                    'A new referral has been sent to ' . $receivingFacility->name . ' for patient ' . Auth::user()->first_name . '.',
                    $referral->id
                );
            }
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
}
