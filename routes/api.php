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

    // Patients
    Route::apiResource('patients', PatientController::class);
    Route::get('/patients/{id}/records', [PatientController::class, 'records']);

    // Referrals
    Route::apiResource('referrals', ReferralController::class);
    Route::patch('/referrals/{id}/status', [ReferralController::class, 'updateStatus']);

    // Medical Records
    Route::apiResource('medical-records', MedicalRecordController::class);

    // Facilities
    Route::apiResource('facilities', FacilityController::class);
});