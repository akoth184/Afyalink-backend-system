<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'referring_facility_id',
        'receiving_facility_id',
        'status',
        'reason',
        'notes',
        'referred_by',
        'priority',
        'appointment_date',
        'clinical_summary',
        'attachment_path',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(\App\Models\User::class, 'patient_id');
    }

    // FIX: use actual DB column names
    public function fromFacility()
    {
        return $this->belongsTo(Facility::class, 'referring_facility_id');
    }

    public function toFacility()
    {
        return $this->belongsTo(Facility::class, 'receiving_facility_id');
    }

    public function referringFacility()
    {
        return $this->belongsTo(\App\Models\Facility::class, 'referring_facility_id');
    }

    public function receivingFacility()
    {
        return $this->belongsTo(\App\Models\Facility::class, 'receiving_facility_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referredByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'referred_by');
    }
}
