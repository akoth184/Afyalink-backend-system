<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Patient extends Model
{
    protected $fillable = [
        'patient_number',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'national_id',
        'nhif_number',
        'phone',
        'email',
        'address',
        'county',
        'next_of_kin_name',
        'next_of_kin_phone',
        'next_of_kin_relationship',
        'blood_group',
        'allergies',
        'chronic_conditions',
        'registered_by',
        'facility_id'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Boot Method
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {

            // ✅ Automatically assign logged-in user
            if (Auth::check()) {
                $patient->registered_by = Auth::id();
                $patient->facility_id   = Auth::user()->facility_id;
            }

            // ✅ Generate patient number safely
            $lastId = DB::table('patients')->max('id') ?? 0;
            $nextNumber = str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

            $patient->patient_number = 'AFL-' . date('Y') . '-' . $nextNumber;
        });
    }
}