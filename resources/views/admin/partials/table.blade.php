<div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Assigned To</th>
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
                            <span class="badge bg-{{ $priorityColor }}">{{ $complaint->priority }}</span>
                        </td>
                        <td>{{ $complaint->assignedTo->name ?? '— Not Assigned —' }}</td>
                        <td>
                            @php
                                $statusColor = match($complaint->status) {
                                    'Pending' => 'warning',
                                    'Assigned' => 'info',
                                    'In Progress' => 'primary',
                                    'Resolved' => 'success',
                                    'Closed' => 'secondary',
                                    'Reopened' => 'danger',
                                    default => 'light',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ $complaint->status }}</span>
                        </td>
                        <td>{{ $complaint->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.complaints.show', $complaint->id) }}"
                               data-url="{{ route('admin.complaints.show', $complaint->id) }}"
                               data-title="Complaint #{{ $complaint->id }}"
                               class="btn btn-sm btn-outline-dark complaint-view-link">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No complaints found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>