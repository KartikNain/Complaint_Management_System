@php
    // Static placeholder data — replace with real values from the controller later.
    $stats = $stats ?? [
        'total' => 0,
        'pending' => 0,
        'in_progress' => 0,
        'resolved' => 0,
        'closed' => 0,
    ];

    $myComplaints = $myComplaints ?? [
        (object) ['id' => 1048, 'title' => 'Laptop screen flickering', 'category' => 'IT Equipment', 'priority' => 'High', 'status' => 'In Progress', 'created_human' => '3 hours ago'],
        (object) ['id' => 1045, 'title' => 'Reimbursement not credited', 'category' => 'Finance', 'priority' => 'Medium', 'status' => 'Pending', 'created_human' => 'Yesterday'],
        (object) ['id' => 1040, 'title' => 'ID card not printed', 'category' => 'HR', 'priority' => 'Low', 'status' => 'Resolved', 'created_human' => '3 days ago'],
        (object) ['id' => 1033, 'title' => 'Access card not working', 'category' => 'Facilities', 'priority' => 'Medium', 'status' => 'Closed', 'created_human' => '1 week ago'],
    ];

    $statusBadge = function ($status) {
    return match ($status) {
        'Pending' => 'warning',
        'Assigned' => 'info',
        'In Progress' => 'primary',
        'Resolved' => 'success',
        'Reopened' => 'danger',
        default => 'secondary',
    };
};

$trackStages = ['Pending', 'Assigned', 'In Progress', 'Resolved', 'Closed'];

$latestStatusIndex = count($myComplaints)
    ? array_search($myComplaints[0]['status'], $trackStages)
    : false;
@endphp

{{-- Stat tiles --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#e2e8f0;color:#1e293b;"><i class="bi bi-card-checklist"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">My Complaints</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-arrow-repeat"></i></div>
            <div>
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-value">{{ $stats['resolved'] }}</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#e2e8f0;color:#475569;"><i class="bi bi-archive"></i></div>
            <div>
                <div class="stat-value">{{ $stats['closed'] }}</div>
                <div class="stat-label">Closed</div>
            </div>
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <a href="{{ route('employee.complaints.create') }}"
        data-url="{{ route('employee.complaints.create') }}"
        data-title="Submit Complaint"
        class="quick-action-card complaint-view-link">
            <div class="qa-icon"><i class="bi bi-plus-circle"></i></div>
            <div>
                <h6 class="mb-0">Submit a Complaint</h6>
                <small class="text-muted">Report a new issue</small>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('employee.complaints.index') }}"
            data-url="{{ route('employee.complaints.index') }}"
            data-title="My Complaints"
            class="quick-action-card complaint-view-link">
                <div class="qa-icon"><i class="bi bi-card-list"></i></div>
                <div>
                    <h6 class="mb-0">View All My Complaints</h6>
                    <small class="text-muted">Track status and history</small>
                </div>
            </a>
    </div>
</div>

{{-- Latest complaint progress --}}
@if($myComplaints->count())
<div class="panel mb-4">
    @php $latest = $myComplaints->first(); @endphp
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h6 class="panel-title mb-1">Latest Complaint — #{{ $latest->id }}</h6>
            <small class="text-muted">{{ $latest->title }}</small>
        </div>
        <span class="badge bg-{{ $statusBadge($latest->status) }}">{{ $latest->status }}</span>
    </div>

    <div class="status-track">
        @foreach($trackStages as $i => $stage)
            <div class="step {{ $latestStatusIndex !== false && $i < $latestStatusIndex ? 'done' : ($i === $latestStatusIndex ? 'current' : '') }}">
                <div class="dot"></div>
                {{ $stage }}
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Complaints table --}}
<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="panel-title">My Recent Complaints</h6>
        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
    </div>

    <div class="table-responsive-wrap">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

                @forelse($myComplaints as $complaint)
                <tr>
                    <td>#{{ $complaint->id }}</td>
                    <td>{{ $complaint->title }}</td>
                    <td>{{ $complaint->category }}</td>
                    <td>
                        <span class="badge bg-{{ $complaint->priority == 'High' ? 'danger' : ($complaint->priority == 'Medium' ? 'warning' : 'secondary') }}">
                            {{ $complaint->priority }}
                        </span>
                    </td>
                    <td><span class="badge bg-{{ $statusBadge($complaint->status) }}">{{ $complaint->status }}</span></td>
                    <td>{{ $complaint->created_at->diffForHumans() }}</td>
                    <td class="text-end">
                        <a href="{{ route('employee.complaints.show', $complaint->id) }}"
                        data-url="{{ route('employee.complaints.show', $complaint->id) }}"
                        data-title="Complaint #{{ $complaint->id }}"
                        class="btn btn-sm btn-outline-primary complaint-view-link">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">You have not submitted any complaints yet.</td></tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>