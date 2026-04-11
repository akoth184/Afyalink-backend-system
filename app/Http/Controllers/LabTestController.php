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
            'result_file'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'result_value'    => 'nullable|string',
            'reference_range' => 'nullable|string',
            'test_status'     => 'nullable|in:normal,abnormal,critical',
            'result_notes'    => 'required|string',
            'result_date'     => 'required|date',
        ]);

        $labTest = LabTest::findOrFail($id);
        
        $resultNotes = $request->result_notes;
        
        if ($request->result_value) {
            $resultNotes .= "\n\nNumeric Result: " . $request->result_value;
        }
        if ($request->reference_range) {
            $resultNotes .= " (Ref: " . $request->reference_range . ")";
        }
        if ($request->test_status) {
            $resultNotes .= "\nStatus: " . strtoupper($request->test_status);
        }
        
        $updateData = [
            'result_notes' => $resultNotes,
            'result_date'  => $request->result_date,
            'status'      => 'completed',
        ];

        if ($request->hasFile('result_file')) {
            $file = $request->file('result_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/lab_results', $filename);
            $updateData['result_file'] = 'lab_results/' . $filename;
        }

        $labTest->update($updateData);

        \App\Models\Notification::send(
            $labTest->patient_id,
            'lab_result',
            'Lab Results Ready',
            'Your ' . $labTest->test_name . ' results are ready. Check your patient dashboard for details.',
            null
        );

        return back()->with('success', 'Lab results submitted successfully.');
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