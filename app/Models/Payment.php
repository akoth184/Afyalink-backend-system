<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'patient_id', 'payment_type', 'amount', 'phone_number',
        'status', 'mpesa_receipt', 'checkout_request_id',
        'merchant_request_id', 'description', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
