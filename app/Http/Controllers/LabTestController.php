<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LabTest;

class LabTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'patient') {
            $labTests = LabTest::where('patient_id', $user->id)
                ->with('doctor')->latest()->get();
            return view('lab-tests.patient-index', compact('labTests'));
        }
        $labTests = LabTest::where('doctor_id', $user->id)
            ->with('patient')->latest()->get();
        return view('lab-tests.doctor-index', compact('labTests'));
    }

    public function create()
    {
        $patients = \App\Models\User::where('role', 'patient')->orderBy('first_name')->get();
        return view('lab-tests.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'     => 'required|exists:users,id',
            'test_name'      => 'required|string|max:255',
            'test_category'  => 'required|string',
            'clinical_notes' => 'nullable|string',
            'requested_date' => 'required|date',
        ]);

        $labTest = LabTest::create([
            'patient_id'     => $request->patient_id,
            'doctor_id'      => Auth::id(),
            'test_name'      => $request->test_name,
            'test_category'  => $request->test_category,
            'clinical_notes' => $request->clinical_notes,
            'requested_date' => $request->requested_date,
            'status'         => 'requested',
        ]);

        \App\Models\Notification::send(
            $request->patient_id,
            'lab_test',
            'Lab Test Requested',
            'Dr. ' . Auth::user()->first_name . ' has requested a ' . $request->test_name . ' for you. Please visit the lab at your earliest convenience.',
            null
        );

        return redirect()->route('lab-tests.index')
            ->with('success', 'Lab test requested successfully.');
    }

    public function uploadResult(Request $request, $id)
    {
        $request->validate([
            'result_file'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'result_notes' => 'nullable|string',
            'result_date'  => 'required|date',
        ]);

        $labTest = LabTest::findOrFail($id);
        $file = $request->file('result_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/lab_results', $filename);

        $labTest->update([
            'result_file'  => 'lab_results/' . $filename,
            'result_notes' => $request->result_notes,
            'result_date'  => $request->result_date,
            'status'       => 'completed',
        ]);

        \App\Models\Notification::send(
            $labTest->patient_id,
            'lab_result',
            'Lab Results Ready',
            'Your ' . $labTest->test_name . ' results are ready. You can view and download them from your dashboard.',
            null
        );

        return back()->with('success', 'Lab results uploaded successfully.');
    }

    public function downloadResult($id)
    {
        $labTest = LabTest::findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'patient' && $labTest->patient_id !== $user->id) {
            abort(403);
        }
        if (!$labTest->result_file || !\Illuminate\Support\Facades\Storage::exists('public/' . $labTest->result_file)) {
            return back()->with('error', 'Result file not found.');
        }
        $filePath = storage_path('app/public/' . $labTest->result_file);
        return response()->download($filePath, basename($labTest->result_file));
    }
}