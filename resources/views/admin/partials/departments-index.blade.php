<div class="panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="panel-title mb-1">Departments</h6>
            <small class="text-muted">Add and manage departments used across complaints.</small>
        </div>
        <button type="button" class="btn btn-dark-custom" id="addDepartmentBtn"
                data-bs-toggle="modal" data-bs-target="#departmentModal">
            <i class="bi bi-plus-lg me-1"></i> Add Department
        </button>
    </div>

    <form id="departmentFilterForm" data-url="{{ route('admin.departments.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search department..." value="{{ request('search') }}">
        </div>
    </form>

    <div id="departmentsTableContainer">
        @include('admin.partials.tables.departments-table')
    </div>
</div>

{{-- Add/Edit modal --}}
<div class="modal fade" id="departmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="departmentModalTitle">Add Department</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="departmentForm" data-url="{{ route('admin.departments.store') }}" data-mode="create">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Department Name</label>
                    <input type="text" name="name" id="departmentNameInput" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark-custom">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>