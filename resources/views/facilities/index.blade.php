<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facilities — AfyaLink</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<div style="display:flex;min-height:100vh;">
<aside style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);">
    <div style="font-size:16px;font-weight:700;color:white;">AfyaLink</div>
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Doctor Portal</div>
  </div>
  <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.08);display:flex;align-items:center;gap:10px;">
    <div style="width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr(Auth::user()->first_name ?? 'D', 0, 1)) }}</div>
    <div>
      <div style="font-size:13px;color:white;font-weight:600;">Dr. {{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</div>
      <div style="font-size:11px;color:rgba(255,255,255,.4);">{{ Auth::user()->specialization ?? 'General Practice' }}</div>
    </div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Main</div>
    <a href="{{ route('doctor.dashboard') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Dashboard</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Patients</div>
    <a href="{{ route('patients.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">My Patients</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Clinical</div>
    <a href="{{ route('referrals.create') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Create Referral</a>
    <a href="{{ route('referrals.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">My Referrals</a>
    <a href="{{ route('records.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Medical Records</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Tools</div>
    <a href="{{ route('patient.nearby-hospitals') }}" style="display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;">Nearby Hospitals</a>
    <a href="{{ route('facilities.index') }}" style="display:block;padding:10px 20px;font-size:13px;color:white;text-decoration:none;background:rgba(59,130,246,.2);border-left:3px solid #3b82f6;">Facilities</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Sign Out</button>
    </form>
  </div>
</aside>
<div style="margin-left:220px;flex:1;background:#f0f6ff;">
<div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:10;">
  <div style="font-size:20px;font-weight:700;color:#0f172a;">Facilities</div>
  <div style="font-size:12px;color:#94a3b8;margin-top:3px;">All active health facilities</div>
</div>
<div style="padding:24px 28px;">
        <div style="max-width:1200px;">
        <div class="grid gap-4">
            @forelse($facilities as $facility)
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:#dbeafe;">
                        <svg class="w-6 h-6" fill="none" stroke="#2563eb" viewBox="0 0 24 24">
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
                <div class="flex items-center gap-3">
                    @if($facility->latitude && $facility->longitude)
                    <a href="https://www.google.com/maps?q={{ $facility->latitude }},{{ $facility->longitude }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium px-3 py-1.5 rounded-lg" style="background:#dbeafe;color:#1d4ed8;text-decoration:none;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        View on Map
                    </a>
                    @else
                    <a href="https://www.google.com/maps/search/{{ urlencode($facility->name . ' ' . $facility->county . ' Kenya') }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium px-3 py-1.5 rounded-lg" style="background:#dbeafe;color:#1d4ed8;text-decoration:none;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Search Map
                    </a>
                    @endif
                    @php
                        $hours = is_string($facility->working_hours) ? json_decode($facility->working_hours, true) : $facility->working_hours;
                    @endphp
                    @if($hours)
                        <span class="inline-block text-xs font-medium px-3 py-1 rounded-full" style="background:#dbeafe;color:#2563eb;">
                            Mon: {{ $hours['Monday'] ?? 'N/A' }}
                        </span>
                    @endif
                    <span class="inline-block text-xs font-medium px-3 py-1 rounded-full" style="background:#e8f8ef;color:#276749;">Active</span>
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
</div>
</div></div>
</body>
</html>
