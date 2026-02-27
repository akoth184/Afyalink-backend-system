<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\FacilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Landing page ──────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ── Guest-only routes (redirect to dashboard if already logged in) ─────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// ── Logout (auth required) ─────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Protected routes (must be logged in) ──────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);

    // Referrals
    Route::resource('referrals', ReferralController::class);
    Route::patch('/referrals/{id}/status', [ReferralController::class, 'updateStatus'])
         ->name('referrals.updateStatus');

    // Medical Records
    Route::resource('records', MedicalRecordController::class);

    // Facilities
    Route::resource('facilities', FacilityController::class);

});