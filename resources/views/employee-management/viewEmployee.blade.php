@extends('../layout/layout')

@push('sites-leftside-menu')
@foreach ($sites as $site)
<li class="submenu-item">
    <a href="{{ route('attendance.showlog.persite', ['siteId' => $site->id]) }}">{{ $site->site_name }}</a>
</li>
@endforeach
@endpush

@section('page-heading')
<h1 class="text-center">Employee Information</h1>
@endsection

@section('page-content')
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $getEmployee->first()->first_name.' '.$getEmployee->first()->last_name
                            }} Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="workingSite">Working Site</label>
                            <input type="text" class="form-control" id="workingSite"
                                value="{{ $getEmployee->first()->site_name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="jobTitle">Job Title</label>
                            <input type="text" class="form-control" id="jobTitle"
                                value="{{ $getEmployee->first()->job_title }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="dailyRate">Daily Rate</label>
                            <input type="text" class="form-control" id="dailyRate"
                                value="{{ $getEmployee->first()->daily_rate }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address"
                                value="{{ $getEmployee->first()->address }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <input type="text" class="form-control" id="contactNumber"
                                value="{{ $getEmployee->first()->contact_number }}" disabled>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('employees.list') }}" class="btn btn-primary">Back to View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
