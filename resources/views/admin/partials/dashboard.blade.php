{{-- Stat tiles --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#e2e8f0;color:#1e293b;"><i class="bi bi-card-checklist"></i></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Complaints</div></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending</div></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-arrow-repeat"></i></div>
            <div><div class="stat-value">{{ $stats['in_progress'] }}</div><div class="stat-label">In Progress</div></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-check2-circle"></i></div>
            <div><div class="stat-value">{{ $stats['resolved'] }}</div><div class="stat-label">Resolved</div></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#e2e8f0;color:#475569;"><i class="bi bi-archive"></i></div>
            <div><div class="stat-value">{{ $stats['closed'] }}</div><div class="stat-label">Closed</div></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#ede9fe;color:#6d28d9;"><i class="bi bi-people"></i></div>
            <div><div class="stat-value">{{ $stats['users'] }}</div><div class="stat-label">Total Users</div></div>
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('admin.complaints.index') }}"
           data-url="{{ route('admin.complaints.index') }}"
           data-title="Manage Complaints"
           class="quick-action-card complaint-view-link">
            <div class="qa-icon"><i class="bi bi-card-checklist"></i></div>
            <div><h6 class="mb-0">Manage Complaints</h6><small class="text-muted">View, assign and track</small></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="#" class="quick-action-card disabled" tabindex="-1" aria-disabled="true">
            <div class="qa-icon"><i class="bi bi-people"></i></div>
            <div><h6 class="mb-0">Manage Users</h6><small class="text-muted">Coming soon</small></div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="#" class="quick-action-card disabled" tabindex="-1" aria-disabled="true">
            <div class="qa-icon"><i class="bi bi-bar-chart"></i></div>
            <div><h6 class="mb-0">Reports</h6><small class="text-muted">Coming soon</small></div>
        </a>
    </div>
</div>

{{-- Pending complaints --}}
<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="panel-title">Pending Complaints (Needs Assignment)</h6>
        <a href="{{ route('admin.complaints.index') }}?status=Pending"
           data-url="{{ route('admin.complaints.index') }}?status=Pending"
           data-title="Manage Complaints"
           class="btn btn-sm btn-outline-primary complaint-view-link">
            View All
        </a>
    </div>

    <div class="table-responsive-wrap">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th><th>Title</th><th>Category</th><th>Priority</th><th>Submitted By</th><th>Created</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingComplaints as $complaint)
                <tr>
                    <td>#{{ $complaint->id }}</td>
                    <td>{{ $complaint->title }}</td>
                    <td>{{ $complaint->category }}</td>
                    <td>
                        <span class="badge bg-{{ $complaint->priority == 'High' ? 'danger' : ($complaint->priority == 'Medium' ? 'warning' : 'secondary') }}">
                            {{ $complaint->priority }}
                        </span>
                    </td>
                    <td>{{ $complaint->user->name ?? 'N/A' }}</td>
                    <td>{{ $complaint->created_at->diffForHumans() }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}"
                           data-url="{{ route('admin.complaints.show', $complaint->id) }}"
                           data-title="Complaint #{{ $complaint->id }}"
                           class="btn btn-sm btn-outline-primary complaint-view-link">
                            Assign
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No pending complaints found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>