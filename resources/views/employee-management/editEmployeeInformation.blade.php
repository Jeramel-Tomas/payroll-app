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
<h1>Add Employee</h1>
@endsection

@section('page-content')
@if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
@endif
@dump($errors->all())
<div class="row">
    <div class="col-3"></div>
    <div class="col-md-6 col-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit {{ $employee->first_name. ' ' .$employee->last_name }} Information</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form form-horizontal" action="{{ route('employees.update', ['empid' => $employee->id]) }}" method="GET">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>First Name</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="text" name="empID" value="{{ $employee->id }}">
                                            <input type="text" class="form-control @error('firstName') is-invalid @enderror" placeholder="First Name"
                                                id="firstName" name="firstName" value="{{ $employee->first_name }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@error('firstName'){{ $message }}@enderror">
                                            <div class="form-control-icon">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Middle Name</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="text" class="form-control @error('middleName') is-invalid @enderror" placeholder="Middle Name"
                                                id="middleName" name="middleName" value="{{ $employee->middle_name }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@error('middleName'){{ $message }}@enderror">
                                            <div class="form-control-icon">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Last Name</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="text" class="form-control @error('lastName') is-invalid @enderror" placeholder="Last Name"
                                                id="lastName" name="lastName" value="{{ $employee->last_name }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@error('lastName'){{ $message }}@enderror">
                                            <div class="form-control-icon">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Working Site </label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <select class="form-control form-select" name="working_site">
                                                @foreach ($sites as $site)
                                                <option value="{{ $site->id }}" {{ ($site->id === $findSite->id ? 'selected' : '') }}>{{ $site->site_name }} </option>
                                                @endforeach
                                            </select>
                                            <div class="form-control-icon">
                                                <i class="bi bi-hammer"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Gender</label>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="position-relative">
                                            <fieldset class="form-group">
                                                <select class="form-select @error('gender') is-invalid @enderror" value="{{ $employee->gender }}" id="gender" name="gender">
                                                    <option value='male'>Male</option>
                                                    <option value='female'>Female</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Job Title</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="text" class="form-control @error('jobTitle') is-invalid @enderror" value="{{ $employee->job_title }}" placeholder="Job Title"
                                                name="jobTitle" value="{{ old('jobTitle') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@error('jobTitle'){{ $message }}@enderror">
                                            <div class="form-control-icon">
                                                <i class="bi bi-person-badge "></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Daily Rate</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="number" class="form-control @error('dailyRate') is-invalid @enderror" placeholder="Daily Rate in Peso"
                                                name="dailyRate" value="{{ $employee->daily_rate }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@error('dailyRate'){{ $message }}@enderror" >
                                            <div class="form-control-icon">
                                                <i class="bi bi-cash"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Address</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" placeholder="Address"
                                                name="address" value="{{ $employee->address }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@error('address'){{ $message }}@enderror" >
                                            <div class="form-control-icon">
                                                <i class="bi bi-house"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Contact Number</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group has-icon-left">
                                        <div class="position-relative">
                                            <input type="text" class="form-control @error('contactNumber') is-invalid @enderror" placeholder="Contact Number"
                                                name="contactNumber" value="{{ $employee->contact_number }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="@error('contactNumber'){{ $message }}@enderror">
                                            <div class="form-control-icon">
                                                <i class="bi bi-phone"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{route('employees.list')}}" type="submit" class="btn btn-primary me-1 mb-1">Back to View</a>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- <div class="container-fluid ">
    <div class="row ">
        <div class="col-md-3"></div>
        <div class="col-md-6 ">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <form action="{{ route('employees.store') }}" method="POST"
                class="border border-secondary border-2 border-lg-3 p-4 ">
                @csrf
                <h3>Employee Personal Information</h3>
                <div class="form-group row ">
                    <label for="firstName" class="col-sm-3 text-right p-0">First Name</label>
                    <div class="col-sm-9 ">
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="middleName" class="col-sm-3 text-right p-0">Middle Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="middleName" name="middleName" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lastName" class="col-sm-3 text-right p-0">Last Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                    </div>
                </div>

                <div class="mb-3 form-group row ">
                    <label for="gender" class="col-sm-3 text-right p-0">Gender</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 form-group row ">
                    <label for="jobTitle" class="col-sm-3 text-right p-0">Job Title</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="jobTitle" name="jobTitle" required>
                    </div>
                </div>

                <div class="mb-3 form-group row ">
                    <label for="dailyRate" class="col-sm-3 text-right p-0">Daily Rate</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="dailyRate" name="dailyRate" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-sm-3 text-right p-0">Address</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="contactNumber" class="col-sm-3 text-right p-0">Contact Number</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end ">
                    <button type="submit" class="btn btn-primary mr-4 ">Add Employee</button>
                    <a href="{{ route('employees.list') }}" class="btn btn-primary ml-5 ">Go to Employee List</a>
                </div>
            </form>
        </div>
    </div>

</div> --}}
@endsection