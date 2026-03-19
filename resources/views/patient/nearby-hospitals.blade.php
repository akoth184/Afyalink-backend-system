<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nearby Hospitals — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        :root {
            --blue-50:  #f0f6ff;
            --blue-100: #dbeafe;
            --blue-200: #bfdbfe;
            --blue-400: #3b82f6;
            --blue-500: #2563eb;
            --blue-600: #1d4ed8;
            --blue-700: #1e40af;
            --accent:   #2563eb;
            --accent-light: #eff6ff;
            --accent-hover: #1d4ed8;
            --green-400: #34d399;
            --green-500: #10b981;
            --green-light: #ecfdf5;
            --amber-400: #f59e0b;
            --amber-light: #fffbeb;
            --coral-400: #f43f5e;
            --coral-light: #fff1f2;
            --purple-400: #a78bfa;
            --purple-light: #f5f3ff;
            --bg:        #f4f7fb;
            --surface:   #ffffff;
            --surface-2: #f8fafc;
            --border:    #e2e8f0;
            --border-soft: #f1f5f9;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --sidebar-w: 220px;
            --header-h:  64px;
            --radius-2xl: 20px;
            --radius-xl:  16px;
            --radius-lg:  12px;
            --radius-md:  8px;
            --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:  0 4px 16px rgba(37,99,235,0.08), 0 1px 4px rgba(0,0,0,0.06);
            --shadow-card:0 2px 12px rgba(0,0,0,0.06);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text-primary);min-height:100vh;overflow-x:hidden}

        /* Topbar */
        .topbar{
            height:var(--header-h);
            background:var(--surface);
            border-bottom:1px solid var(--border);
            display:flex;align-items:center;
            padding:0 24px;gap:16px;
            position:fixed;top:0;left:0;right:0;z-index:300;
            box-shadow:0 1px 4px rgba(0,0,0,0.05);
        }
        .logo{display:flex;align-items:center;gap:10px;text-decoration:none}
        .logo-mark{
            width:38px;height:38px;border-radius:11px;
            background:linear-gradient(135deg,var(--blue-500),#0ea5e9);
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 2px 10px rgba(37,99,235,0.35);flex-shrink:0;
        }
        .logo-mark svg{width:18px;height:18px;fill:none;stroke:white;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
        .logo-text{font-family:'DM Serif Display',serif;font-size:1.35rem;color:var(--text-primary);letter-spacing:-.01em}
        .logo-text span{color:var(--accent)}
        .topbar-spacer{flex:1}
        .topbar-right{display:flex;align-items:center;gap:14px}
        .topbar-pill{
            display:flex;align-items:center;gap:6px;
            padding:6px 14px;border-radius:100px;
            background:var(--accent-light);
            border:1px solid var(--blue-200);
            font-size:.75rem;font-weight:600;color:var(--accent);
        }
        .status-dot{width:6px;height:6px;border-radius:50%;background:var(--green-500);box-shadow:0 0 6px var(--green-400);animation:pulse-dot 2s ease-in-out infinite}
        @keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.6;transform:scale(1.3)}}
        .topbar-sep{width:1px;height:22px;background:var(--border)}
        .topbar-user{display:flex;align-items:center;gap:9px;cursor:pointer}
        .topbar-user-name{font-size:.83rem;font-weight:600;color:var(--text-secondary)}
        .avatar{
            width:36px;height:36px;border-radius:50%;
            background:linear-gradient(135deg,var(--blue-400),#0ea5e9);
            display:flex;align-items:center;justify-content:center;
            font-size:.68rem;font-weight:700;color:white;
            border:2px solid var(--blue-200);flex-shrink:0;overflow:hidden;
            box-shadow:0 2px 8px rgba(37,99,235,0.2);
        }
        .avatar svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2}

        /* Main */
        .main{padding:calc(var(--header-h) + 30px) 24px 40px;max-width:1200px;margin:0 auto}
        .page-header{margin-bottom:28px;display:flex;flex-direction:column;gap:12px}
        .page-title{font-family:'DM Serif Display',serif;font-size:2rem;color:var(--text-primary);margin-bottom:6px}
        .page-subtitle{font-size:.9rem;color:var(--text-muted)}
        .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;border:none;transition:all .2s;width:fit-content}
        .btn-primary{background:var(--accent);color:#fff}
        .btn-primary:hover{background:var(--accent-hover)}
        .location-status{font-size:.8rem;color:var(--text-muted)}
        .map-container{margin-bottom:24px;border-radius:16px;overflow:hidden;box-shadow:var(--shadow-md)}

        /* Cards */
        .card{
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:var(--radius-xl);
            box-shadow:var(--shadow-card);
            overflow:hidden;
            animation:fadeSlideUp .55s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes fadeSlideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
        .card-header{
            display:flex;align-items:center;justify-content:space-between;
            padding:18px 24px 15px;
            border-bottom:1px solid var(--border-soft);
        }
        .card-title{
            font-weight:700;font-size:.95rem;color:var(--text-primary);
            display:flex;align-items:center;gap:9px;
        }
        .card-title-dot{width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0}
        .card-body{padding:0}

        /* Hospital Card */
        .hospital-card{
            display:flex;align-items:center;padding:20px 24px;
            border-bottom:1px solid var(--border-soft);
            transition:all .18s;
        }
        .hospital-card:last-child{border-bottom:none}
        .hospital-card:hover{background:var(--blue-50)}

        .hospital-icon{
            width:56px;height:56px;border-radius:12px;
            background:var(--accent-light);
            display:flex;align-items:center;justify-content:center;
            margin-right:18px;flex-shrink:0;
        }
        .hospital-icon svg{width:24px;height:24px;stroke:var(--accent);fill:none;stroke-width:1.5}

        .hospital-info{flex:1}
        .hospital-name{font-weight:700;font-size:1.05rem;color:var(--text-primary);margin-bottom:4px}
        .hospital-type{font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;font-weight:600}

        .hospital-distance{
            text-align:center;padding:0 20px;
        }
        .distance-value{
            font-family:'DM Serif Display',serif;font-size:1.5rem;color:var(--accent);
        }
        .distance-label{font-size:.7rem;color:var(--text-muted)}

        .hospital-contact{
            text-align:right;min-width:160px;
        }
        .contact-item{display:flex;align-items:center;gap:6px;font-size:.85rem;color:var(--text-secondary);margin-bottom:4px}
        .contact-item:last-child{margin-bottom:0}
        .contact-item svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2}

        /* Badge */
        .badge{
            display:inline-flex;align-items:center;
            padding:4px 12px;border-radius:100px;
            font-size:.68rem;font-weight:700;
            letter-spacing:.04em;text-transform:uppercase;
        }
        .badge-hospital{background:var(--accent-light);color:var(--accent)}
        .badge-clinic{background:var(--green-light);color:var(--green-500)}
        .badge-health-center{background:var(--purple-light);color:var(--purple-400)}
        .badge-dispensary{background:var(--amber-light);color:var(--amber-400)}

        /* Empty State */
        .empty-state{padding:60px 24px;text-align:center}
        .empty-icon{
            width:72px;height:72px;border-radius:50%;
            background:var(--accent-light);
            display:flex;align-items:center;justify-content:center;
            margin:0 auto 20px;
        }
        .empty-icon svg{width:32px;height:32px;stroke:var(--accent);fill:none;stroke-width:1.5}
        .empty-title{font-weight:700;font-size:1.1rem;color:var(--text-primary);margin-bottom:6px}
        .empty-text{font-size:.85rem;color:var(--text-muted);max-width:320px;margin:0 auto}

        /* Back Link */
        .back-link{display:inline-flex;align-items:center;gap:6px;color:var(--accent);text-decoration:none;font-size:.85rem;font-weight:500;margin-bottom:16px}
        .back-link:hover{color:var(--accent-hover)}
        .back-link svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}

        /* Responsive */
        @media (max-width:768px){
            .main{padding:calc(var(--header-h) + 16px) 16px 32px}
            .hospital-card{flex-wrap:wrap}
            .hospital-distance,.hospital-contact{width:100%;text-align:left;padding:12px 0 0}
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a href="{{ route('dashboard') }}" class="logo">
            <div class="logo-mark">
                <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div class="logo-text">Afya<span>Link</span></div>
        </a>
        <div class="topbar-spacer"></div>
        <div class="topbar-right">
            <div class="topbar-pill">
                <span class="status-dot"></span>
                Patient Portal
            </div>
            <div class="topbar-sep"></div>
            <div class="topbar-user">
                <div class="avatar">
                    <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <span class="topbar-user-name">{{ Auth::user()->first_name ?? 'Patient' }}</span>
            </div>
        </div>
    </header>

    <main class="main">
        <a href="{{ route('dashboard') }}" class="back-link">
            <svg viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Dashboard
        </a>

        <div class="page-header">
            <h1 class="page-title">Nearby Hospitals</h1>
            <p class="page-subtitle">Find healthcare facilities near your location</p>
            <button id="getLocationBtn" class="btn btn-primary" onclick="getLocation()">
                <svg viewBox="0 0 24 24" width="16" height="16"><circle cx="12" cy="12" r="3"/><path d="M12 2v4m0 12v4M2 12h4m12 0h4"/></svg>
                Use My Current Location
            </button>
            <p id="locationStatus" class="location-status"></p>
        </div>

        <!-- Map Section -->
        <div class="map-container">
            <div id="map" style="width:100%;height:400px;border-radius:12px;"></div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-title-dot"></span>
                    Healthcare Facilities
                </div>
            </div>
            <div class="card-body">
                @if($facilitiesWithDistance->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <h3 class="empty-title">No Hospitals Found</h3>
                        <p class="empty-text">There are no hospitals with location data in the system yet.</p>
                    </div>
                @else
                    @foreach($facilitiesWithDistance as $facility)
                        <div class="hospital-card">
                            <div class="hospital-icon">
                                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </div>
                            <div class="hospital-info">
                                <div class="hospital-name">{{ $facility['name'] }}</div>
                                <span class="badge badge-{{ $facility['type'] }}">{{ $facility['type'] }}</span>
                                @if($facility['county'])
                                    <span style="font-size:.75rem;color:var(--text-muted);margin-left:8px">{{ $facility['county'] }}</span>
                                @endif
                                @if($facility['working_hours'])
                                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:4px">
                                        <i class="fas fa-clock" style="margin-right:4px"></i>
                                        @php
                                            $hours = is_array($facility['working_hours']) ? $facility['working_hours'] : json_decode($facility['working_hours'], true);
                                        @endphp
                                        @if(is_array($hours) && count($hours) > 0)
                                            @foreach($hours as $day => $time)
                                                <span class="badge" style="background:#e0f2fe;color:#0369a1;margin-right:4px;padding:2px 6px;">
                                                    {{ $day }}: {{ $time }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span>Hours not available</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="hospital-distance">
                                <div class="distance-value">{{ $facility['distance'] }} km</div>
                                <div class="distance-label">Distance</div>
                            </div>
                            <div class="hospital-contact">
                                @if($facility['phone'])
                                    <div class="contact-item">
                                        <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                                        {{ $facility['phone'] }}
                                    </div>
                                @endif
                                @if($facility['email'])
                                    <div class="contact-item">
                                        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        {{ $facility['email'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('google-maps.api_key') }}&libraries=places" async defer></script>

    <script>
        // Hospital data from server
        const hospitals = @json($facilitiesWithDistance);
        let userMarker = null;
        let map = null;
        let markers = [];

        // Initialize map
        function initMap() {
            const defaultCenter = { lat: {{ $userLat }}, lng: {{ $userLng }} };
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: defaultCenter
            });

            // Add markers for all hospitals
            hospitals.forEach(hospital => {
                if (hospital.latitude && hospital.longitude) {
                    const marker = new google.maps.Marker({
                        position: { lat: parseFloat(hospital.latitude), lng: parseFloat(hospital.longitude) },
                        map: map,
                        title: hospital.name
                    });

                    // Build working hours HTML
                    let workingHoursHtml = '';
                    if (hospital.working_hours) {
                        try {
                            const hours = typeof hospital.working_hours === 'string' ? JSON.parse(hospital.working_hours) : hospital.working_hours;
                            if (hours && typeof hours === 'object') {
                                workingHoursHtml = '<br><strong>Hours:</strong> ';
                                Object.entries(hours).forEach(([day, time]) => {
                                    workingHoursHtml += `<span style="display:inline-block;margin-right:4px;background:#e0f2fe;color:#0369a1;padding:2px 6px;border-radius:4px;font-size:10px;">${day}: ${time}</span>`;
                                });
                            }
                        } catch(e) {}
                    }

                    const infoWindow = new google.maps.InfoWindow({
                        content: `
                            <div style="padding:8px;max-width:200px">
                                <strong>${hospital.name}</strong><br>
                                <span style="color:#666">${hospital.type}</span><br>
                                <span style="color:#2563eb;font-weight:600">${hospital.distance} km away</span><br>
                                ${hospital.phone ? `<span>${hospital.phone}</span><br>` : ''}
                                ${workingHoursHtml}
                            </div>
                        `
                    });

                    marker.addListener('click', () => {
                        infoWindow.open(map, marker);
                    });

                    markers.push(marker);
                }
            });
        }

        // Get user location
        function getLocation() {
            const statusEl = document.getElementById('locationStatus');
            const btn = document.getElementById('getLocationBtn');

            if (!navigator.geolocation) {
                statusEl.innerHTML = '<span style="color:#f43f5e">Geolocation is not supported by your browser</span>';
                return;
            }

            btn.disabled = true;
            btn.innerHTML = 'Getting location...';
            statusEl.innerHTML = '<span style="color:#2563eb">Requesting location access...</span>';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    statusEl.innerHTML = `<span style="color:#10b981">Location found! Searching nearby...</span>`;

                    // Update map center
                    if (map) {
                        map.setCenter({ lat, lng });
                        map.setZoom(14);
                    }

                    // Add user marker
                    if (userMarker) {
                        userMarker.setMap(null);
                    }

                    userMarker = new google.maps.Marker({
                        position: { lat, lng },
                        map: map,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 10,
                            fillColor: '#2563eb',
                            fillOpacity: 1,
                            strokeColor: '#fff',
                            strokeWeight: 2
                        },
                        title: 'Your Location'
                    });

                    // Fetch nearby hospitals with actual location
                    fetchNearbyHospitals(lat, lng);

                    btn.disabled = false;
                    btn.innerHTML = '<svg viewBox="0 0 24 24" width="16" height="16"><circle cx="12" cy="12" r="3"/><path d="M12 2v4m0 12v4M2 12h4m12 0h4"/></svg> Use My Current Location';
                },
                (error) => {
                    let msg = 'Unable to get location';
                    if (error.code === 1) msg = 'Location access denied';
                    else if (error.code === 2) msg = 'Position unavailable';
                    else if (error.code === 3) msg = 'Location request timed out';

                    statusEl.innerHTML = `<span style="color:#f43f5e">${msg}. Please enable location services.</span>`;
                    btn.disabled = false;
                    btn.innerHTML = '<svg viewBox="0 0 24 24" width="16" height="16"><circle cx="12" cy="12" r="3"/><path d="M12 2v4m0 12v4M2 12h4m12 0h4"/></svg> Use My Current Location';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }

        // Fetch nearby hospitals via AJAX
        function fetchNearbyHospitals(lat, lng) {
            const url = `/patient/nearby-hospitals?latitude=${lat}&longitude=${lng}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        // Update hospital markers
                        markers.forEach(m => m.setMap(null));
                        markers = [];

                        data.data.forEach(hospital => {
                            if (hospital.latitude && hospital.longitude) {
                                const marker = new google.maps.Marker({
                                    position: { lat: parseFloat(hospital.latitude), lng: parseFloat(hospital.longitude) },
                                    map: map,
                                    title: hospital.name
                                });

                                // Build working hours HTML
                                let workingHoursHtml = '';
                                if (hospital.working_hours) {
                                    try {
                                        const hours = typeof hospital.working_hours === 'string' ? JSON.parse(hospital.working_hours) : hospital.working_hours;
                                        if (hours && typeof hours === 'object') {
                                            workingHoursHtml = '<br><strong>Hours:</strong> ';
                                            Object.entries(hours).forEach(([day, time]) => {
                                                workingHoursHtml += `<span style="display:inline-block;margin-right:4px;background:#e0f2fe;color:#0369a1;padding:2px 6px;border-radius:4px;font-size:10px;">${day}: ${time}</span>`;
                                            });
                                        }
                                    } catch(e) {}
                                }

                                const infoWindow = new google.maps.InfoWindow({
                                    content: `
                                        <div style="padding:8px;max-width:200px">
                                            <strong>${hospital.name}</strong><br>
                                            <span style="color:#666">${hospital.type}</span><br>
                                            <span style="color:#2563eb;font-weight:600">${hospital.distance} km away</span><br>
                                            ${hospital.phone ? `<span>${hospital.phone}</span><br>` : ''}
                                            ${workingHoursHtml}
                                        </div>
                                    `
                                });

                                marker.addListener('click', () => {
                                    infoWindow.open(map, marker);
                                });

                                markers.push(marker);
                            }
                        });

                        // Update list display
                        location.reload();
                    }
                })
                .catch(err => console.error('Error fetching hospitals:', err));
        }

        // Initialize on load
        window.addEventListener('load', function() {
            if (typeof google !== 'undefined' && google.maps) {
                initMap();
            } else {
                // If Google Maps fails to load, add callback
                window.initMap = initMap;
            }
        });
    </script>
</body>
</html>
