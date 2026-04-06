<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Appointment extends Model
{
    protected $fillable = [
        'referral_id','patient_id','doctor_id','facility_id',
        'appointment_date','appointment_time','status','notes'
    ];
    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
    public static function isAvailable($doctorId, $date, $time, $excludeId = null)
    {
        $query = self::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->where('status', '!=', 'cancelled');
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->count() === 0;
    }
}