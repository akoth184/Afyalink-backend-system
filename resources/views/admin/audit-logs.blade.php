<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Audit Logs — AfyaLink</title>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{font-family:'Inter',sans-serif;}
.slink{display:block;padding:10px 20px;font-size:13px;color:rgba(255,255,255,.55);text-decoration:none;border-left:3px solid transparent;cursor:pointer;transition:all .15s;}
.slink:hover{color:rgba(255,255,255,.85);background:rgba(255,255,255,.05);}
.slink.on{color:white;background:rgba(59,130,246,.2);border-left-color:#3b82f6;}
.section{display:none;}
.section.active{display:block;}
.badge-action{background:#f0fdf4;color:#15803d;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-view{background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-delete{background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-update{background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.badge-login{background:#f5f3ff;color:#7c3aed;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
#sidebar{width:220px;background:#1e3a5f;position:fixed;top:0;bottom:0;left:0;z-index:400;display:flex;flex-direction:column;}
</style>
</head>
<body style="background:#f0f6ff;font-family:'Inter',sans-serif;">
<div style="display:flex;min-height:100vh;">

<!-- SIDEBAR -->
<aside id="sidebar" style="width:220px;background:#1e3a5f;flex-shrink:0;display:flex;flex-direction:column;position:fixed;top:0;bottom:0;left:0;overflow-y:auto;">
  <div style="padding:20px;border-bottom:1px solid rgba(255,255,255,.1);">
    <div style="font-size:16px;font-weight:700;color:white;">AfyaLink</div>
    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;">Admin Portal</div>
  </div>
  <nav style="flex:1;padding:8px 0;">
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="slink">Dashboard</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Verification</div>
    <a href="{{ route('admin.dashboard') }}" class="slink">Doctor Applications</a>
    <a href="{{ route('admin.dashboard') }}" class="slink">Hospital Applications</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">Management</div>
    <a href="{{ route('admin.dashboard') }}" class="slink">Manage Users</a>
    <a href="{{ route('admin.dashboard') }}" class="slink">All Referrals</a>
    <a href="{{ route('admin.dashboard') }}" class="slink">Facilities</a>
    <div style="font-size:10px;color:rgba(255,255,255,.25);padding:12px 20px 5px;text-transform:uppercase;letter-spacing:.07em;">System</div>
    <a href="{{ route('admin.dashboard') }}" class="slink">System Reports</a>
    <a href="#" class="slink on" style="background:rgba(59,130,246,.2);border-left-color:#3b82f6;">Audit Logs</a>
  </nav>
  <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,.08);">
    <form method="POST" action="{{ route('logout') }}">@csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.55);font-size:13px;cursor:pointer;font-family:inherit;">Sign Out</button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<div style="margin-left:220px;flex:1;">
  <div style="background:white;padding:16px 28px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
    <div>
      <div style="font-size:20px;font-weight:700;color:#0f172a;">Audit Logs</div>
      <div style="font-size:12px;color:#94a3b8;margin-top:3px;">System activity and change history</div>
    </div>
    <div style="font-size:12px;color:#64748b;">
      Total Records: <strong>{{ $logs->total() }}</strong>
    </div>
  </div>

  <div style="padding:24px 28px;">
    <!-- FILTERS -->
    <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;margin-bottom:20px;">
      <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:200px;">
          <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Search</label>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;outline:none;">
        </div>
        <div style="min-width:150px;">
          <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Action Type</label>
          <select name="action" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;outline:none;">
            <option value="">All Actions</option>
            @foreach($actions as $action)
            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
            @endforeach
          </select>
        </div>
        <div style="min-width:150px;">
          <label style="font-size:11px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Entity Type</label>
          <select name="entity_type" style="width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;outline:none;">
            <option value="">All Types</option>
            @foreach($entityTypes as $type)
            <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" style="background:#2563eb;color:white;border:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">Filter</button>
        <a href="{{ route('admin.audit-logs') }}" style="background:#f1f5f9;color:#64748b;border:none;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;display:inline-block;">Clear</a>
      </form>
    </div>

    <!-- STATS -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
      <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Total Logs</div>
        <div style="font-size:24px;font-weight:700;color:#0f172a;">{{ $logs->total() }}</div>
      </div>
      <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Logins</div>
        <div style="font-size:24px;font-weight:700;color:#7c3aed;">{{ \App\Models\AuditLog::where('action','login')->count() }}</div>
      </div>
      <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Updates</div>
        <div style="font-size:24px;font-weight:700;color:#d97706;">{{ \App\Models\AuditLog::where('action','updated')->count() }}</div>
      </div>
      <div style="background:white;border-radius:10px;padding:16px;border:1px solid #e2e8f0;">
        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Creates</div>
        <div style="font-size:24px;font-weight:700;color:#16a34a;">{{ \App\Models\AuditLog::where('action','created')->count() }}</div>
      </div>
    </div>

    <!-- LOGS TABLE -->
    <div style="background:white;border-radius:10px;padding:20px;border:1px solid #e2e8f0;">
      <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
          <tr style="border-bottom:2px solid #f1f5f9;">
            <th style="text-align:left;padding:10px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Time</th>
            <th style="text-align:left;padding:10px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">User</th>
            <th style="text-align:left;padding:10px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Action</th>
            <th style="text-align:left;padding:10px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Entity</th>
            <th style="text-align:left;padding:10px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">Description</th>
            <th style="text-align:left;padding:10px 0;color:#94a3b8;font-size:10px;text-transform:uppercase;">IP Address</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
          <tr style="border-bottom:1px solid #f1f5f9;">
            <td style="padding:12px 0;color:#64748b;font-size:12px;white-space:nowrap;">{{ $log->created_at->format('d M Y, h:i A') }}</td>
            <td style="padding:12px 0;">
              @if($log->user)
              <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:50%;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;">{{ strtoupper(substr($log->user->first_name ?? 'U',0,1)) }}</div>
                <span style="font-weight:600;color:#0f172a;">{{ $log->user->first_name }} {{ $log->user->last_name }}</span>
              </div>
              @else
              <span style="color:#94a3b8;">System</span>
              @endif
            </td>
            <td style="padding:12px 0;">
              <span class="badge-{{ $log->action }}">{{ ucfirst($log->action) }}</span>
            </td>
            <td style="padding:12px 0;color:#64748b;">
              @if($log->entity_type)
              <span style="background:#f1f5f9;padding:3px 8px;border-radius:4px;font-size:11px;">{{ $log->entity_type }} #{{ $log->entity_id }}</span>
              @else
              —
              @endif
            </td>
            <td style="padding:12px 0;color:#0f172a;font-size:12px;max-width:300px;">{{ $log->description ?? '—' }}</td>
            <td style="padding:12px 0;color:#94a3b8;font-size:12px;">{{ $log->ip_address ?? '—' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="padding:40px;text-align:center;color:#94a3b8;">No audit logs found</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <!-- PAGINATION -->
      @if($logs->hasPages())
      <div style="display:flex;justify-content:center;gap:8px;margin-top:20px;">
        @if($logs->previousPageUrl())
        <a href="{{ $logs->previousPageUrl() }}" style="background:#f1f5f9;color:#64748b;padding:8px 14px;border-radius:6px;font-size:13px;text-decoration:none;">Previous</a>
        @endif
        <span style="background:#f1f5f9;color:#64748b;padding:8px 14px;border-radius:6px;font-size:13px;">Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }}</span>
        @if($logs->nextPageUrl())
        <a href="{{ $logs->nextPageUrl() }}" style="background:#f1f5f9;color:#64748b;padding:8px 14px;border-radius:6px;font-size:13px;text-decoration:none;">Next</a>
        @endif
      </div>
      @endif
    </div>
  </div>
</div>
</body>
</html>
