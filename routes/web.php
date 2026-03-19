<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MpesaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Professional Portal Routes
Route::get('/professional-portal', function () {
    return view('professional.portal');
})->name('professional.portal');

// Doctor Portal
Route::prefix('doctor')->group(function () {
    Route::get('/login', [\App\Http\Controllers\DoctorAuthController::class, 'showLogin'])->name('doctor.login');
    Route::post('/login', [\App\Http\Controllers\DoctorAuthController::class, 'login'])->name('doctor.login.submit');
    Route::get('/apply', [\App\Http\Controllers\DoctorAuthController::class, 'showApply'])->name('doctor.apply');
    Route::post('/apply', [\App\Http\Controllers\DoctorAuthController::class, 'apply'])->name('doctor.apply.submit');
    Route::get('/dashboard', [\App\Http\Controllers\DoctorAuthController::class, 'dashboard'])->name('doctor.dashboard')->middleware('auth');
});

// Hospital Portal
Route::prefix('hospital')->group(function () {
    Route::get('/login', [\App\Http\Controllers\HospitalAuthController::class, 'showLogin'])->name('hospital.login');
    Route::post('/login', [\App\Http\Controllers\HospitalAuthController::class, 'login'])->name('hospital.login.submit');
    Route::get('/register', [\App\Http\Controllers\HospitalAuthController::class, 'showRegister'])->name('hospital.register');
    Route::post('/register', [\App\Http\Controllers\HospitalAuthController::class, 'register'])->name('hospital.register.submit');
    Route::get('/dashboard', [\App\Http\Controllers\HospitalAuthController::class, 'dashboard'])->name('hospital.dashboard')->middleware('auth');
});

// Legacy routes (redirect to new portals)
Route::get('/doctor-portal', function () {
    return redirect()->route('doctor.login');
})->name('doctor.portal');

Route::get('/hospital-portal', function () {
    return redirect()->route('hospital.login');
})->name('hospital.portal');

Route::get('/admin-login', function () {
    return redirect()->route('admin.login');
})->name('admin.login.legacy');

// Admin Routes
Route::prefix('admin')->group(function () {
    // Admin Login (guest only - redirect to dashboard if already logged in)
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login')->middleware('guest');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit')->middleware('guest');

    // Admin Dashboard (protected - requires admin authentication)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth');

    // Admin Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout')->middleware('auth');

    // Doctor Application Verification
    Route::post('/doctors/{id}/approve', [AdminController::class, 'approveDoctor'])->name('admin.doctor.approve')->middleware('auth');
    Route::post('/doctors/{id}/reject', [AdminController::class, 'rejectDoctor'])->name('admin.doctor.reject')->middleware('auth');
    Route::get('/doctors/pending', [AdminController::class, 'pendingDoctors'])->name('admin.doctors.pending')->middleware('auth');

    // Facility Application Verification
    Route::post('/facilities/{id}/approve', [AdminController::class, 'approveFacility'])->name('admin.facility.approve')->middleware('auth');
    Route::post('/facilities/{id}/reject', [AdminController::class, 'rejectFacility'])->name('admin.facility.reject')->middleware('auth');
    Route::get('/facilities/pending', [AdminController::class, 'pendingFacilities'])->name('admin.facilities.pending')->middleware('auth');
});

