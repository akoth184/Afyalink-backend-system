<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->to($this->redirectBasedOnRole(Auth::user()->role));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Clear any existing session first
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            return redirect()->to($this->redirectBasedOnRole($user->role));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Redirect users to appropriate dashboard based on role
     */
    private function redirectBasedOnRole($role)
    {
        return match($role) {
            'admin' => route('admin.dashboard'),
            'doctor', 'nurse' => route('doctor.dashboard'),
            'hospital', 'facility' => route('hospital.dashboard'),
            'patient' => route('patient.dashboard'),
            default => route('dashboard'),
        };
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->to($this->redirectBasedOnRole(Auth::user()->role));
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Check if this is patient registration (from homepage)
        $isPatientRegistration = $request->query('role') === 'patient';

        // Build validation rules based on registration type
        $roleRules = $isPatientRegistration
            ? ['sometimes', 'in:patient']  // Patients auto-assigned, no role selection needed
            : ['sometimes', 'in:admin,doctor,nurse,receptionist']; // Other roles via admin

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users'],
            'password'   => ['required', 'min:8', 'confirmed'],
            'role'       => $roleRules,
        ]);

        // Auto-assign patient role for patient registration, otherwise use default or provided
        $role = $isPatientRegistration ? 'patient' : ($data['role'] ?? 'doctor');

        // Generate unique patient ID if registering as patient
        $patientId = null;
        if ($role === 'patient') {
            $latest = User::where('role', 'patient')->latest('id')->first();
            $nextNumber = $latest ? (int)str_replace('PAT-', '', $latest->patient_id) + 1 : 1;
            $patientId = 'PAT-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        }

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'role'       => $role,
            'patient_id' => $patientId,
        ]);

        Auth::login($user);

        return redirect()->to($this->redirectBasedOnRole($user->role));
    }

    /**
     * Show user profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('patient.profile', compact('user'));
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id . ',id'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->fill($validated);
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show settings page (password change)
     */
    public function settings()
    {
        return view('patient.settings');
    }

    /**
     * Update password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
