<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Created</th><th class="text-end">Action</th></tr>
        </thead>
        <tbody>
            @forelse($departments as $dept)
            <tr>
                <td>#{{ $dept->id }}</td>
                <td>{{ $dept->name }}</td>
                <td>{{ $dept->created_at->format('d M Y') }}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-dark department-edit-btn"
                            data-id="{{ $dept->id }}"
                            data-name="{{ $dept->name }}"
                            data-url="{{ route('admin.departments.update', $dept->id) }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger department-delete-btn"
                            data-url="{{ route('admin.departments.destroy', $dept->id) }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No departments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $departments->links() }}</div>