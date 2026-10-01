@if(auth()->check() && auth()->user()->role === 'admin')
@extends('layout.app')

@section('title', 'Admin Dashboard - CMS')
@section('page-title', 'Admin Dashboard')

@section('content')

<div id="appContent">
    @include('admin.partials.dashboard')
</div>  

@endsection

@endif