<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DoctorAuthController extends Controller
{
    public function showLogin()
    {
        return view('doctor.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Only allow doctors to login through this portal
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->role !== 'doctor') {
            throw ValidationException::withMessages([
                'email' => __('Only doctors can login through this portal.'),
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('doctor.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    public function showApply()
    {
        return view('doctor.apply');
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users'],
            'phone'      => ['required', 'string'],
            'license_number' => ['required', 'string'],
            'specialization' => ['required', 'string'],
            'facility_name' => ['required', 'string'],
            'password'   => ['required', 'min:8', 'confirmed'],
        ]);

        // Create doctor account (inactive until approved)
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'doctor',
            'license_number' => $data['license_number'],
            'specialization' => $data['specialization'],
            'is_active'  => false, // Requires admin approval
        ]);

        // Note: In a real app, you'd also create a Facility record and link the doctor

        return redirect()->route('doctor.login')
            ->with('success', 'Your application has been submitted. Please wait for admin approval.');
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Get facility ID
        $facilityId = $user->facility_id;

        $stats = [
            'patients_today' => \App\Models\Patient::where('facility_id', $facilityId)
                ->whereDate('created_at', today())->count(),
            'total_patients' => \App\Models\Patient::where('facility_id', $facilityId)->count(),
            'pending_referrals' => \App\Models\Referral::where('receiving_facility_id', $facilityId)
                ->where('status', 'pending')->count(),
            'total_records' => \App\Models\MedicalRecord::where('doctor_id', $user->id)->count(),
        ];

        // Get recent patients at this facility
        $recentPatients = \App\Models\Patient::where('facility_id', $facilityId)
            ->latest()
            ->take(10)
            ->get();

        // Get recent medical records created by this doctor
        $recentRecords = \App\Models\MedicalRecord::with(['patient', 'facility'])
            ->where('doctor_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Get nearby hospitals (within 50km) using Haversine formula
        $currentFacility = \App\Models\Facility::find($facilityId);
        $nearbyHospitals = [];

        if ($currentFacility && $currentFacility->latitude && $currentFacility->longitude) {
            $facilities = \App\Models\Facility::where('is_active', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('id', '!=', $facilityId)
                ->get();

            $nearbyHospitals = $facilities->map(function ($facility) use ($currentFacility) {
                $distance = $this->calculateDistance(
                    $currentFacility->latitude,
                    $currentFacility->longitude,
                    $facility->latitude,
                    $facility->longitude
                );

                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'type' => $facility->type,
                    'phone' => $facility->phone,
                    'email' => $facility->email,
                    'county' => $facility->county,
                    'sub_county' => $facility->sub_county,
                    'ward' => $facility->ward,
                    'latitude' => $facility->latitude,
                    'longitude' => $facility->longitude,
                    'working_hours' => $facility->working_hours,
                    'distance' => round($distance, 1),
                ];
            })->filter(function ($facility) {
                return $facility['distance'] <= 50; // Within 50km
            })->sortBy('distance')->take(10)->values();
        }

        $patients = \App\Models\User::where('role', 'patient')
            ->orderBy('first_name')->get();
        $facilities = \App\Models\Facility::where('is_active', true)
            ->orderBy('name')->get();
        $nearbyHospitals = \App\Models\Facility::where('is_active', true)
            ->take(6)->get();

        return view('doctor.dashboard', compact('stats', 'user', 'recentPatients', 'recentRecords', 'facilityId', 'nearbyHospitals', 'patients', 'facilities'));
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Handle doctor logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('doctor.login');
    }
}
