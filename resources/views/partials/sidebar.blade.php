<aside class="app-sidebar" id="appSidebar">

    <div class="d-flex align-items-center justify-content-between">
        <div class="brand">
            <i class="bi bi-headset"></i>
            <div>
                <div class="brand-text">CMS</div>
                <div class="brand-sub">Complaint System</div>
            </div>
        </div>
        <button type="button" class="btn btn-sm text-white d-lg-none me-2" id="sidebarCloseBtn" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul class="nav flex-column py-2">

        @if(Auth::user()->role === 'admin')

            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item" id="adminComplaintsLink">
                <a href="{{ route('admin.complaints.index') }}"
                   data-url="{{ route('admin.complaints.index') }}"
                   data-title="Manage Complaints"
                   class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-card-checklist"></i> Manage Complaints
                </a>
            </li>

            <li class="nav-item" id="adminUsersLink">
                <a href="{{ route('admin.users.index') }}"
                data-url="{{ route('admin.users.index') }}"
                data-title="Users"
                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Users
                </a>
            </li>

            <li class="nav-item" id="adminDepartmentsLink">
                <a href="{{ route('admin.departments.index') }}"
                data-url="{{ route('admin.departments.index') }}"
                data-title="Departments"
                class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i> Departments
                </a>
            </li>

            <li class="nav-item" id="adminReportsLink">
                <a href="{{ route('admin.reports.index') }}"
                data-url="{{ route('admin.reports.index') }}"
                data-title="Reports"
                class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i> Reports
                </a>
            </li>

        @elseif(Auth::user()->role === 'staff')

            <li class="nav-item" id="staffDashboardLink">
                <a href="{{ route('staff.dashboard') }}"
                data-url="{{ route('staff.dashboard') }}"
                data-title="Dashboard"
                class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item" id="staffComplaintsLink">
                <a href="{{ route('staff.complaints.index') }}"
                   data-url="{{ route('staff.complaints.index') }}"
                   data-title="Assigned Complaints"
                   class="nav-link {{ request()->routeIs('staff.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-card-checklist"></i> Assigned Complaints
                </a>
            </li>

            <li class="nav-item" id="staffConversationsLink">
                <a href="{{ route('staff.conversations.index') }}"
                data-url="{{ route('staff.conversations.index') }}"
                data-title="Conversations"
                class="nav-link {{ request()->routeIs('staff.conversations.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-left-text"></i> Conversations
                </a>
            </li>

            <li class="nav-item" id="staffResolvedLink">
                <a href="{{ route('staff.resolved.index') }}"
                data-url="{{ route('staff.resolved.index') }}"
                data-title="Resolved"
                class="nav-link {{ request()->routeIs('staff.resolved.*') ? 'active' : '' }}">
                    <i class="bi bi-check2-circle"></i> Resolved
                </a>
            </li>

        @else

            <li class="nav-item" id="employeeDashboardLink">
                <a href="{{ route('employee.dashboard') }}"
                data-url="{{ route('employee.dashboard') }}"
                data-title="Dashboard"
                class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item" id="submitComplaintLink">
                <a href="javascript:void(0);"
                class="nav-link {{ request()->routeIs('employee.complaints.create') ? 'active' : '' }}"
                data-url="{{ route('employee.complaints.create') }}"
                data-title="Submit Complaint">
                    <i class="bi bi-plus-circle"></i> Submit Complaint
                </a>
            </li>

            <li class="nav-item" id="myComplaintsLink">
                <a href="{{ route('employee.complaints.index') }}"
                data-url="{{ route('employee.complaints.index') }}"
                data-title="My Complaints"
                class="nav-link {{ request()->routeIs('employee.complaints.index') || request()->routeIs('employee.complaints.show') ? 'active' : '' }}">
                    <i class="bi bi-card-list"></i> My Complaints
                </a>
            </li>

        @endif

    </ul>

</aside>
