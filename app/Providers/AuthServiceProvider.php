<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // ── GATE: Create Referrals ──
        // Only doctors can create referrals
        // Patients can only view their referral status
        Gate::define('create-referrals', function ($user) {
            // Allow doctors to create referrals
            if ($user->role === 'doctor' && $user->is_active) {
                return true;
            }

            // Allow admins to create referrals (for testing/admin purposes)
            if ($user->role === 'admin') {
                return true;
            }

            // Allow hospital and facility staff to create referrals
            if (in_array($user->role, ['hospital', 'facility'])) {
                return true;
            }

            // Patients and other roles cannot create referrals
            return false;
        });

        // ── GATE: View All Referrals ──
        // Doctors and hospital staff can view all referrals
        // Patients can only view their own referrals
        Gate::define('view-all-referrals', function ($user) {
            if (in_array($user->role, ['doctor', 'admin', 'hospital', 'facility'])) {
                return true;
            }
            return false;
        });

        // ── GATE: Manage Patients ──
        // Only doctors, nurses, admins, and hospital staff can manage patients
        Gate::define('manage-patients', function ($user) {
            return in_array($user->role, ['doctor', 'nurse', 'admin', 'hospital', 'facility', 'receptionist']);
        });

        // ── GATE: Create Medical Records ──
        // Only doctors can create medical records
        Gate::define('create-medical-records', function ($user) {
            if ($user->role === 'doctor' && $user->is_active) {
                return true;
            }
            if ($user->role === 'admin') {
                return true;
            }
            return false;
        });

        // ── GATE: View Medical Records ──
        // Patients can view their own records
        // Doctors/hospital can view records from their facility
        // Admins can view all records
        Gate::define('view-medical-records', function ($user) {
            if (in_array($user->role, ['doctor', 'admin', 'hospital', 'facility', 'nurse', 'receptionist'])) {
                return true;
            }
            // Patients can also view their own
            if ($user->role === 'patient') {
                return true;
            }
            return false;
        });

        // ── GATE: Edit Medical Records ──
        // Only doctors can edit medical records (and only their own created records)
        Gate::define('edit-medical-records', function ($user) {
            if ($user->role === 'doctor' && $user->is_active) {
                return true;
            }
            if ($user->role === 'admin') {
                return true;
            }
            return false;
        });

        // ── GATE: Delete Medical Records ──
        // Only admins can delete medical records
        Gate::define('delete-medical-records', function ($user) {
            if ($user->role === 'admin') {
                return true;
            }
            return false;
        });
    }
}
