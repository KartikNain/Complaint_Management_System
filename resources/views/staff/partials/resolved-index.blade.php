<div class="panel">
    <div class="mb-4">
        <h6 class="panel-title mb-1">Resolved Complaints</h6>
        <small class="text-muted">Complaints you've resolved or closed.</small>
    </div>

    <form id="staffResolvedFilterForm" data-url="{{ route('staff.resolved.index') }}" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search title / employee..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">All</option>
                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
    </form>

    <div id="staffResolvedTableContainer">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr><th>ID</th><th>Title</th><th>Employee</th><th>Status</th><th>Resolved On</th><th class="text-end">Action</th></tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                    <tr>
                        <td>#{{ $complaint->id }}</td>
                        <td>{{ $complaint->title }}</td>
                        <td>{{ $complaint->user->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $complaint->status == 'Closed' ? 'secondary' : 'success' }}">
                                {{ $complaint->status }}
                            </span>
                        </td>
                        <td>{{ $complaint->updated_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('staff.complaints.show', $complaint->id) }}"
                               data-url="{{ route('staff.complaints.show', $complaint->id) }}"
                               data-title="Complaint #{{ $complaint->id }}"
                               class="btn btn-sm btn-outline-dark complaint-view-link">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No resolved complaints yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $complaints->appends(request()->query())->links() }}</div>
    </div>
</div>