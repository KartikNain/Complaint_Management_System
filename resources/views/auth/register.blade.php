<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.head')
</head>
<body class="auth-body">

<div class="auth-card d-flex flex-wrap">

    <!-- Left Side -->
    <div class="auth-left col-md-5 d-flex flex-column justify-content-center">
        <i class="bi bi-person-plus display-4 mb-3"></i>

        <h2>Create Your Account</h2>

        <p class="opacity-75">
            Join the Complaint Management System to submit complaints, track their progress, and receive timely updates.
        </p>
    </div>

    <!-- Right Side -->
    <div class="auth-right col-md-7">

        <h3 class="mb-2">Create Account</h3>

        <p class="text-muted mb-4">
            Fill in your information below to create your account.
        </p>

        <form id="registerForm" data-url="{{ route('auth.register.store') }}">
            @csrf

            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>

                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Enter your full name"
                    value="{{ old('name') }}"
                    required
                    autofocus>

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>

                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Enter your email address"
                    value="{{ old('email') }}"
                    required>

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>

                    <input
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Create a password"
                        required>

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm your password"
                        required>
                </div>

            </div>

            <!-- Register Button -->
            <button type="submit" class="btn btn-dark-custom w-100 py-2">
                <i class="bi bi-person-check"></i>
                Create Account
            </button>

        </form>

        <div id="successMessage"></div>

        <!-- Login -->
        <p class="text-center text-muted mt-4 mb-0">
            Already have an account?
            <a href="{{ route('auth.login.page') }}" class="fw-semibold text-decoration-none">
                Sign In
            </a>
        </p>

    </div>

</div>

@include('partials.footer')
</body>
</html>
