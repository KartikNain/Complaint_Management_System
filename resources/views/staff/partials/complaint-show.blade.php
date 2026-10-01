<div class="panel" style="max-width: 900px; margin: 0 auto;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="panel-title mb-1">Complaint #{{ $complaint->id }}</h6>
            <small class="text-muted">Submitted on {{ $complaint->created_at->format('d M Y, h:i A') }}</small>
        </div>
        <a href="{{ route('staff.complaints.index') }}"
           data-url="{{ route('staff.complaints.index') }}"
           data-title="Assigned Complaints"
           class="btn btn-sm btn-outline-secondary complaint-back-link">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row g-4">

        {{-- Complaint details --}}
        <div class="col-md-7">
            <div class="border rounded p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $complaint->title }}</h6>
                    <span id="statusBadge" class="badge bg-{{ match($complaint->status) {
                        'Assigned' => 'info',
                        'In Progress' => 'primary',
                        'Resolved' => 'success',
                        'Reopened' => 'danger',
                        default => 'light',
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
            </div>
        </div>

        {{-- Actions --}}
        <div class="col-md-5">

            {{-- Status / Resolve --}}
            @if(!in_array($complaint->status, ['Closed']))
            <div class="border rounded p-3 mb-3">
                <h6 class="mb-3">Update Status</h6>
                <form id="staffUpdateStatusForm" data-url="{{ route('staff.complaints.updateStatus', $complaint->id) }}">
                    @csrf
                    <select name="status" id="staffStatusSelect" class="form-select mb-2" required>
                        <option value="In Progress" {{ $complaint->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ $complaint->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>

                    <div id="staffResolutionSummaryWrap" class="mb-2" style="{{ $complaint->status == 'Resolved' ? '' : 'display:none;' }}">
                        <textarea name="resolution_summary" class="form-control" rows="3"
                                  placeholder="Describe how the issue was resolved...">{{ $complaint->resolution_summary }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-dark-custom w-100">
                        <i class="bi bi-arrow-repeat me-1"></i> Update Status
                    </button>
                </form>
            </div>
            @else
                @if ($complaint->resolution_summary)
                <div class="border rounded p-3 mb-3 bg-light">
                    <small class="text-muted">Resolution Summary</small>
                    <p class="mb-0">{{ $complaint->resolution_summary }}</p>
                </div>
                @endif
            @endif

            {{-- Conversation --}}
            <div class="border rounded p-3">
                <h6 class="mb-3">Conversation</h6>

                <div id="conversationList" style="max-height: 280px; overflow-y: auto;">
                    @forelse($complaint->comments as $comment)
                        @include('staff.partials.complaint-comment', ['comment' => $comment])
                    @empty
                        <p class="text-muted small" id="noConversationMsg">No conversation yet.</p>
                    @endforelse
                </div>

                @if(!in_array($complaint->status, ['Closed']))
                <form id="staffAddCommentForm" data-url="{{ route('staff.complaints.comment', $complaint->id) }}" enctype="multipart/form-data" class="mt-2">
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