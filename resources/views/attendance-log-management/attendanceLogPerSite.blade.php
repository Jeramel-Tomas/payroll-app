@php
use Carbon\Carbon;
@endphp

@extends('../layout/layout')

@push('sites-leftside-menu')
@foreach ($sites as $site)
<li class="submenu-item ">
    <a href="{{ route('attendance.showlog.persite', ['siteId' => $site->id]) }}">{{
        $site->site_name }}</a>
</li>
@endforeach
@endpush

@section('page-heading')
<h4>Attendance log</h4>
@endsection

@section('page-content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Table</h3>
                <p class="text-subtitle text-muted">For user to check they list</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Table</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Table</h3>
                        <p class="text-subtitle text-muted">For user to check they list</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Table</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">

            <form action="" class="form-inline">
                <div class="form-group mb-2">
                    <label for="filterDate" class="text-right">Filter Date</label>
                    <input type="date" name="date" class="form-control" />
                </div>
            </form>

            <h4 class="text-center">{{ $employeesWorkingSite->first()->site_name }} - Site</h4>
            {{-- {{ Carbon::now()->format('l') }} --}}
            {{-- {{ dd(count($employeesWorkingSite)) }} --}}

            {{-- <div class="spinner-border text-success mb-4"> this is a spinner</div>

            <div class="spinner-border text-primary" id="spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div> --}}
            <div id="data"></div>

            @php
            $countEmployees = count($employeesWorkingSite);
            @endphp
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Status</th>
                            <th scope="col">Name</th>
                            <th scope="col">Job title</th>
                            <th scope="col">Date</th>
                            <th scope="col">Day/s of work</th>
                            <th scope="col">OT (# of hrs)</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employeesWorkingSite as $ews)
                        <tr>
                            <th scope="row">absent</th>
                            <td>{{ $ews->first_name }} {{ $ews->first_name }}</td>
                            <td>{{ $ews->job_title}}</td>
                            <td>{{ Carbon::now()->format('Y-m-d') }}</td>
                            <td>
                                {{-- {{ $ews->employee_information_id }}
                                {{ $ews->working_site_id }} --}}
                                <select class="form-control count-{{ $countEmployees }}"
                                    id="daysOfWork_{{$countEmployees}}"
                                    data-employee-id="{{ $ews->employee_information_id }}"
                                    data-site-id="{{ $ews->working_site_id }}">
                                    <option value="">--Attendance--</option>
                                    <option value="1">Whole day</option>
                                    <option value="0.5">Half day</option>
                                    <option value="0">Absent</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" id="overTime" class="form-control" placeholder="Over time..."
                                    min="1">
                            </td>
                            <td>
                                <a href="#">
                                    {{-- {{ route('employees.edit', ['empid' => $employee->emp_info_id]) }} --}}
                                    <i class="material-icons" data-toggle="tooltip" title="Alter log">&#xE254;</i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $employeesWorkingSite->links() }}
            </div>

        </div>

        @endsection

        @push('append-jq-code')
        {{-- let counter = {{ $countEmployees }};
        console.log({{ $countEmployees }}); --}}

        $('select[id^="daysOfWork_" ]').on('change', function() {
        var daysOfWork=$(this).val(); console.log(daysOfWork);
        console.log($(this).attr('data-employee-id'));
        console.log($(this).attr('data-site-id'));

        $.ajax({
        url: "{{ route('attendance.saveAttendanceAjax') }}" ,
        type: 'GET',
        data: {
        daysOfWork: daysOfWork
        },
        success: function(data) {
        console.log(data.msg);
        {{-- $('#result').html(data.msg); --}}
        }
        });

        });



        @endpush