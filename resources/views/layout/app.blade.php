<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.head')
</head>
<body class="app-body">

<div class="app-shell d-flex">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    {{-- Main Column --}}
    <div class="flex-grow-1 d-flex flex-column" style="min-width:0;">

        {{-- Header --}}
        @include('partials.header')
            <div class="app-content flex-grow-1" id="appContent">
                @yield('content')
            </div>

    </div>

</div>

{{-- Profile edit modal (available on every authenticated page) --}}
@include('partials.profile-modal')

{{-- add users modal (available on every authenticated page) --}}
@include('partials.addUsers-modal')


@include('partials.footer')
</body>
</html>
