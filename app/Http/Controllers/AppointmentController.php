<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if($user->role === 'patient') {
            $appointments = Appointment::where('patient_id', $user->id)
                ->with(['doctor','facility'])
                ->latest()->get();
            return view('appointments.patient-index', compact('appointments'));
        }
        if($user->role === 'doctor') {
            $appointments = Appointment::where('doctor_id', $user->id)
                ->with(['patient','facility'])
                ->latest()->get();
            return view('appointments.doctor-index', compact('appointments'));
        }
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:users,id',
            'doctor_id'        => 'required|exists:users,id',
            'facility_id'      => 'required|exists:facilities,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $exists = Appointment::where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where(function($query) use ($request) {
                $query->where('patient_id', $request->patient_id)
                      ->where('doctor_id', $request->doctor_id);
            })->exists();

        if ($exists) {
            return back()->with('error', 'This appointment slot is already booked for this patient with this doctor.');
        }

        $doctorBooked = Appointment::where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('doctor_id', $request->doctor_id)
            ->exists();

        if ($doctorBooked) {
            return back()->with('error', 'This doctor already has an appointment at this time.');
        }

        $appointment = Appointment::create([
            'patient_id'       => $request->patient_id,
            'doctor_id'        => $request->doctor_id,
            'facility_id'      => $request->facility_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes'            => $request->notes,
            'status'           => 'scheduled',
            'referral_id'      => $request->referral_id ?? null,
        ]);

        // Notify patient
        $doctor = \App\Models\User::find($request->doctor_id);
        $facility = \App\Models\Facility::find($request->facility_id);
        \App\Models\Notification::send(
            $request->patient_id,
            'appointment',
            'Appointment Scheduled',
            'You have an appointment with Dr. ' . optional($doctor)->first_name . ' ' . optional($doctor)->last_name . ' at ' . optional($facility)->name . ' on ' . \Carbon\Carbon::parse($request->appointment_date)->format('d M Y') . ' at ' . \Carbon\Carbon::parse($request->appointment_time)->format('h:i A') . '.',
            null
        );

        // Notify doctor
        $patient = \App\Models\User::find($request->patient_id);
        \App\Models\Notification::send(
            $request->doctor_id,
            'appointment',
            'New Appointment',
            'You have a new appointment with ' . optional($patient)->first_name . ' ' . optional($patient)->last_name . ' on ' . \Carbon\Carbon::parse($request->appointment_date)->format('d M Y') . ' at ' . \Carbon\Carbon::parse($request->appointment_time)->format('h:i A') . '.',
            null
        );

        return back()->with('success', 'Appointment booked successfully. Patient has been notified.');
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Appointment ' . $request->status . ' successfully.');
    }

    public function getAvailableSlots(Request $request)
    {
        return response()->json(['slots' => []]);
    }
}