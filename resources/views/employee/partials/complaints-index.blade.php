<div class="panel">

    <div class="mb-4">
        <h6 class="panel-title mb-1">My Complaints</h6>
        <small class="text-muted">Complaints you have submitted. Track status and reply to staff.</small>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold">{{ $counts['total'] }}</div>
                <small class="text-muted">Total</small>
            </div>
        </div>
        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold text-warning">{{ $counts['pending'] }}</div>
                <small class="text-muted">Pending</small>
            </div>
        </div>
        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold text-primary">{{ $counts['in_progress'] }}</div>
                <small class="text-muted">In Progress</small>
            </div>
        </div>
        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold text-success">{{ $counts['resolved'] }}</div>
                <small class="text-muted">Resolved</small>
            </div>
        </div>
        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold text-secondary">{{ $counts['closed'] }}</div>
                <small class="text-muted">Closed</small>
            </div>
        </div>
    </div>

    <form id="employeeComplaintFilterForm" data-url="{{ route('employee.complaints.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" id="employeeComplaintSearch" class="form-control"
                   placeholder="Search title / category..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" id="employeeComplaintStatus" class="form-select">
                <option value="">All Status</option>
                @foreach (['Pending','Assigned','In Progress','Resolved','Closed','Reopened'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="button"
                    class="reset-complaint-filters btn btn-secondary w-100">
                <i class="bi bi-x-lg"></i> Reset
            </button>
        </div>
    </form>

    <div id="employeeComplaintsTableContainer">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Department</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                    <tr>
                        <td>#{{ $complaint->id }}</td>
                        <td>{{ $complaint->title }}</td>
                        <td>{{ $complaint->department }}</td>
                        <td>
                            @php
                            $priorityColor = match($complaint->priority) {
                                'High' => 'danger', 'Medium' => 'warning', default => 'secondary',
                            };
                            @endphp
                            <span class="badge bg-{{ $priorityColor }}">{{ $complaint->priority }}</span>
                        </td>
                        <td>
                            @php
                            $statusColor = match($complaint->status) {
                                'Assigned' => 'info', 'In Progress' => 'primary',
                                'Resolved' => 'success', 'Closed' => 'secondary',
                                'Reopened' => 'danger', default => 'light',
                            };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ $complaint->status }}</span>
                        </td>
                        <td>{{ $complaint->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('employee.complaints.show', $complaint->id) }}"
                               data-url="{{ route('employee.complaints.show', $complaint->id) }}"
                               data-title="Complaint #{{ $complaint->id }}"
                               class="btn btn-sm btn-outline-dark complaint-view-link">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">You haven't submitted any complaints yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $complaints->links() }}</div>
    </div>

</div>