<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Referral;
use App\Models\MedicalRecord;
use App\Models\Facility;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients'    => Patient::count(),
            'patients_today'    => Patient::whereDate('created_at', today())->count(),
            'total_referrals'   => Referral::count(),
            'pending_referrals' => Referral::where('status', 'pending')->count(),
            'active_referrals'  => Referral::whereIn('status', ['pending', 'accepted'])->count(),
            'total_records'     => MedicalRecord::count(),
            'records_today'     => MedicalRecord::whereDate('created_at', today())->count(),
            'total_facilities'  => Facility::count(),
        ];

        $recent_patients  = Patient::latest()->take(5)->get();

        $recent_referrals = Referral::with(['patient', 'fromFacility', 'toFacility'])
                                    ->latest()->take(5)->get();

        $recent_records   = MedicalRecord::with('patient')
                                    ->latest()->take(5)->get();

        return view('dashboard', compact(
            'stats',
            'recent_patients',
            'recent_referrals',
            'recent_records'
        ));
    }
}