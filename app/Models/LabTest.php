<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LabTest extends Model
{
    protected $fillable = [
        'patient_id','doctor_id','test_name','test_category',
        'clinical_notes','status','result_file','result_notes',
        'requested_date','result_date'
    ];
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}