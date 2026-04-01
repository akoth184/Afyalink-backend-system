<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Referral;
use App\Models\Facility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLogin()
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and is an admin
        if (!$user || $user->role !== 'admin') {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        // Attempt to login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        $stats = [
            'total_patients' => \App\Models\User::where('role', 'patient')->count(),
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_facilities' => Facility::where('is_active', true)->count(),
            'total_referrals' => Referral::count(),
            'pending_referrals' => Referral::where('status', 'pending')->count(),
            'completed_referrals' => Referral::where('status', 'completed')->count(),
            'total_staff' => User::whereIn('role', ['doctor', 'nurse', 'receptionist'])->count(),
            'active_users' => User::where('is_active', true)->count(),
            'pending_doctor_applications' => User::where('role', 'doctor')
                ->where('is_active', false)
                ->count(),
        ];

        // Get pending doctor applications
        $pendingDoctorApplications = User::where('role', 'doctor')
            ->where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get pending facilities
        $pendingFacilities = \App\Models\Facility::where('is_active', false)->get();

        $recent_patients = Patient::latest()->take(5)->get();
        $recent_referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'user',
            'recent_patients',
            'recent_referrals',
            'pendingDoctorApplications',
            'pendingFacilities'
        ));
    }

    /**
     * Approve a doctor application
     * Update users.is_active = true
     */
    public function approveDoctor(Request $request, $id)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $doctor = User::where('role', 'doctor')
            ->where('id', $id)
            ->first();

        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor not found.');
        }

        $doctor->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Doctor application approved successfully.');
    }

    /**
     * Show pending doctor applications
     */
    public function pendingDoctors()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $pendingDoctors = User::where('role', 'doctor')
            ->where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pending-doctors', compact('pendingDoctors'));
    }

    /**
     * Show pending facility applications
     */
    public function pendingFacilities()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login');
        }

        $pendingFacilities = Facility::where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pending-facilities', compact('pendingFacilities'));
    }

    /**
     * Reject a doctor application
     * Update users.is_active = false
     */
    public function rejectDoctor(Request $request, $id)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $doctor = User::where('role', 'doctor')
            ->where('id', $id)
            ->first();

        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor not found.');
        }

        $doctor->update(['is_active' => false]);

        return redirect()->back()->with('success', 'Doctor application rejected.');
    }

    /**
     * Approve a hospital/facility application
     */
    public function approveFacility(Request $request, $id)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $facility = Facility::find($id);

        if (!$facility) {
            return redirect()->back()->with('error', 'Facility not found.');
        }

        $facility->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Facility approved successfully.');
    }

    /**
     * Reject a hospital/facility application
     */
    public function rejectFacility(Request $request, $id)
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $facility = Facility::find($id);

        if (!$facility) {
            return redirect()->back()->with('error', 'Facility not found.');
        }

        $facility->update(['is_active' => false]);

        return redirect()->back()->with('success', 'Facility rejected.');
    }

    /**
     * Export report view
     */
    public function exportReport()
    {
        $data = [
            'total_patients' => \App\Models\User::where('role','patient')->count(),
            'total_doctors' => \App\Models\User::where('role','doctor')->count(),
            'total_facilities' => \App\Models\Facility::where('is_active',true)->count(),
            'total_referrals' => \App\Models\Referral::count(),
            'accepted_referrals' => \App\Models\Referral::where('status','accepted')->count(),
            'pending_referrals' => \App\Models\Referral::where('status','pending')->count(),
            'rejected_referrals' => \App\Models\Referral::where('status','rejected')->count(),
            'referrals' => \App\Models\Referral::with(['patient','referringFacility','receivingFacility'])->latest()->get(),
            'generated_at' => now()->format('d M Y, H:i A'),
        ];
        return view('admin.report-pdf', $data);
    }
}
