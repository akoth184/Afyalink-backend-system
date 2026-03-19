<!DOCTYPE html>
<!-- cspell:ignore Afya AfyaLink -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Facility Applications - AfyaLink Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --background: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--background); color: var(--text-primary); }

        .admin-container { max-width: 1200px; margin: 0 auto; padding: 20px; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .page-title { font-family: 'DM Serif Display', serif; font-size: 1.8rem; color: var(--text-primary); }
        .page-subtitle { color: var(--text-secondary); margin-top: 5px; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            background: white;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        .back-link:hover { background: var(--surface-2); }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border);
        }
        .stat-label { font-size: 0.85rem; color: var(--text-secondary); }
        .stat-value { font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-top: 5px; }

        .card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title { font-weight: 600; font-size: 1.1rem; }
        .card-body { padding: 0; }

        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 16px 20px; text-align: left; border-bottom: 1px solid var(--border); }
        .table th { background: var(--surface-2); font-weight: 600; font-size: 0.85rem; color: var(--text-secondary); }
        .table tr:last-child td { border-bottom: none; }
        .table tr:hover { background: var(--surface-2); }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-pending { background: #fef3c7; color: #d97706; }
        .badge-approved { background: #d1fae5; color: #059669; }

        .action-btns { display: flex; gap: 8px; }
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-outline { background: white; border: 1px solid var(--border); color: var(--text-primary); }
        .btn-outline:hover { background: var(--surface-2); }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }
        .empty-state svg { width: 64px; height: 64px; margin-bottom: 16px; opacity: 0.5; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Pending Facility Applications</h1>
                <p class="page-subtitle">Review and verify hospital/facility registration requests</p>
            </div>
            <a href="{{ route('dashboard') }}" class="back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Pending Applications</div>
                <div class="stat-value">{{ $pendingFacilities->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">This Week</div>
                <div class="stat-value">{{ $pendingFacilities->where('created_at', '>=', now()->subWeek())->count() }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Facility Applications</span>
            </div>
            <div class="card-body">
                @if($pendingFacilities->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Facility Name</th>
                            <th>Type</th>
                            <th>County</th>
                            <th>Email</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingFacilities as $facility)
                        <tr>
                            <td><strong>{{ $facility->name }}</strong></td>
                            <td>{{ $facility->type }}</td>
                            <td>{{ $facility->county }}</td>
                            <td>{{ $facility->email }}</td>
                            <td>{{ $facility->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-btns">
                                    <form method="POST" action="{{ route('admin.facility.approve', $facility->id) }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.facility.reject', $facility->id) }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <p>No pending facility applications</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
