<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class HospitalAuthController extends Controller
{
    public function showLogin()
    {
        return view('hospital.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Only allow hospitals to login through this portal
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !in_array($user->role, ['hospital', 'facility'])) {
            throw ValidationException::withMessages([
                'email' => __('Only hospital/facility accounts can login through this portal.'),
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('hospital.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    public function showRegister()
    {
        return view('hospital.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'facility_name' => ['required', 'string', 'max:255'],
            'facility_type' => ['required', 'in:hospital,clinic,health_center,dispensary'],
            'mfl_code' => ['required', 'string', 'unique:facilities'],
            'county' => ['required', 'string'],
            'sub_county' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'admin_first_name' => ['required', 'string', 'max:255'],
            'admin_last_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Create facility first
        $facility = Facility::create([
            'name' => $data['facility_name'],
            'type' => $data['facility_type'],
            'mfl_code' => $data['mfl_code'],
            'county' => $data['county'],
            'sub_county' => $data['sub_county'],
            'phone' => $data['phone'],
            'is_active' => false, // Requires admin approval
        ]);

        // Create hospital admin user
        $user = User::create([
            'first_name' => $data['admin_first_name'],
            'last_name'  => $data['admin_last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => 'hospital',
            'facility_id' => $facility->id,
            'is_active'  => false, // Requires admin approval
        ]);

        return redirect()->route('hospital.login')
            ->with('success', 'Your facility has been registered. Please wait for admin approval.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $facility = $user->facility;
        if (!$facility) {
            return redirect('/hospital/login')->with('error', 'No facility linked to your account. Please contact admin.');
        }

        $stats = [
            'total_patients' => \App\Models\Referral::where('receiving_facility_id', $facility->id)->distinct('patient_id')->count(),
            'pending_referrals' => \App\Models\Referral::where('receiving_facility_id', $facility->id)->where('status', 'pending')->count(),
            'accepted_referrals' => \App\Models\Referral::where('receiving_facility_id', $facility->id)->where('status', 'accepted')->count(),
            'staff_count' => \App\Models\User::where('facility_id', $facility->id)->count(),
        ];

        $referrals = \App\Models\Referral::where('receiving_facility_id', $facility->id)
            ->with(['patient', 'referringFacility', 'receivingFacility'])
            ->latest()
            ->get();

        return view('hospital.dashboard', compact('stats', 'user', 'facility', 'referrals'));
    }

    /**
     * Handle hospital logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('hospital.login');
    }
}
