<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'facility_id',
        'doctor_id',
        'visit_date',
        'chief_complaint',
        'history_of_present_illness',
        'vital_signs',
        'examination_findings',
        'diagnosis',
        'treatment_plan',
        'medications',
        'lab_results',
        'notes',
        'status'
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'vital_signs' => 'array',
        'lab_results' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}