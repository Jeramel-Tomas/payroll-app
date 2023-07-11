@extends('../layout/layout')

@push('sites-leftside-menu')
@foreach ($sites as $site)
<li class="submenu-item ">
    <a href="{{ route('attendance.showlog.persite', ['siteId' => $site->id]) }}">{{
        $site->site_name }}</a>
</li>
@endforeach
@endpush

@section('page-content')

<div class="container">
    <div class="title">
        Attendance Logs
    </div>

</div>

@endsection