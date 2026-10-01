@php
    $statusBadge = function ($status) {
        return match ($status) {
            'Assigned' => 'info',
            'In Progress' => 'primary',
            'Resolved' => 'success',
            'Reopened' => 'danger',
            default => 'secondary',
        };
    };
@endphp

{{-- Stat tiles --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#e2e8f0;color:#1e293b;"><i class="bi bi-card-checklist"></i></div>
            <div>
                <div class="stat-value">{{ $stats['assigned'] }}</div>
                <div class="stat-label">Assigned to Me</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-eye"></i></div>
            <div>
                <div class="stat-value">{{ $stats['awaiting_review'] }}</div>
                <div class="stat-label">Awaiting Review</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><i class="bi bi-arrow-repeat"></i></div>
            <div>
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-tile">
            <div class="stat-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-value">{{ $stats['resolved'] }}</div>
                <div class="stat-label">Resolved by Me</div>
            </div>
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <a href="{{ route('staff.complaints.index') }}"
           data-url="{{ route('staff.complaints.index') }}"
           data-title="Assigned Complaints"
           class="quick-action-card complaint-view-link">
            <div class="qa-icon"><i class="bi bi-card-checklist"></i></div>
            <div>
                <h6 class="mb-0">View Assigned Complaints</h6>
                <small class="text-muted">Review and update status</small>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('staff.conversations.index') }}"
           data-url="{{ route('staff.conversations.index') }}"
           data-title="Conversations"
           class="quick-action-card complaint-view-link">
            <div class="qa-icon"><i class="bi bi-chat-left-text"></i></div>
            <div>
                <h6 class="mb-0">Conversations</h6>
                <small class="text-muted">Reply to users awaiting response</small>
            </div>
        </a>
    </div>
</div>

{{-- Assigned complaints table --}}
<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="panel-title">Assigned Complaints</h6>
        <a href="{{ route('staff.complaints.index') }}"
           data-url="{{ route('staff.complaints.index') }}"
           data-title="Assigned Complaints"
           class="btn btn-sm btn-outline-primary complaint-view-link">
            View All
        </a>
    </div>

    <div class="table-responsive-wrap">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th><th>Title</th><th>Category</th><th>Priority</th><th>Status</th><th>Submitted By</th><th>Created</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignedComplaints as $complaint)
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
                    <td>{{ $complaint->user->name ?? '-' }}</td>
                    <td>{{ $complaint->created_at->diffForHumans() }}</td>
                    <td class="text-end">
                        <a href="{{ route('staff.complaints.show', $complaint->id) }}"
                           data-url="{{ route('staff.complaints.show', $complaint->id) }}"
                           data-title="Complaint #{{ $complaint->id }}"
                           class="btn btn-sm btn-outline-primary complaint-view-link">
                            Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No complaints assigned to you yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>