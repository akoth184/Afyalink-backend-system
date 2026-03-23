<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\IdGenerator;

class Facility extends Model
{
    protected $fillable = [
        'hospital_id',
        'name', 'type', 'mfl_code', 'county', 'sub_county',
        'ward', 'phone', 'email', 'latitude', 'longitude',
        'is_active', 'working_hours'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'working_hours' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function outgoingReferrals()
    {
        return $this->hasMany(Referral::class, 'referring_facility_id');
    }

    public function incomingReferrals()
    {
        return $this->hasMany(Referral::class, 'receiving_facility_id');
    }

    public function referralsFrom()
    {
        return $this->hasMany(Referral::class, 'referring_facility_id');
    }

    public function referralsTo()
    {
        return $this->hasMany(Referral::class, 'receiving_facility_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Boot Method
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($facility) {
            // Generate hospital_id automatically (HOS-000001)
            if (empty($facility->hospital_id)) {
                $facility->hospital_id = IdGenerator::generateHospitalId();
            }
        });
    }
}
