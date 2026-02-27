MedicalRecord::create([
    'patient_id'      => 1,
    'facility_id'     => 1,
    'doctor_id'       => 1,
    'visit_date'      => now(),
    'chief_complaint' => 'Headache and fever',
    'diagnosis'       => 'Viral infection',
    'status'          => 'active',
]);