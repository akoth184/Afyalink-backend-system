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

        // Get user role
        $userRole = $user ? $user->role : null;

        // Route to appropriate role-specific dashboard
        switch ($userRole) {
            case 'patient':
                return $this->patientDashboard($user, $userRole);
            case 'doctor':
                return $this->doctorDashboard($user, $userRole);
            case 'hospital':
            case 'facility':
                return $this->hospitalDashboard($user, $userRole);
            case 'admin':
                return $this->adminDashboard($user, $userRole);
            default:
                return $this->genericDashboard();
        }
    }

    /**
     * Patient Dashboard - Shows patient's own data
     */
    private function patientDashboard($user, $userRole)
    {
        // Find patient record associated with this user
        $patient = Patient::where('email', $user->email)->first();

        $stats = [
            'my_records' => 0,
            'my_referrals' => 0,
            'total_patients' => Patient::count(),
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_hospitals' => Facility::count(),
        ];

        $patientRecords = collect();
        $patientReferrals = collect();

        // Get nearby hospitals (default Nairobi coordinates)
        $userLat = -1.2921;
        $userLng = 36.8219;

        $facilities = Facility::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $facilitiesWithDistance = $facilities->map(function ($facility) use ($userLat, $userLng) {
            $distance = $this->calculateDistance(
                $userLat,
                $userLng,
                $facility->latitude,
                $facility->longitude
            );
            return [
                'id' => $facility->id,
                'name' => $facility->name,
                'type' => $facility->type,
                'phone' => $facility->phone,
                'working_hours' => $facility->working_hours,
                'distance' => round($distance, 2),
            ];
        })->sortBy('distance')->take(4)->values();

        if ($patient) {
            $stats['my_records'] = MedicalRecord::where('patient_id', $patient->id)->count();
            $stats['my_referrals'] = Referral::where('patient_id', $patient->id)->count();

            // Get patient's recent records
            $patientRecords = MedicalRecord::with(['patient', 'facility', 'doctor'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->take(5)
                ->get();

            // Get patient's recent referrals
            $patientReferrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
                ->where('patient_id', $patient->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('patient.dashboard', compact(
            'stats',
            'patientRecords',
            'patientReferrals',
            'facilitiesWithDistance',
            'userRole'
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

        return view('dashboard', compact(
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

        return view('dashboard', compact(
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

        return view('dashboard', compact(
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
