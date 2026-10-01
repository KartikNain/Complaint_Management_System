@php
    $categories = ['IT Equipment', 'Finance', 'HR', 'Facilities', 'Software', 'Other'];
    // Replace this static array with Department::pluck('name') once your Departments module is built
    $departments = ['IT Department', 'Finance Department', 'HR Department', 'Admin / Facilities'];
@endphp
    <div class="alert alert-warning">
        <strong>Access Denied:</strong> Only employees can submit complaints.
    </div>
<div class="panel" style="max-width: 800px; margin: 0 auto;">

    <div class="mb-4">
        <h6 class="panel-title mb-1">New Complaint</h6>
        <small class="text-muted">Fill in the details below to submit a new complaint.</small>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form data-url="{{ route('employee.complaints.store') }}" method="POST" enctype="multipart/form-data"
    id="submitComplaintForm">
        @csrf

        <div class="row g-3">

            <div class="col-md-12">
                <label class="form-label">Complaint Title</label>
                <input type="text" name="title" class="form-control" placeholder="Brief summary of the issue" value="{{ old('title') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select name="category" class="form-select" required>
                    <option value="" disabled selected>Select category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Department</label>
                <select name="department" class="form-select" required>
                    <option value="" disabled selected>Select department</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select" required>
                    <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ old('priority', 'Medium') == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Attachment <small class="text-muted">(optional)</small></label>
                <input type="file" name="attachment" class="form-control">
                <small class="text-muted">JPG, PNG, PDF, DOC — max 5MB</small>
            </div>

            <div class="col-md-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Describe the issue in detail...">{{ old('description') }}</textarea>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="submit" class="btn btn-dark-custom px-4">
                <i class="bi bi-person-check me-1"></i> Submit Complaint
            </button>
        </div>

    </form>

</div>
