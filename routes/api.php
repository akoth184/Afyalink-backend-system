<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\FacilityController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ── PATIENTS ──
    // Patients can only be managed by doctors, nurses, admins
    Route::apiResource('patients', PatientController::class)->names([
        'index' => 'api.patients.index',
        'store' => 'api.patients.store',
        'show' => 'api.patients.show',
        'update' => 'api.patients.update',
        'destroy' => 'api.patients.destroy',
    ]);
    Route::get('/patients/{id}/records', [PatientController::class, 'records']);

    // ── REFERRALS ──
    // All authenticated users can access referrals (authorization handled in controller)
    Route::apiResource('referrals', ReferralController::class)->names([
        'index' => 'api.referrals.index',
        'store' => 'api.referrals.store',
        'show' => 'api.referrals.show',
        'update' => 'api.referrals.update',
        'destroy' => 'api.referrals.destroy',
    ]);
    Route::patch('/referrals/{id}/status', [ReferralController::class, 'updateStatus']);

    // ── MEDICAL RECORDS ──
    // All authenticated users can access records (authorization handled in controller)
    Route::apiResource('medical-records', MedicalRecordController::class);

    // ── FACILITIES ──
    // Only admins can manage facilities via API
    Route::apiResource('facilities', FacilityController::class)->names([
        'index' => 'api.facilities.index',
        'store' => 'api.facilities.store',
        'show' => 'api.facilities.show',
        'update' => 'api.facilities.update',
        'destroy' => 'api.facilities.destroy',
    ]);
});
