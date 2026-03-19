<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard — AfyaLink</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal: #0d6e6e; --teal-mid: #0f8080; --teal-lt: #e6f4f4;
            --green: #22a85a; --amber: #e07b1a; --red: #e53e3e;
            --ink: #1a1f2e; --muted: #5a6275; --border: #dde4e4;
            --cream: #f7f5f1;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--cream); color: var(--ink); }

        /* Header */
        header { background: white; border-bottom: 1px solid var(--border); padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .logo { display: flex; align-items: center; gap: 10px; }
        .logo-mark { width: 36px; height: 36px; background: var(--teal); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .logo-mark svg { width: 18px; height: 18px; fill: white; }
        .logo-text { font-weight: 700; font-size: 1.2rem; }
        .logo-text span { color: var(--teal); }
        .user-info { display: flex; align-items: center; gap: 16px; }
        .user-name { font-weight: 600; font-size: 0.9rem; }
        .logout-btn { padding: 8px 16px; background: transparent; border: 1px solid var(--border); border-radius: 8px; color: var(--muted); font-size: 0.85rem; cursor: pointer; transition: all .2s; }
        .logout-btn:hover { border-color: var(--teal); color: var(--teal); }

        /* Main */
        .main { padding: 32px; max-width: 1200px; margin: 0 auto; }
        .page-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 8px; }
        .page-subtitle { color: var(--muted); margin-bottom: 32px; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; border-radius: 12px; padding: 24px; border: 1px solid var(--border); }
        .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .stat-icon.teal { background: var(--teal-lt); }
        .stat-icon.green { background: #e6f8ee; }
        .stat-icon.amber { background: #fef3e4; }
        .stat-icon.red { background: #fff5f5; }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-value { font-size: 1.8rem; font-weight: 700; }
        .stat-label { font-size: 0.85rem; color: var(--muted); }

        /* Section */
        .section { background: white; border-radius: 12px; border: 1px solid var(--border); padding: 24px; margin-bottom: 24px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-title { font-size: 1.1rem; font-weight: 600; }
        .btn { padding: 10px 20px; background: var(--teal); color: white; border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: var(--teal-mid); }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 16px; font-size: 0.75rem; font-weight: 600; color: var(--muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 14px 16px; font-size: 0.9rem; border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .status-pending { background: #fef3e4; color: #e07b1a; }
        .status-accepted { background: #e6f8ee; color: #22a85a; }
        .status-completed { background: var(--teal-lt); color: var(--teal); }
        .status-inactive { background: #fff5f5; color: #e53e3e; }

        /* Action buttons */
        .action-btns { display: flex; gap: 8px; }
        .btn-approve { padding: 6px 12px; background: #22a85a; color: white; border: none; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; }
        .btn-approve:hover { background: #1e9650; }
        .btn-reject { padding: 6px 12px; background: #e53e3e; color: white; border: none; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; }
        .btn-reject:hover { background: #c53030; }

        @media (max-width: 960px) { .stats-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } .main { padding: 20px; } }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <div class="logo-mark">
                <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
            </div>
            <span class="logo-text">Afya<span>Link</span> <small style="font-weight: 400; font-size: 0.85rem; color: var(--muted);">Admin</small></span>
        </div>
        <div class="user-info">
            <span class="user-name">{{ $user->first_name }} {{ $user->last_name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <main class="main">
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">System overview and management</p>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0d6e6e" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <div class="stat-value">{{ $stats['total_patients'] }}</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#22a85a" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r=\"4\"/><path d=\"M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75\"/></svg>
                </div>
                <div class="stat-value">{{ $stats['total_doctors'] }}</div>
                <div class="stat-label">Total Doctors</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#e07b1a" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z\"/><polyline points=\"9 22 9 12 15 12 15 22\"/></svg>
                </div>
                <div class="stat-value">{{ $stats['total_hospitals'] }}</div>
                <div class="stat-label">Total Facilities</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0d6e6e" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="stat-value">{{ $stats['total_referrals'] }}</div>
                <div class="stat-label">Total Referrals</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#e07b1a" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <div class="stat-value">{{ $stats['pending_referrals'] }}</div>
                <div class="stat-label">Pending Referrals</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#22a85a" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14\"/><path d="M22 4L12 14.01l-3-3"/></svg>
                </div>
                <div class="stat-value">{{ $stats['completed_referrals'] }}</div>
                <div class="stat-label">Completed Referrals</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#0d6e6e" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2\"/><circle cx="9" cy="7" r=\"4\"/><path d=\"M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75\"/></svg>
                </div>
                <div class="stat-value">{{ $stats['total_staff'] }}</div>
                <div class="stat-label">Total Staff</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#22a85a" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2\"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div class="stat-value">{{ $stats['active_users'] }}</div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Quick Actions</h2>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('patients.index') }}" class="btn">View Patients</a>
                <a href="{{ route('facilities.index') }}" class="btn">Manage Facilities</a>
                <a href="{{ route('referrals.index') }}" class="btn">View Referrals</a>
                <a href="{{ route('records.index') }}" class="btn">Medical Records</a>
            </div>
        </div>

        <!-- Pending Doctor Applications Section -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Pending Doctor Applications</h2>
                <span style="background: #fef3e4; color: #e07b1a; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                    {{ $pendingDoctorApplications->count() }} Pending
                </span>
            </div>
            @if($pendingDoctorApplications->isEmpty())
                <div style="text-align: center; padding: 40px; color: var(--muted);">
                    <p>No pending doctor applications</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                            <th>License Number</th>
                            <th>Specialization</th>
                            <th>Application Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingDoctorApplications as $doctor)
                        <tr>
                            <td>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</td>
                            <td>{{ $doctor->license_number ?? 'N/A' }}</td>
                            <td>{{ $doctor->specialization ?? 'N/A' }}</td>
                            <td>{{ $doctor->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-btns">
                                    <form method="POST" action="{{ route('admin.doctor.approve', $doctor->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-approve">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.doctor.reject', $doctor->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-reject" onclick="return confirm('Are you sure you want to reject this doctor?')">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Recent Patients</h2>
                <a href="{{ route('patients.index') }}" class="btn" style="padding: 6px 14px; font-size: 0.8rem;">View All</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient #</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Facility</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_patients as $patient)
                    <tr>
                        <td>{{ $patient->patient_id ?? 'N/A' }}</td>
                        <td>{{ $patient->patient_number }}</td>
                        <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                        <td>{{ $patient->phone }}</td>
                        <td>{{ $patient->facility->name ?? 'N/A' }}</td>
                        <td>{{ $patient->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; color: var(--muted);">No patients yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Recent Referrals</h2>
                <a href="{{ route('referrals.index') }}" class="btn" style="padding: 6px 14px; font-size: 0.8rem;">View All</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Referral #</th>
                        <th>Patient</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_referrals as $referral)
                    <tr>
                        <td>REF-{{ str_pad($referral->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $referral->patient->first_name }} {{ $referral->patient->last_name }}</td>
                        <td>{{ $referral->fromFacility->name ?? 'N/A' }}</td>
                        <td>{{ $referral->toFacility->name ?? 'N/A' }}</td>
                        <td>
                            <span class="status-badge status-{{ $referral->status }}">
                                {{ ucfirst($referral->status) }}
                            </span>
                        </td>
                        <td>{{ $referral->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; color: var(--muted);">No referrals yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
