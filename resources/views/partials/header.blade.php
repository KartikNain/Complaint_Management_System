<nav class="app-header">

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Open menu">
            <i class="bi bi-list"></i>
        </button>
        <h4>@yield('page-title')</h4>
    </div>


    <div class="dropdown me-2">
        <button class="header-user-btn dropdown-toggle position-relative" type="button"
                id="notificationBellBtn" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell fs-5"></i>
            <span id="notifBadge" class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle d-none"
                style="font-size: 0.65rem;">0</span>
        </button>

        <div class="dropdown-menu dropdown-menu-end shadow-sm p-0" style="width: 340px; max-height: 420px; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                <strong class="small">Notifications</strong>
                <button type="button" id="markAllReadBtn" class="btn btn-sm btn-link p-0 small">Mark all read</button>
            </div>
            <div id="notificationList">
                <div class="text-center text-muted small py-4">Loading...</div>
            </div>
        </div>
    </div>
    <div class="dropdown">

        <button class="header-user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="header-user-avatar" id="headerAvatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
            <span class="header-user-name">{{ Auth::user()->name }}</span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

            <li>
                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileModal">
                    <i class="bi bi-person-gear"></i> Edit Profile
                </button>
            </li>

            <li><hr class="dropdown-divider"></li>
            @if(Auth::user()->role === 'admin')
            <li>
                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#usersModal">
                    <i class="bi bi-person-gear"></i> Add Users
                </button>
            </li>
            
            <li><hr class="dropdown-divider"></li>
            @endif

            <li>
                <form id="logoutForm" action="{{ route('auth.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <button id="logoutBtn" type="button" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </li>

            

        </ul>

    </div>

</nav>
