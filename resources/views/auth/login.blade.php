<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.head')
</head>
<body class="auth-body">

<div class="auth-card d-flex flex-wrap">
    <div class="auth-left col-md-5 d-flex flex-column justify-content-center">
        <i class="bi bi-headset display-4 mb-3"></i>
        <h3>Complaint Management System</h3>
        <p class="opacity-75">Submit your complaints, track their progress, and get updates - all in one place.</p>
    </div>

    <div class="auth-right col-md-7">
        <h4 class="mb-1">Welcome Back</h4>
        <p class="text-muted mb-4">Login to your account</p>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="loginForm" data-url="{{ route('auth.login.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required>
            </div>

            <button type="submit" class="btn btn-dark w-100">
                Login
            </button>
        </form>

        <div id="loginMessage"></div>

        <p class="text-center text-muted mt-4 mb-0">
            Don't have an account? <a href="{{ route('auth.register.page') }}" class="text-decoration-none fw-semibold">Register here</a>
        </p>
    </div>
</div>

@include('partials.footer')

</body>
</html>
