<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Profile — AfyaLink</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <a href="{{ route('patients.index') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium mb-6 inline-block">← Back to Patients</a>
        <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-full bg-teal-600 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $patient->first_name }} {{ $patient->last_name }}</h1>
                    <p class="text-gray-500">{{ $patient->patient_id ?? 'No Patient ID' }} · {{ $patient->email }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Email</p>
                    <p class="text-gray-900 font-medium">{{ $patient->email }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Patient ID</p>
                    <p class="text-gray-900 font-medium">{{ $patient->patient_id ?? 'Not assigned' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Role</p>
                    <p class="text-gray-900 font-medium">{{ ucfirst($patient->role) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Registered</p>
                    <p class="text-gray-900 font-medium">{{ $patient->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Medical Records</h2>
            @forelse($records as $record)
            <div class="border-b border-gray-100 py-3 last:border-0">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">{{ $record->diagnosis ?? 'No diagnosis' }}</p>
                        <p class="text-sm text-gray-500">{{ $record->visit_date ? $record->visit_date->format('d M Y') : 'No date' }}</p>
                    </div>
                    <span class="text-xs bg-teal-50 text-teal-700 px-2 py-1 rounded-full">{{ ucfirst($record->status ?? 'draft') }}</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-sm text-center py-4">No medical records yet</p>
            @endforelse
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Referrals</h2>
            @forelse($referrals as $referral)
            <div class="border-b border-gray-100 py-3 last:border-0 flex justify-between items-center">
                <div>
                    <p class="font-medium text-gray-900">{{ $referral->reason ?? 'No reason specified' }}</p>
                    <p class="text-sm text-gray-500">{{ $referral->created_at->format('d M Y') }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full font-medium
                    @if($referral->status === 'accepted') bg-green-50 text-green-700
                    @elseif($referral->status === 'rejected') bg-red-50 text-red-700
                    @else bg-yellow-50 text-yellow-700 @endif">
                    {{ ucfirst($referral->status ?? 'pending') }}
                </span>
            </div>
            @empty
            <p class="text-gray-500 text-sm text-center py-4">No referrals yet</p>
            @endforelse
        </div>
    </div>
</body>
</html>
