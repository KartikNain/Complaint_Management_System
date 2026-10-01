<div class="panel" style="max-width: 900px; margin: 0 auto;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="panel-title mb-1">Complaint #{{ $complaint->id }}</h6>
            <small class="text-muted">Submitted on {{ $complaint->created_at->format('d M Y, h:i A') }}</small>
        </div>
        <a href="{{ route('employee.complaints.index') }}"
           data-url="{{ route('employee.complaints.index') }}"
           data-title="My Complaints"
           class="btn btn-sm btn-outline-secondary complaint-back-link">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row g-4">

        <div class="col-md-7">
            <div class="border rounded p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $complaint->title }}</h6>
                    <span id="statusBadge" class="badge bg-{{ match($complaint->status) {
                        'Assigned' => 'info', 'In Progress' => 'primary',
                        'Resolved' => 'success', 'Closed' => 'secondary',
                        'Reopened' => 'danger', default => 'light',
                    } }}">
                        {{ $complaint->status }}
                    </span>
                </div>

                <div class="row mb-2">
                    <div class="col-6"><small class="text-muted">Category</small><div>{{ $complaint->category }}</div></div>
                    <div class="col-6"><small class="text-muted">Department</small><div>{{ $complaint->department }}</div></div>
                </div>

                <div class="row mb-2">
                    <div class="col-6"><small class="text-muted">Priority</small><div>{{ $complaint->priority }}</div></div>
                    <div class="col-6"><small class="text-muted">Assigned Staff</small><div>{{ $complaint->assignedTo->name ?? 'Not assigned yet' }}</div></div>
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
            </div>
        </div>

        <div class="col-md-5">

            {{-- Resolution + Accept/Reopen --}}
            @if($complaint->status === 'Resolved')
            <div class="border rounded p-3 mb-3" id="resolutionDecisionBox">
                <h6 class="mb-2">Resolution Summary</h6>
                <p class="mb-3">{{ $complaint->resolution_summary }}</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm flex-fill employee-decision-btn"
                            data-decision="accept"
                            data-url="{{ route('employee.complaints.decision', $complaint->id) }}">
                        <i class="bi bi-check2-circle"></i> Accept Resolution
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill employee-decision-btn"
                            data-decision="reopen"
                            data-url="{{ route('employee.complaints.decision', $complaint->id) }}">
                        <i class="bi bi-arrow-counterclockwise"></i> Reopen
                    </button>
                </div>
            </div>
            @elseif($complaint->status === 'Closed' && $complaint->resolution_summary)
            <div class="border rounded p-3 mb-3 bg-light">
                <small class="text-muted">Resolution Summary</small>
                <p class="mb-0">{{ $complaint->resolution_summary }}</p>
            </div>
            @endif

            {{-- Conversation --}}
            <div class="border rounded p-3">
                <h6 class="mb-3">Conversation</h6>

                <div id="conversationList" style="max-height: 280px; overflow-y: auto;">
                    @forelse($complaint->comments as $comment)
                        @include('employee.partials.complaint-comment', ['comment' => $comment])
                    @empty
                        <p class="text-muted small" id="noConversationMsg">No conversation yet.</p>
                    @endforelse
                </div>

                @if(!in_array($complaint->status, ['Closed']))
                <form id="employeeAddCommentForm" data-url="{{ route('employee.complaints.comment', $complaint->id) }}" enctype="multipart/form-data" class="mt-2">
                    @csrf
                    <textarea name="comment" rows="2" class="form-control mb-2" placeholder="Type a message..." required></textarea>
                    <input type="file" name="attachment" class="form-control form-control-sm mb-2">
                    <button type="submit" class="btn btn-dark-custom btn-sm w-100">
                        <i class="bi bi-send me-1"></i> Send
                    </button>
                </form>
                @endif
            </div>

        </div>
    </div>

</div>