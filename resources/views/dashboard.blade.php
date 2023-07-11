@extends('layout/layout')

@push('sites-leftside-menu')
@foreach ($sites as $site)
<li class="submenu-item ">
    <a href="{{ route('attendance.showlog.persite', ['siteId' => $site->id]) }}">{{
        $site->site_name }}</a>
</li>
@endforeach
@endpush


@section('page-heading')
<h4>Attendance Monitoring System</h4>
@endsection


@section('page-content')

@endsection