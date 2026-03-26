<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Referral;
use App\Models\MedicalRecord;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRole = $user->role;

        return match($userRole) {
            'patient' => redirect()->route('patient.dashboard'),
            'doctor', 'nurse' => redirect()->route('doctor.dashboard'),
            'hospital', 'facility' => redirect()->route('hospital.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => view('dashboard', compact('userRole', 'user')),
        };
    }

    /**
     * Patient Dashboard - Shows patient's own data
     */
    public function patientDashboard()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'patient') {
            return redirect()->route('login');
        }

        $patientRecords = \App\Models\MedicalRecord::where('patient_id', $user->id)
            ->latest()->take(3)->get();

        $facilitiesWithDistance = \App\Models\Facility::take(5)->get()
            ->map(function($f) {
                return ['name' => $f->name, 'distance' => rand(1,15)];
            });

        $stats = [
            'my_records'      => \App\Models\MedicalRecord::where('patient_id', $user->id)->count(),
            'my_referrals'    => \App\Models\Referral::where('patient_id', $user->id)->count(),
            'total_records'   => \App\Models\MedicalRecord::where('patient_id', $user->id)->count(),
            'total_facilities' => \App\Models\Facility::where('is_active', true)->count(),
            'total_doctors'   => \App\Models\User::where('role', 'doctor')->count(),
        ];

        $pending_referrals = 0;

        $active_referrals = 0;

        return view('patient.dashboard', compact(
            'patientRecords',
            'facilitiesWithDistance',
            'stats',
            'pending_referrals',
            'active_referrals'
        ));
    }

    /**
     * Calculate distance between two points using Haversine formula
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    /**
     * Doctor Dashboard - Shows doctor's own data
     */
    private function doctorDashboard($user, $userRole)
    {
        $facilityId = $user->facility_id;

        $stats = [
            'patients_treated' => 0,
            'referrals_created' => 0,
            'total_records' => 0,
            'total_patients' => Patient::count(),
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_hospitals' => Facility::count(),
        ];

        // Get doctor's stats
        if ($facilityId) {
            $stats['patients_treated'] = Patient::where('facility_id', $facilityId)->count();
            $stats['referrals_created'] = Referral::where('referring_facility_id', $facilityId)->count();
            $stats['total_records'] = MedicalRecord::where('doctor_id', $user->id)->count();
        }

        // Get doctor's recent medical records
        $recentRecords = MedicalRecord::with(['patient', 'facility', 'doctor'])
            ->where('doctor_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Get recent patients
        $recentPatients = Patient::where('facility_id', $facilityId)
            ->latest()
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact(
            'stats',
            'recentRecords',
            'recentPatients',
            'userRole'
        ));
    }

    /**
     * Hospital Dashboard - Shows hospital's own data
     */
    private function hospitalDashboard($user, $userRole)
    {
        $facilityId = $user->facility_id;

        $stats = [
            'incoming_referrals' => 0,
            'accepted_referrals' => 0,
            'completed_referrals' => 0,
            'total_patients' => 0,
            'total_doctors' => 0,
            'total_hospitals' => Facility::count(),
        ];

        // Get hospital's stats
        if ($facilityId) {
            $stats['incoming_referrals'] = Referral::where('receiving_facility_id', $facilityId)
                ->where('status', 'pending')
                ->count();
            $stats['accepted_referrals'] = Referral::where('receiving_facility_id', $facilityId)
                ->where('status', 'accepted')
                ->count();
            $stats['completed_referrals'] = Referral::where('receiving_facility_id', $facilityId)
                ->where('status', 'completed')
                ->count();
            $stats['total_patients'] = Patient::where('facility_id', $facilityId)->count();
            $stats['total_doctors'] = User::where('facility_id', $facilityId)
                ->where('role', 'doctor')
                ->count();
        }

        // Get recent referrals
        $recentReferrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
            ->where(function($query) use ($facilityId) {
                $query->where('receiving_facility_id', $facilityId)
                    ->orWhere('referring_facility_id', $facilityId);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('hospital.dashboard', compact(
            'stats',
            'recentReferrals',
            'userRole'
        ));
    }

    /**
     * Admin Dashboard - Shows system-wide data
     */
    private function adminDashboard($user, $userRole)
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_hospitals' => Facility::count(),
            'pending_doctor_applications' => User::where('role', 'doctor')
                ->where('is_active', false)
                ->count(),
            'pending_doctors' => User::where('role', 'doctor')
                ->where('is_active', false)
                ->count(),
            'total_referrals' => Referral::count(),
            'pending_referrals' => Referral::where('status', 'pending')->count(),
            'completed_referrals' => Referral::where('status', 'completed')->count(),
            'total_staff' => User::whereIn('role', ['doctor', 'nurse', 'receptionist'])->count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        $recent_patients = Patient::latest()->take(5)->get();
        $recent_referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
            ->latest()->take(5)->get();
        $recent_records = MedicalRecord::with('patient')
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_patients',
            'recent_referrals',
            'recent_records',
            'userRole'
        ));
    }

    /**
     * Generic Dashboard - For unauthenticated or unknown roles
     */
    private function genericDashboard()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_hospitals' => Facility::count(),
            'total_referrals' => Referral::count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
