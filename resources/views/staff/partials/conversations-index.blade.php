<div class="panel">
    <div class="mb-4">
        <h6 class="panel-title mb-1">Conversations</h6>
        <small class="text-muted">Complaints with message activity, most recent first.</small>
    </div>

    <form id="staffConversationFilterForm" data-url="{{ route('staff.conversations.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search title / employee..." value="{{ request('search') }}">
        </div>
    </form>

    <div id="staffConversationsListContainer">
        @forelse($complaints as $complaint)
            @php $lastComment = $complaint->comments->first(); @endphp
            <a href="{{ route('staff.complaints.show', $complaint->id) }}"
               data-url="{{ route('staff.complaints.show', $complaint->id) }}"
               data-title="Complaint #{{ $complaint->id }}"
               class="d-block text-decoration-none text-dark complaint-view-link">
                <div class="border rounded p-3 mb-2 conversation-row">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>#{{ $complaint->id }} — {{ $complaint->title }}</strong>
                            <div class="small text-muted">{{ $complaint->user->name ?? 'N/A' }}</div>
                        </div>
                        <small class="text-muted">{{ $lastComment?->created_at->diffForHumans() }}</small>
                    </div>
                    @if($lastComment)
                        <p class="mb-0 mt-1 small text-truncate">
                            <strong>{{ $lastComment->user->name ?? 'User' }}:</strong> {{ $lastComment->comment }}
                        </p>
                    @endif
                </div>
            </a>
        @empty
            <p class="text-center text-muted py-4">No conversations yet.</p>
        @endforelse

        <div class="mt-3">{{ $complaints->links() }}</div>
    </div>
</div>