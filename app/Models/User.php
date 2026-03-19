<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Helpers\IdGenerator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'doctor_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'patient_id',
        'phone',
        'license_number',
        'specialization',
        'facility_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Optional: Full name accessor
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Relationship (if you have facilities table)
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    // Medical Records Relationships
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    public function doctorRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Boot Method
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Generate doctor_id automatically for doctors (DOC-000001)
            if (in_array($user->role, ['doctor', 'nurse']) && empty($user->doctor_id)) {
                $user->doctor_id = IdGenerator::generateDoctorId();
            }
        });
    }
}
