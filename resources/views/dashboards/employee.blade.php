@extends('layout.app')

@section('title', 'Employee Dashboard - CMS')
@section('page-title', 'Employee Dashboard')

@section('content')

<div id="appContent">
    @include('employee.partials.dashboard')
</div>

@endsection
