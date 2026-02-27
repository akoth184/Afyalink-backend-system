<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalRecordController extends Controller
{
    /**
     * GET /api/medical-records
     * List all medical records with related patient, facility, and doctor
     */
    public function index()
    {
        $records = MedicalRecord::with(['patient', 'facility', 'doctor'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $records,
        ], 200);
    }

    /**
     * POST /api/medical-records
     * Create a new medical record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id'                  => 'required|exists:patients,id',
            'facility_id'                 => 'required|exists:facilities,id',
            'doctor_id'                   => 'required|exists:users,id',
            'visit_date'                  => 'required|date',
            'chief_complaint'             => 'required|string',
            'history_of_present_illness'  => 'nullable|string',
            'vital_signs'                 => 'nullable|array',
            'examination_findings'        => 'nullable|string',
            'diagnosis'                   => 'nullable|string',
            'treatment_plan'              => 'nullable|string',
            'medications'                 => 'nullable|string',
            'lab_results'                 => 'nullable|array',
            'notes'                       => 'nullable|string',
            'status'                      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $record = MedicalRecord::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Medical record created successfully.',
            'data'    => $record->load(['patient', 'facility', 'doctor']),
        ], 201);
    }

    /**
     * GET /api/medical-records/{id}
     * Show a single medical record
     */
    public function show($id)
    {
        $record = MedicalRecord::with(['patient', 'facility', 'doctor'])->find($id);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Medical record not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $record,
        ], 200);
    }

    /**
     * PUT /api/medical-records/{id}
     * Update a medical record
     */
    public function update(Request $request, $id)
    {
        $record = MedicalRecord::find($id);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Medical record not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'patient_id'                  => 'sometimes|exists:patients,id',
            'facility_id'                 => 'sometimes|exists:facilities,id',
            'doctor_id'                   => 'sometimes|exists:users,id',
            'visit_date'                  => 'sometimes|date',
            'chief_complaint'             => 'sometimes|string',
            'history_of_present_illness'  => 'nullable|string',
            'vital_signs'                 => 'nullable|array',
            'examination_findings'        => 'nullable|string',
            'diagnosis'                   => 'nullable|string',
            'treatment_plan'              => 'nullable|string',
            'medications'                 => 'nullable|string',
            'lab_results'                 => 'nullable|array',
            'notes'                       => 'nullable|string',
            'status'                      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $record->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Medical record updated successfully.',
            'data'    => $record->load(['patient', 'facility', 'doctor']),
        ], 200);
    }

    /**
     * DELETE /api/medical-records/{id}
     * Delete a medical record
     */
    public function destroy($id)
    {
        $record = MedicalRecord::find($id);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Medical record not found.',
            ], 404);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Medical record deleted successfully.',
        ], 200);
    }
}