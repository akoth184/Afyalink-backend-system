<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name', 'type', 'mfl_code', 'county', 'sub_county',
        'ward', 'phone', 'email', 'latitude', 'longitude', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

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
}