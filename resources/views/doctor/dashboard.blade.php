<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Dashboard — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link:hover { background-color: rgba(255,255,255,0.1); }
        .sidebar-link.active { background-color: rgba(255,255,255,0.15); border-left: 3px solid #5eead4; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-teal-900 text-white flex flex-col no-print">
            <div class="p-6 border-b border-teal-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg">AfyaLink</h1>
                        <p class="text-xs text-teal-300">Doctor Portal</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-1">
                <a href="#" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                    <i class="fas fa-th-large w-5"></i> Dashboard
                </a>
                <a href="{{ route('patients.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-teal-100">
                    <i class="fas fa-users w-5"></i> Patients
                </a>
                <a href="{{ route('records.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-teal-100">
                    <i class="fas fa-file-medical w-5"></i> Medical Records
                </a>
                <a href="{{ route('referrals.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-teal-100">
                    <i class="fas fa-share-alt w-5"></i> Referrals
                    @if($stats['pending_referrals'] > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['pending_referrals'] }}</span>
                    @endif
                </a>
                <a href="{{ route('facilities.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-teal-100">
                    <i class="fas fa-hospital w-5"></i> Facilities
                </a>
            </nav>

            <div class="p-4 border-t border-teal-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-700 rounded-full flex items-center justify-center">
                        <span class="text-sm font-semibold">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">Dr. {{ $user->first_name }} {{ $user->last_name }}</p>
                        <p class="text-xs text-teal-300 truncate">{{ $user->specialization ?? 'Doctor' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-teal-200 hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between no-print">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    <p class="text-sm text-gray-500">Welcome back, Dr. {{ $user->last_name }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="openModal('newRecordModal')" class="btn-primary flex items-center gap-2">
                        <i class="fas fa-plus"></i> New Record
                    </button>
                </div>
            </header>

            <div class="p-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Patients Today</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['patients_today'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-injured text-teal-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Patients</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_patients'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pending Referrals</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_referrals'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-amber-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Medical Records</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_records'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file-medical-alt text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Patient Search -->
                        <div class="card p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-search text-teal-600 mr-2"></i>Quick Patient Search
                            </h2>
                            <div class="relative">
                                <input type="text" id="patientSearch"
                                    placeholder="Search by name, patient ID, phone, or email..."
                                    class="input-field pl-12"
                                    autocomplete="off">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <div id="patientResults" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-80 overflow-y-auto"></div>
                            </div>

                            <!-- Selected Patient Details -->
                            <div id="selectedPatientDetails" class="hidden mt-4 p-4 bg-teal-50 rounded-lg border border-teal-200">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-800" id="patientFullName"></h3>
                                        <p class="text-sm text-gray-600">ID: <span id="patientId"></span></p>
                                        <p class="text-sm text-gray-600">Phone: <span id="patientPhone"></span></p>
                                        <p class="text-sm text-gray-600">Email: <span id="patientEmail"></span></p>
                                        <p class="text-sm text-gray-600">DOB: <span id="patientDob"></span></p>
                                        <p class="text-sm text-gray-600">Gender: <span id="patientGender"></span></p>
                                        <p class="text-sm text-gray-600">Blood Group: <span id="patientBloodGroup"></span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Medical Records</p>
                                        <p class="text-2xl font-bold text-teal-600" id="patientRecordsCount">0</p>
                                    </div>
                                </div>
                                <input type="hidden" id="selectedPatientId">
                                <div class="mt-4 flex gap-2">
                                    <button onclick="createMedicalRecord()" class="btn-primary text-sm">
                                        <i class="fas fa-file-medical mr-1"></i> Create Record
                                    </button>
                                    <a href="#" id="viewPatientLink" class="btn-secondary text-sm">
                                        <i class="fas fa-eye mr-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Patients -->
                        <div class="card">
                            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-users text-teal-600 mr-2"></i>Recent Patients
                                </h2>
                                <a href="{{ route('patients.index') }}" class="text-sm text-teal-600 hover:text-teal-700">View All</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient #</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($recentPatients as $patient)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $patient->patient_number }}</td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $patient->phone ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $patient->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('patients.show', $patient->id) }}" class="text-teal-600 hover:text-teal-700 text-sm">View</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No patients yet</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Nearby Hospitals -->
                        <div class="card">
                            <div class="p-6 border-b border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-hospital text-teal-600 mr-2"></i>Nearby Hospitals (50km radius)
                                </h2>
                            </div>
                            <div class="p-6">
                                @if(count($nearbyHospitals) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($nearbyHospitals as $hospital)
                                    <div class="p-4 border border-gray-200 rounded-lg hover:border-teal-300 transition-colors">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="font-semibold text-gray-800">{{ $hospital['name'] }}</h3>
                                                <p class="text-sm text-gray-500 capitalize">{{ $hospital['type'] }}</p>
                                                <p class="text-sm text-gray-500">{{ $hospital['county'] }}</p>
                                            </div>
                                            <span class="px-2 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded">
                                                {{ $hospital['distance'] }} km
                                            </span>
                                        </div>
                                        <div class="mt-3 flex items-center gap-4 text-sm text-gray-500">
                                            @if($hospital['phone'])
                                            <span><i class="fas fa-phone mr-1"></i>{{ $hospital['phone'] }}</span>
                                            @endif
                                        </div>
                                        @if(isset($hospital['working_hours']) && $hospital['working_hours'])
                                        <div class="mt-2 text-xs text-gray-500">
                                            @php
                                                $hours = is_array($hospital['working_hours']) ? $hospital['working_hours'] : json_decode($hospital['working_hours'], true);
                                            @endphp
                                            @if(is_array($hours) && count($hours) > 0)
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach($hours as $day => $time)
                                                    <span class="px-2 py-0.5 bg-teal-50 text-teal-700 text-xs rounded">{{ $day }}: {{ $time }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-map-marker-alt text-4xl mb-3 text-gray-300"></i>
                                    <p>No nearby hospitals found</p>
                                    <p class="text-sm">Configure your facility location to see nearby hospitals</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Quick Actions -->
                        <div class="card p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-bolt text-teal-600 mr-2"></i>Quick Actions
                            </h2>
                            <div class="space-y-3">
                                <a href="{{ route('patients.create') }}" class="flex items-center gap-3 p-3 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 transition-colors">
                                    <i class="fas fa-user-plus w-5"></i>
                                    <span class="font-medium">Register Patient</span>
                                </a>
                                <button onclick="openModal('newRecordModal')" class="w-full flex items-center gap-3 p-3 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 transition-colors">
                                    <i class="fas fa-file-medical w-5"></i>
                                    <span class="font-medium">New Medical Record</span>
                                </button>
                                <a href="{{ route('referrals.create') }}" class="flex items-center gap-3 p-3 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 transition-colors">
                                    <i class="fas fa-share-alt w-5"></i>
                                    <span class="font-medium">Create Referral</span>
                                </a>
                                <a href="{{ route('facilities.index') }}" class="flex items-center gap-3 p-3 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 transition-colors">
                                    <i class="fas fa-list w-5"></i>
                                    <span class="font-medium">View Facilities</span>
                                </a>
                            </div>
                        </div>

                        <!-- Recent Medical Records -->
                        <div class="card">
                            <div class="p-6 border-b border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-file-medical-alt text-teal-600 mr-2"></i>Recent Records
                                </h2>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse($recentRecords as $record)
                                <div class="p-4 hover:bg-gray-50">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $record->patient->first_name ?? 'N/A' }} {{ $record->patient->last_name ?? '' }}</p>
                                            <p class="text-sm text-gray-500">{{ $record->chief_complaint ?? 'No complaint recorded' }}</p>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $record->visit_date ? $record->visit_date->format('M d') : '' }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="p-6 text-center text-gray-500 text-sm">No records yet</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- New Medical Record Modal -->
    <div id="newRecordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
                <h2 class="text-xl font-semibold text-gray-800">New Medical Record</h2>
                <button onclick="closeModal('newRecordModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('records.store') }}" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="facility_id" value="{{ $facilityId }}">
                <input type="hidden" name="doctor_id" value="{{ $user->id }}">

                <!-- Patient Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                    <div class="relative">
                        <input type="text" id="modalPatientSearch"
                            placeholder="Search by name, ID, phone..."
                            class="input-field pl-12"
                            autocomplete="off">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <div id="modalPatientResults" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto"></div>
                    </div>
                    <input type="hidden" name="patient_id" id="modalSelectedPatientId" required>
                    <div id="modalSelectedPatientName" class="mt-2 text-sm text-teal-600 font-medium"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Visit Date *</label>
                        <input type="date" name="visit_date" required class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="input-field">
                            <option value="completed">Completed</option>
                            <option value="in_progress">In Progress</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chief Complaint *</label>
                    <input type="text" name="chief_complaint" required placeholder="Primary reason for visit" class="input-field">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">History of Present Illness</label>
                    <textarea name="history_of_present_illness" rows="2" placeholder="Describe the patient's current condition" class="input-field"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis</label>
                    <input type="text" name="diagnosis" placeholder="Medical diagnosis" class="input-field">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Treatment Plan</label>
                    <textarea name="treatment_plan" rows="2" placeholder="Recommended treatment" class="input-field"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Medications</label>
                    <textarea name="medications" rows="2" placeholder="Prescribed medications" class="input-field"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="2" placeholder="Additional notes" class="input-field"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attachment (PDF, DOC, JPG - max 10MB)</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="input-field">
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeModal('newRecordModal')" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Create Record</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('newRecordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal('newRecordModal');
            }
        });

        // Patient search functionality - Main search
        const patientSearch = document.getElementById('patientSearch');
        const patientResults = document.getElementById('patientResults');
        const selectedPatientDetails = document.getElementById('selectedPatientDetails');
        let searchTimeout;

        patientSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;

            if (query.length < 2) {
                patientResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch('/patients/search?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(patients => {
                        if (patients.length > 0) {
                            patientResults.innerHTML = patients.map(p => `
                                <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0"
                                    onclick="selectPatient(${p.id}, '${p.first_name} ${p.last_name}', '${p.patient_number || p.patient_id}', '${p.phone || ''}', '${p.email || ''}', '${p.date_of_birth || ''}', '${p.gender || ''}', '${p.blood_group || ''}', ${p.medical_records_count || 0})">
                                    <div class="font-medium text-gray-800">${p.first_name} ${p.last_name}</div>
                                    <div class="text-sm text-gray-500">${p.patient_number || p.patient_id} • ${p.phone || 'No phone'}</div>
                                </div>
                            `).join('');
                            patientResults.classList.remove('hidden');
                        } else {
                            patientResults.innerHTML = '<div class="p-3 text-gray-500 text-center">No patients found</div>';
                            patientResults.classList.remove('hidden');
                        }
                    });
            }, 300);
        });

        function selectPatient(id, name, patientNum, phone, email, dob, gender, bloodGroup, recordsCount) {
            document.getElementById('selectedPatientId').value = id;
            document.getElementById('patientFullName').textContent = name;
            document.getElementById('patientId').textContent = patientNum;
            document.getElementById('patientPhone').textContent = phone || 'N/A';
            document.getElementById('patientEmail').textContent = email || 'N/A';
            document.getElementById('patientDob').textContent = dob || 'N/A';
            document.getElementById('patientGender').textContent = gender || 'N/A';
            document.getElementById('patientBloodGroup').textContent = bloodGroup || 'N/A';
            document.getElementById('patientRecordsCount').textContent = recordsCount;
            document.getElementById('viewPatientLink').href = '/patients/' + id;

            patientSearch.value = name;
            patientResults.classList.add('hidden');
            selectedPatientDetails.classList.remove('hidden');
        }

        function createMedicalRecord() {
            const patientId = document.getElementById('selectedPatientId').value;
            if (patientId) {
                document.getElementById('modalSelectedPatientId').value = patientId;
                openModal('newRecordModal');
            }
        }

        // Modal patient search
        const modalPatientSearch = document.getElementById('modalPatientSearch');
        const modalPatientResults = document.getElementById('modalPatientResults');

        modalPatientSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;

            if (query.length < 2) {
                modalPatientResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch('/patients/search?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(patients => {
                        if (patients.length > 0) {
                            modalPatientResults.innerHTML = patients.map(p => `
                                <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0"
                                    onclick="selectModalPatient(${p.id}, '${p.first_name} ${p.last_name}')">
                                    <div class="font-medium text-gray-800">${p.first_name} ${p.last_name}</div>
                                    <div class="text-sm text-gray-500">${p.patient_number || p.patient_id}</div>
                                </div>
                            `).join('');
                            modalPatientResults.classList.remove('hidden');
                        } else {
                            modalPatientResults.innerHTML = '<div class="p-3 text-gray-500 text-center">No patients found</div>';
                            modalPatientResults.classList.remove('hidden');
                        }
                    });
            }, 300);
        });

        function selectModalPatient(id, name) {
            document.getElementById('modalSelectedPatientId').value = id;
            document.getElementById('modalSelectedPatientName').textContent = 'Selected: ' + name;
            modalPatientSearch.value = name;
            modalPatientResults.classList.add('hidden');
        }

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!patientSearch.contains(e.target) && !patientResults.contains(e.target)) {
                patientResults.classList.add('hidden');
            }
            if (!modalPatientSearch.contains(e.target) && !modalPatientResults.contains(e.target)) {
                modalPatientResults.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
