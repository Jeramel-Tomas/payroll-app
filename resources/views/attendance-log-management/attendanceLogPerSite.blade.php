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

@php
$countEmployees = count($employeesWorkingSite);
@endphp

<!-- Striped rows start -->
<section class="section">
    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $specificSite->site_name}} - Site</h4>
                </div>
                <div class="card-content">
                    <!-- table striped -->
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Job title</th>
                                    <th>Date</th>
                                    <th>Day/s of work</th>
                                    <th>OT (# of hrs)</th>
                                    <th>Action</th>
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
                                        <input type="number" id="overTime" class="form-control"
                                            placeholder="Over time..." min="1">
                                    </td>
                                    <td>
                                        <a href="#">
                                            {{-- {{ route('employees.edit', ['empid' => $employee->emp_info_id]) }} --}}
                                            <i class="material-icons" data-toggle="tooltip"
                                                title="Alter log">&#xE254;</i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $employeesWorkingSite->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Striped rows end -->

@endsection

@push('append-jq-code')
{{-- let counter = {{ $countEmployees }};
console.log({{ $countEmployees }}); --}}





@endpush