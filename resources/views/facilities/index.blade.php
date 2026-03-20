<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facilities — AfyaLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Healthcare Facilities</h1>
                <p class="text-gray-500 text-sm mt-1">All registered facilities on AfyaLink</p>
            </div>
            <a href="{{ route('doctor.dashboard') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">← Back to Dashboard</a>
        </div>
        <div class="grid gap-4">
            @forelse($facilities as $facility)
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $facility->name }}</h3>
                        <p class="text-sm text-gray-500">{{ ucfirst($facility->type) }} · {{ $facility->county }}</p>
                        @if($facility->phone)
                        <p class="text-sm text-gray-500">{{ $facility->phone }}</p>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    @php
                        $hours = is_string($facility->working_hours) ? json_decode($facility->working_hours, true) : $facility->working_hours;
                    @endphp
                    @if($hours)
                        <span class="inline-block bg-teal-50 text-teal-700 text-xs font-medium px-3 py-1 rounded-full">
                            Mon: {{ $hours['Monday'] ?? 'N/A' }}
                        </span>
                    @endif
                    <div class="mt-2">
                        <span class="inline-block bg-green-50 text-green-700 text-xs font-medium px-3 py-1 rounded-full">Active</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <p class="text-gray-500">No facilities found</p>
            </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $facilities->links() }}</div>
    </div>
</body>
</html>
