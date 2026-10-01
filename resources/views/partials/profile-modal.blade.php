<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#profileInfoPane" type="button" role="tab">
                            Profile Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#profilePasswordPane" type="button" role="tab">
                            Change Password
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="profileTabContent">

                    {{-- Profile Info Tab --}}
                    <div class="tab-pane fade show active" id="profileInfoPane" role="tabpanel">

                        <form id="profileInfoForm" data-url="{{ url('/profile/update') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <div class="text-center mb-4">

                                <div class="profile-avatar-preview" id="avatarPreview">
                                    @if(Auth::user()->profileImage)
                                        <img src="{{ asset(Auth::user()->profileImage) }}" alt="Profile photo">
                                    @else
                                        <i class="bi bi-person"></i>
                                    @endif
                                </div>

                                <label class="d-block mt-2">
                                    <span class="avatar-upload-btn">
                                        <i class="bi bi-camera"></i> Change Photo
                                    </span>
                                    <input type="file" name="profileImage" id="avatarInput" accept="image/*" class="d-none">
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-dark-custom px-4">
                                    Save Changes
                                </button>
                            </div>

                        </form>

                    </div>

                    {{-- Change Password Tab --}}
                    <div class="tab-pane fade" id="profilePasswordPane" role="tabpanel">

                        <form id="profilePasswordForm" data-url="{{ url('/profile/password') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter new password" required>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-dark-custom px-4">
                                    Update Password
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
