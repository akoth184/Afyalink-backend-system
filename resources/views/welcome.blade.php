<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AfyaLink – Digital Patient Referral & Health Record System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-teal-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">AfyaLink</span>
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#" class="text-gray-600 hover:text-teal-600 font-medium transition">Home</a>
                    <a href="#about" class="text-gray-600 hover:text-teal-600 font-medium transition">About</a>
                    <a href="#users" class="text-gray-600 hover:text-teal-600 font-medium transition">Users</a>
                    <a href="#features" class="text-gray-600 hover:text-teal-600 font-medium transition">Features</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-teal-600 font-medium px-4 py-2 transition">
                        Patient Login
                    </a>
                    <a href="{{ route('professional.portal') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-medium px-4 py-2 rounded-lg transition">
                        Professional Portal
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Column - Text -->
                <div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                        AfyaLink<br>
                        <span class="text-teal-600">Digital Patient Referral & Health Record Platform</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-xl">
                        Connect patients, doctors, and healthcare facilities across Kenya with secure digital medical records and seamless referrals. Streamline healthcare delivery with our integrated platform.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-lg text-teal-600 bg-teal-50 hover:bg-teal-100 transition">
                            Patient Login
                        </a>
                        <a href="{{ route('register', ['role' => 'patient']) }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-teal-600 hover:bg-teal-700 transition">
                            Patient Registration
                        </a>
                        <a href="{{ route('professional.portal') }}" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                            Professional Portal
                        </a>
                    </div>
                </div>

                <!-- Right Column - Dashboard Illustration -->
                <div class="hidden lg:block">
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <!-- Dashboard Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-900">AfyaLink Dashboard</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="text-sm text-gray-500">Live</span>
                            </div>
                        </div>

                        <!-- Dashboard Cards -->
                        <div class="space-y-4">
                            <!-- Card 1 -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm">Patient Records</h4>
                                        <p class="text-xs text-gray-500 mt-1">Secure digital medical history</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm">Hospital Referrals</h4>
                                        <p class="text-xs text-gray-500 mt-1">Track referrals between facilities</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 text-sm">Connected Hospitals</h4>
                                        <p class="text-xs text-gray-500 mt-1">Network of healthcare facilities</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About AfyaLink Section -->
    <section id="about" class="py-20 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">About AfyaLink</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    AfyaLink is Kenya's connected healthcare platform, enabling seamless coordination between patients, doctors, and healthcare facilities.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center p-6">
                    <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Digital Medical Records</h3>
                    <p class="text-gray-600">Access patient health records securely from any connected facility. Complete medical history available at your fingertips.</p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center p-6">
                    <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Faster Hospital Referrals</h3>
                    <p class="text-gray-600">Streamlined digital referral system connecting healthcare facilities across Kenya. Reduce paperwork and wait times.</p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center p-6">
                    <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Connected Healthcare Facilities</h3>
                    <p class="text-gray-600">Network of healthcare facilities across Kenya. Coordinated care regardless of location or facility.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- System Users Section -->
    <section id="users" class="py-20 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Who Uses AfyaLink?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Our platform serves three main user groups across Kenya's healthcare ecosystem.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Patient Card -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Patients</h3>
                    <p class="text-gray-600 mb-6">
                        Access your medical records, manage appointments, and track referrals. Your health information, securely in your hands.
                    </p>
                    <a href="{{ route('login') }}" class="inline-flex items-center text-teal-600 font-medium hover:text-teal-700 transition">
                        Patient Login
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <!-- Doctor Card -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Doctors</h3>
                    <p class="text-gray-600 mb-6">
                        Access patient records, create referrals, and manage patient care. Connected to hospitals and patients nationwide.
                    </p>
                    <a href="{{ route('doctor.login') }}" class="inline-flex items-center text-teal-600 font-medium hover:text-teal-700 transition">
                        Doctor Portal
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <!-- Hospital Card -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Hospitals</h3>
                    <p class="text-gray-600 mb-6">
                        Manage facility operations, coordinate referrals, and connect with doctors and patients. Streamlined hospital management.
                    </p>
                    <a href="{{ route('hospital.login') }}" class="inline-flex items-center text-teal-600 font-medium hover:text-teal-700 transition">
                        Hospital Portal
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('admin.login') }}" class="inline-flex items-center text-gray-500 hover:text-teal-600 font-medium transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Admin Login
                </a>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section id="features" class="py-20 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Key Features</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Essential tools for modern healthcare delivery in Kenya.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Digital Medical Records</h3>
                    <p class="text-gray-600">
                        Secure, comprehensive electronic health records accessible across all connected healthcare facilities in Kenya.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Referral System</h3>
                    <p class="text-gray-600">
                        Digital referrals with real-time tracking. From submission to acceptance, manage patient transfers seamlessly.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Hospital Network</h3>
                    <p class="text-gray-600">
                        Connected network of healthcare facilities across Kenya. Coordinate care between hospitals, clinics, and health centers.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-12 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <h3 class="text-2xl font-bold text-white mb-2">AfyaLink</h3>
            <p class="text-gray-400 mb-4">Digital Patient Referral System</p>
            <p class="text-gray-500 text-sm">
                © 2026 AfyaLink. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
