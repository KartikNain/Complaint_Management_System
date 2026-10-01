@extends('layout.app')

@section('title', 'Staff Dashboard - CMS')
@section('page-title', 'Staff Dashboard')

@section('content')

<div id="appContent">
    @include('staff.partials.dashboard')
</div>

@endsection
