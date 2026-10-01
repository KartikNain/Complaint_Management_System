<!-- Add Users Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="usersModalLabel">
                        <i class="bi bi-person-plus me-2"></i>
                        Add User
                    </h5>

                    <p class="text-muted mb-0 mt-1">
                        Create a new employee or staff account.
                    </p>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <form id="addUserForm" data-url="{{ route('admin.users.store') }}">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter full name"
                            required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter email address"
                            required>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label class="form-label">
                            User Role
                        </label>

                        <select name="role" class="form-select" required>
                            <option value="" selected disabled>
                                Select user role
                            </option>

                            <option value="employee">
                                Employee
                            </option>

                            <option value="staff">
                                Staff
                            </option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Create password"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Confirm password"
                                required>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="text-end mt-2">

                        <button type="button"
                                class="btn btn-secondary me-2"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit"
                                class="btn btn-dark-custom px-4">
                            <i class="bi bi-person-check me-1"></i>
                            Create User
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</div>