// Guest-only routes (redirect to dashboard if already logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Logout (auth required)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected routes (must be logged in)
Route::middleware('auth')->group(function () {

    // Profile & Settings
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', [AuthController::class, 'settings'])->name('settings');
    Route::put('/settings/password', [AuthController::class, 'updatePassword'])->name('settings.password');

    // M-Pesa Payment Routes
    Route::post('/payment/initiate', [MpesaController::class, 'initiatePayment'])->name('payment.initiate');
    Route::post('/payment/callback', [MpesaController::class, 'handleCallback'])->name('payment.callback');
    Route::get('/payment/status', [MpesaController::class, 'queryStatus'])->name('payment.status');

    // Main Dashboard (default)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role-specific dashboards (generic fallback - for direct URL access)
    // Note: Doctor-specific dashboard is handled in the doctor prefix group above
    Route::get('/hospital/dashboard', [DashboardController::class, 'index'])->name('hospital.dashboard');
    Route::get('/patient/dashboard', [DashboardController::class, 'index'])->name('patient.dashboard');

    // ── PATIENT REFERRALS ──
    // Dedicated route for patients to view their own referrals
    Route::get('/patient/referrals', [PatientController::class, 'myReferrals'])->name('patient.referrals');

    // ── PATIENT RECORDS ──
    // Dedicated route for patients to view their own medical records
    Route::get('/patient/records', [PatientController::class, 'myRecords'])->name('patient.records');
    // Patient can download their own medical records (PDF)
    Route::get('/patient/medical-record/{id}/download', [MedicalRecordController::class, 'downloadPDF'])->name('patient.record.download');
    // Patient can download attached files from their medical records
    Route::get('/patient/medical-record/{id}/file', [MedicalRecordController::class, 'downloadFile'])->name('patient.record.file');

    // ── PATIENT NEARBY HOSPITALS ──
    // Route for patients to find nearby hospitals
    Route::get('/patient/nearby-hospitals', [FacilityController::class, 'nearbyHospitals'])->name('patient.nearby-hospitals');

    // ── FACILITY NEARBY FACILITIES ──
    // Route for hospitals to view nearby facilities
    Route::get('/facility/nearby', [FacilityController::class, 'nearbyHospitals'])->name('facility.nearby');

    // Patients - Only accessible by doctors, nurses, admins, and hospital staff
    Route::resource('patients', PatientController::class);

    // ── REFERRAL ROUTES ──
    // Referral creation is RESTRICTED to doctors only
    // Patients can only VIEW their referral status

    // Doctor-only routes for creating referrals
    Route::middleware(['can:create-referrals'])->group(function () {
        Route::get('/referrals/create', [ReferralController::class, 'create'])->name('referrals.create');
        Route::post('/referrals', [ReferralController::class, 'store'])->name('referrals.store');
    });

    // All authenticated users can view and update referrals (filtered by role in controller)
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');
    Route::get('/referrals/{referral}', [ReferralController::class, 'show'])->name('referrals.show');
    Route::patch('/referrals/{id}/status', [ReferralController::class, 'updateStatus'])->name('referrals.updateStatus');

    // Edit, update, delete - only for doctors and admins
    Route::middleware(['can:create-referrals'])->group(function () {
        Route::get('/referrals/{referral}/edit', [ReferralController::class, 'edit'])->name('referrals.edit');
        Route::put('/referrals/{referral}', [ReferralController::class, 'update'])->name('referrals.update');
        Route::delete('/referrals/{referral}', [ReferralController::class, 'destroy'])->name('referrals.destroy');
    });

    // ── MEDICAL RECORDS ROUTES ──
    // Creating medical records is RESTRICTED to doctors only
    // Patients can only view/download their own records

    // Doctor-only routes for creating medical records
    Route::middleware(['can:create-medical-records'])->group(function () {
        Route::get('/records/create', [MedicalRecordController::class, 'create'])->name('records.create');
        Route::post('/records', [MedicalRecordController::class, 'store'])->name('records.store');
        Route::get('/records/{record}/edit', [MedicalRecordController::class, 'edit'])->name('records.edit');
        Route::put('/records/{record}', [MedicalRecordController::class, 'update'])->name('records.update');
        Route::delete('/records/{record}', [MedicalRecordController::class, 'destroy'])->name('records.destroy');
    });

    // All authenticated users can view and download records (filtered by role in controller)
    Route::get('/records', [MedicalRecordController::class, 'index'])->name('records.index');
    Route::get('/records/{record}', [MedicalRecordController::class, 'show'])->name('records.show');
    Route::get('/records/{id}/download', [MedicalRecordController::class, 'downloadPDF'])->name('records.download');

    // Patient Search API (for doctor dashboard)
    Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search')->middleware('auth');

    // Facilities
    Route::resource('facilities', FacilityController::class);

});
