<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class IdGenerator
{
    /**
     * Generate a unique patient ID
     * Format: PAT-000001, PAT-000002, etc.
     */
    public static function generatePatientId(): string
    {
        return self::generateId('patients', 'patient_id', 'PAT');
    }

    /**
     * Generate a unique doctor ID
     * Format: DOC-000001, DOC-000002, etc.
     */
    public static function generateDoctorId(): string
    {
        return self::generateId('users', 'doctor_id', 'DOC');
    }

    /**
     * Generate a unique hospital ID
     * Format: HOS-000001, HOS-000002, etc.
     */
    public static function generateHospitalId(): string
    {
        return self::generateId('facilities', 'hospital_id', 'HOS');
    }

    /**
     * Generate a unique ID with the given prefix
     *
     * @param string $table The table name
     * @param string $column The column name
     * @param string $prefix The prefix (PAT, DOC, HOS)
     * @param int $padding Number of digits to pad
     * @return string
     */
    private static function generateId(string $table, string $column, string $prefix, int $padding = 6): string
    {
        // Get the last record with this ID type
        $lastRecord = DB::table($table)
            ->whereNotNull($column)
            ->orderByDesc('id')
            ->first();

        if ($lastRecord) {
            // Extract the number from the last ID and increment
            $lastNumber = (int) substr($lastRecord->$column, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            // First record
            $nextNumber = 1;
        }

        return $prefix . str_pad((string) $nextNumber, $padding, '0', STR_PAD_LEFT);
    }
}
