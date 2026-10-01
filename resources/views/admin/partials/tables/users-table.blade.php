<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th class="text-end">Action</th></tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-{{ $user->role == 'admin' ? 'dark' : ($user->role == 'staff' ? 'info' : 'secondary') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-dark user-edit-btn"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}"
                            data-role="{{ $user->role }}"
                            data-url="{{ route('admin.users.update', $user->id) }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    @if($user->id !== auth()->id())
                    <button type="button" class="btn btn-sm btn-outline-danger user-delete-btn"
                            data-url="{{ route('admin.users.destroy', $user->id) }}">
                        <i class="bi bi-trash"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $users->appends(request()->query())->links() }}
</div>