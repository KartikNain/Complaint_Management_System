<div class="panel">

    <div class="mb-4">
        <h6 class="panel-title mb-1">Assigned Complaints</h6>
        <small class="text-muted">
            Complaints assigned to you. Review, update status and communicate with the employee.
        </small>
    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold text-info">
                    {{ $counts['assigned'] }}
                </div>
                <small class="text-muted">Assigned</small>
            </div>
        </div>

        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold text-primary">
                    {{ $counts['in_progress'] }}
                </div>
                <small class="text-muted">In Progress</small>
            </div>
        </div>

        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold text-success">
                    {{ $counts['resolved'] }}
                </div>
                <small class="text-muted">Resolved</small>
            </div>
        </div>

        <div class="col-md">
            <div class="border rounded p-3 text-center">
                <div class="fs-4 fw-bold text-danger">
                    {{ $counts['reopened'] }}
                </div>
                <small class="text-muted">Reopened</small>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <form id="staffComplaintFilterForm"
      data-url="{{ route('staff.complaints.index') }}"
      class="complaint-filter-form row g-2 mb-3">

    <div class="col-md-4">
        <input type="text"
               name="search"
               id="staffComplaintSearch"
               class="form-control"
               placeholder="Search title / employee..."
               value="{{ request('search') }}">
    </div>

    <div class="col-md-3">
        <select name="status"
                id="staffComplaintStatus"
                class="form-select">

            <option value="">All Status</option>

            @foreach(['Assigned', 'In Progress', 'Resolved', 'Reopened'] as $status)
                <option value="{{ $status }}"
                    {{ request('status') == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach

        </select>
    </div>

    <div class="col-md-3">
        <select name="priority"
                id="staffComplaintPriority"
                class="form-select">

            <option value="">All Priority</option>

            @foreach(['Low', 'Medium', 'High'] as $priority)
                <option value="{{ $priority }}"
                    {{ request('priority') == $priority ? 'selected' : '' }}>
                    {{ $priority }}
                </option>
            @endforeach

        </select>
    </div>

    <div class="col-md-2">
        <button type="button"
                class="reset-complaint-filters btn btn-secondary w-100">
            <i class="bi bi-x-lg"></i> Reset
        </button>
    </div>

</form>


    {{-- IMPORTANT: Only this section will be replaced by AJAX filter --}}
    <div id="staffComplaintsTableContainer" class="complaint-table-container">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Employee</th>
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

                        <td>{{ $complaint->user->name ?? 'N/A' }}</td>

                        <td>{{ $complaint->department }}</td>

                        <td>

                            @php
                            $priorityColor = match($complaint->priority) {
                                'High' => 'danger',
                                'Medium' => 'warning',
                                default => 'secondary',
                            };
                            @endphp

                            <span class="badge bg-{{ $priorityColor }}">
                                {{ $complaint->priority }}
                            </span>

                        </td>

                        <td>

                            @php
                            $statusColor = match($complaint->status) {
                                'Assigned' => 'info',
                                'In Progress' => 'primary',
                                'Resolved' => 'success',
                                'Reopened' => 'danger',
                                default => 'light',
                            };
                            @endphp

                            <span class="badge bg-{{ $statusColor }}">
                                {{ $complaint->status }}
                            </span>

                        </td>

                        <td>{{ $complaint->created_at->format('d M Y') }}</td>

                        <td class="text-end">

                            <a
                                href="{{ route('staff.complaints.show', $complaint->id) }}"
                                data-url="{{ route('staff.complaints.show', $complaint->id) }}"
                                data-title="Complaint #{{ $complaint->id }}"
                                class="btn btn-sm btn-outline-dark complaint-view-link">

                                <i class="bi bi-eye"></i>
                                Review

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No complaints assigned to you yet.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $complaints->links() }}
        </div>

    </div>

</div>