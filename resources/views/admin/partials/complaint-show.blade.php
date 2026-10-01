<div class="panel" style="max-width: 900px; margin: 0 auto;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="panel-title mb-1">Complaint #{{ $complaint->id }}</h6>
            <small class="text-muted">Submitted on {{ $complaint->created_at->format('d M Y, h:i A') }}</small>
        </div>
        <a href="{{ route('admin.complaints.index') }}"
           data-url="{{ route('admin.complaints.index') }}"
           data-title="Manage Complaints"
           class="btn btn-sm btn-outline-secondary complaint-back-link">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row g-4">

        {{-- Complaint details --}}
        <div class="col-md-7">
            <div class="border rounded p-3 h-100">
                <h6 class="mb-3">{{ $complaint->title }}</h6>

                <div class="row mb-2">
                    <div class="col-6"><small class="text-muted">Category</small><div>{{ $complaint->category }}</div></div>
                    <div class="col-6"><small class="text-muted">Department</small><div>{{ $complaint->department }}</div></div>
                </div>

                <div class="row mb-2">
                    <div class="col-6"><small class="text-muted">Priority</small><div>{{ $complaint->priority }}</div></div>
                    <div class="col-6"><small class="text-muted">Submitted By</small><div>{{ $complaint->user->name ?? 'N/A' }}</div></div>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Description</small>
                    <p class="mb-0">{{ $complaint->description }}</p>
                </div>

                @if ($complaint->attachment)
                    <div class="mb-2">
                        <small class="text-muted">Attachment</small><br>
                        <a href="{{ asset('storage/' . $complaint->attachment) }}" target="_blank">
                            <i class="bi bi-paperclip"></i> View Attachment
                        </a>
                    </div>
                @endif

                @if ($complaint->resolution_summary)
                    <div class="mt-3 p-2 bg-light rounded">
                        <small class="text-muted">Resolution Summary</small>
                        <p class="mb-0">{{ $complaint->resolution_summary }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="col-md-5">

            {{-- Assign --}}
            <div class="border rounded p-3 mb-3">
                <h6 class="mb-3">Assign To Staff</h6>
                <form id="assignComplaintForm" data-url="{{ route('admin.complaints.assign', $complaint->id) }}">
                    @csrf
                    <select name="assigned_to" class="form-select mb-2" required>
                        <option value="" disabled {{ !$complaint->assigned_to ? 'selected' : '' }}>Select staff member</option>
                        @foreach ($staff as $member)
                            <option value="{{ $member->id }}" {{ $complaint->assigned_to == $member->id ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-dark-custom w-100">
                        <i class="bi bi-person-check me-1"></i> Assign
                    </button>
                </form>
            </div>

            {{-- Status Update --}}
            <div class="border rounded p-3">
                <h6 class="mb-3">Update Status</h6>
                <form id="updateStatusForm" data-url="{{ route('admin.complaints.updateStatus', $complaint->id) }}">
                    @csrf
                    <select name="status" id="statusSelect" class="form-select mb-2" required>
                        @foreach (['Pending','Assigned','In Progress','Resolved','Closed','Reopened'] as $s)
                            <option value="{{ $s }}" {{ $complaint->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>

                    <div id="resolutionSummaryWrap" class="mb-2" style="{{ $complaint->status == 'Resolved' ? '' : 'display:none;' }}">
                        <textarea name="resolution_summary" class="form-control" rows="3"
                                  placeholder="Describe how the issue was resolved...">{{ $complaint->resolution_summary }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-dark-custom w-100">
                        <i class="bi bi-arrow-repeat me-1"></i> Update Status
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>