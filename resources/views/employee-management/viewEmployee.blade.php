@extends('../layout/layout')

{{-- @push('sites-leftside-menu')
@foreach ($sites as $site)
<li class="submenu-item">
    <a href="{{ route('attendance.showlog.persite', ['siteId' => $site->id]) }}">{{ $site->site_name }}</a>
</li>
@endforeach
@endpush --}}

@section('page-heading')
<h1 class="text-center">Employee Information</h1>
@endsection

@section('page-content')
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <div class="card  shadow p-3 mb-5 bg-body-tertiary rounded">
                    <div class="card-header mb-0 p-0">
                        <div class=" d-flex justify-content-between">

                            <h4 class="card-title">{{ Str::ucfirst(Str::lower($getEmployee->first_name)).' '.Str::ucfirst(Str::lower($getEmployee->last_name)) }}'s Employee Information</h4>
                            <a href="{{ route('employees.edit', ['empid' =>  $getEmployee->employee_id]) }}" class="btn btn-success">
                                <span class="bi bi-pencil-square align-items-center d-flex"
                                    data-toggle="tooltip" title="Edit"></span>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body  ">
                        <div class="row">
                            <div class="col col-sm-4 col-md-4 col-lg-4 border-end border-secondary mb-2">

                                <p class="text-uppercase bg-secondary text-white text-center ">Working Site</p>
                                <p wire:model="emp_site" id="workingSite">
                                    @php
                                    $siteName = DB::table('working_sites')
                                    ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                    ->where('employee_working_sites.employee_information_id', $getEmployee->employee_id)
                                    ->get();
                                    @endphp
                                <ol class="">
                                    @if($siteName->isEmpty())
                                        <p class="text-warning dance-animation">No Site Assigned</p>
                                    @else
                                    @foreach ($siteName as $item)
                                        <li class="{{$item->site_name ?? 'text-danger'}}">{{$item->site_name ?? 'Not set'}}</li>
                                        @endforeach
                                    @endif
                                </ol>
                                </p>
                            </div>
                            <div class="col col-sm-4 col-md-4 col-lg-4 border-end border-secondary mb-2">
                                <p class="text-uppercase bg-secondary text-white text-center">Job Title</p>
                                <p>
                                    @php
                                        $jobTitle = DB::table('working_sites')
                                            ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                            ->where('employee_working_sites.employee_information_id', $getEmployee->employee_id)
                                            ->get();
                                    @endphp
                                    <ol class="">
                                        @if($jobTitle->isEmpty())
                                        <p class="text-warning dance-animation ml-0">No Job Title</p>
                                    @else
                                        @foreach ($jobTitle as $item)
                                        <li class="{{$item->job_title ?? 'text-danger'}}">{{$item->job_title ?? 'Not set'}}</li>
                                        @endforeach
                                    @endif
                                    </ol>
                                </p>
                            </div>
                            <div class="col col-sm-4 col-md-4 col-lg-4 ">
                                <p class="text-uppercase bg-secondary text-white text-center">Daily Rate</p>
                                <p>
                                    @php
                                        $rate = DB::table('working_sites')
                                            ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                            ->where('employee_working_sites.employee_information_id', $getEmployee->employee_id)
                                            ->get();
                                    @endphp
                                    <ol class="">
                                        @if($rate->isEmpty())
                                            <p class="text-warning dance-animation">No Daily Rate</p>
                                        @else
                                        @foreach ($rate as $item)
                                            <li class="{{$item->job_title_rate ?? 'text-danger'}}">{{$item->job_title_rate ?? 'Not set'}}</li>
                                            @endforeach
                                        @endif
                                    </ol>
                                </p>
                            </div>
                        </div>
                            <p class="text-uppercase bg-secondary text-white text-center">Address</p>
                            <p class="{{ $getEmployee->address ? '' : ' text-danger' }}" id="address">
                                {{ $getEmployee->address?Str::ucfirst(Str::lower($getEmployee->address)):'Address not set' }}
                            </p>
                            
                            <p class="text-uppercase bg-secondary text-white text-center">Contact Number</p>
                            <p  class="{{ $getEmployee->contact_number ? '' : ' text-danger' }}" id="address">
                                {{ $getEmployee->contact_number?$getEmployee->contact_number:'Contact # not set' }}
                            </p>

                            <p class="text-uppercase bg-secondary text-white text-center">Date of Employment</p>
                            <p  class="{{ $getEmployee->employment_date ? '' : ' text-danger' }}" id="address">
                                {{ $getEmployee->employment_date?date('F d, Y', strtotime($getEmployee->employment_date)):'Employment Date Not Set!' }}
                            </p>
                        <div class="text-end">
                            <a href="{{ route('employees.list') }}" class="btn btn-primary">Back to View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- @push('js-code')

    const inputs = document.querySelectorAll(' input');
    inputs.forEach(input => {
        input.classList.add('text-center' , 'font-weight-bold');
    });
    
@endpush --}}

@endsection
