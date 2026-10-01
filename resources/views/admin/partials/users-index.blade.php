<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="panel-title mb-1">Users</h6>
            <small class="text-muted">Manage admin, staff and employee accounts.</small>
        </div>
        <button type="button" class="btn btn-dark-custom" id="addUserBtn"
                data-bs-toggle="modal" data-bs-target="#usersModal"
                data-url="{{ route('admin.users.store') }}">
            <i class="bi bi-person-plus me-1"></i> Add User
        </button>
    </div>

    <form id="userFilterForm" data-url="{{ route('admin.users.index') }}" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Search name / email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                @foreach(['admin','staff','employee'] as $r)
                    <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div id="usersTableContainer">
        @include('admin.partials.tables.users-table', ['users' => $users])
    </div>
</div>

{{-- Add/Edit modal --}}
<div class="modal fade" id="usersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="usersModalTitle">Add User</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm" data-url="{{ route('admin.users.store') }}" data-mode="create">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="userNameInput" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="userEmailInput" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="userRoleInput" class="form-select" required>
                            <option value="employee">Employee</option>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <small class="text-muted" id="passwordHint">(min 6 characters)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark-custom">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>