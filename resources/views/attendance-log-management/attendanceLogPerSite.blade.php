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
{{-- <td>{{ Carbon::now()->format('Y-m-d') }}</td> --}}
<!-- Striped rows start -->
<section class="section">
    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title d-inline">{{ $specificSite->site_name}} - Site</h3>
                    <p class="text-sm d-inline float-sm-end">Today is: {{ Carbon::now()->format('F d, Y') }}</p>
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
                                    <th>Day/s of work</th>
                                    <th>OT (# of hrs)</th>
                                    <th>Alter log</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employeesWorkingSite as $ews)
                                <tr>
                                    <th scope="row">
                                        @if (!isset($ews->attendance_status))
                                        <span class="badge bg-warning">Not yet checked</span>
                                        @elseif ($ews->attendance_status === '0')
                                        <span class="badge bg-danger">Absent</span>
                                        @else
                                        <span class="badge bg-info">Present</span>
                                        @endif
                                    </th>
                                    <td>{{ Str::ucfirst($ews->first_name) }} {{ Str::ucfirst($ews->last_name) }}</td>
                                    <td>{{ Str::ucfirst($ews->job_title) }}</td>
                                    <td>
                                        <select class="form-control" id="daysOfWork"
                                            data-employee-id="{{ $ews->employeeId }}" {{ (!isset($ews->attendance_status)) ?: 'disabled' }}>
                                            <option value="">--Attendance--</option>
                                            <option value="1" {{ ($ews->attendance_status == '1') ? 'slected' : '' }}>Whole day</option>
                                            <option value="0.5" {{ ($ews->attendance_status == "0.5") ? 'slected' : '' }}>Half day</option>
                                            <option value="0" {{ ($ews->attendance_status == "0") ? 'slected' : '' }}>Absent</option>
                                        </select>
                                    </td>
                                    <td>
                                        @if ($ews->overtime_per_day)
                                        {{ $ews->overtime_per_day }}
                                        @else
                                        <input type="number" id="overTime" class="form-control" 
                                            placeholder="Over time..." min="1" max="8" data-employee-id="{{ $ews->employeeId }}"
                                            {{ ($ews->attendance_status && $ews->attendance_status !== '0' && Carbon::today()->eq($ews->attendance_date)) ?: 'disabled' }} >
                                            {{-- <span class="bi bi-save-fill btn"></span> --}}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#">
                                            {{-- {{ route('employees.edit', ['empid' => $employee->emp_info_id]) }} --}}
                                           
                                            <span class="bi bi-pencil-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Alter log"></span>
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

<div class="overlay d-none" id="spinner-wrapper">
    <div class="d-flex justify-content-center spinner-container">
    </div>
</div>

@endsection

@push('jq-code')

$('select[id="daysOfWork"]').on('change', function () {

    {{-- let daysOfWorked = $(this).val();
    let employeeId =$(this).attr('data-employee-id'); --}}

    let payload = {
        employeeId: $(this).attr('data-employee-id'),
        daysOfWorked: $(this).val()
    };
    let endpoint = "{{ route('attendance.saveAttendanceAjax') }}";
    ajaxCall('GET', endpoint, payload)
});

var setTimeoutFuntion;

$("#overTime").bind("change keyup", function() {
    let payload = {
        employeeId: $(this).attr('data-employee-id'),
        ovetimeHours: $(this).val()
    };
    let endpoint = "{{ route('attendance.saveAttendanceAjax') }}";

    clearTimeout(setTimeoutFuntion);
    setTimeoutFuntion = setTimeout(function() {
        ajaxCall('GET', endpoint, payload);
    }, 2000);
    
});

@endpush


@push('js-code')
let delay = 5000;
let res = {
    loader: $(
        "<div />", {
        class: "spinner-border",
        role: "status"
    }),
    sucessMess: $(
        "<div />", {
        class: "icon dripicons-checkmark text-success fs-1"
    })
};


function ajaxCall(type, endpoint, payLoad)
{
    $.ajax({
        url: endpoint,
        type: type,
        data: payLoad,
        beforeSend: function () {
            $("#spinner-wrapper").removeClass('d-none');
            $(".spinner-container").append(res.loader);
            window.setTimeout(delay);
        },
        success: function (data) {
            $(".spinner-container").find(res.loader).remove();
        
            if (data.msg) {
                $(".spinner-container").append(res.sucessMess);
            }
            
            setTimeout(function(){
                $(".spinner-container").find(res.sucessMess).remove();
                $("#spinner-wrapper").addClass('d-none');
                history.go(0);
            }, delay-3000);
            
           {{--  console.log(data.msg);
            console.log('success'); --}}
        }
    });
}

@endpush