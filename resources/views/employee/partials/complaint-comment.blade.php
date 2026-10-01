<div class="mb-3 p-2 border rounded {{ $comment->user_id == auth()->id() ? 'bg-light' : '' }}">
    <div class="d-flex justify-content-between">
        <strong>{{ $comment->user->name ?? 'User' }}</strong>
        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
    </div>
    <p class="mb-1">{{ $comment->comment }}</p>
    @if($comment->attachment)
        <a href="{{ asset('storage/' . $comment->attachment) }}" target="_blank" class="small">
            <i class="bi bi-paperclip"></i> Attachment
        </a>
    @endif
</div>