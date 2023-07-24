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
<h1 class="text-center">Add Employee</h1>
@endsection

@section('page-content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<section class="section">
    <div class="row">
        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Fill up Employee Information</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('employees.store', ['siteId' => $site->id]) }}" method="POST" id="employeeForm">
                            @csrf
                            <div class="form-body ">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>First Name</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left ">
                                            <div class="position-relative">

                                                <input type="text"
                                                    class="form-control @error('firstName') is-invalid @enderror"
                                                    placeholder="First Name" id="firstName" name="firstName"
                                                    value="{{ old('firstName') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
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
                                                <input type="text"
                                                    class="form-control @error('middleName') is-invalid @enderror"
                                                    placeholder="Middle Name" id="middleName" name="middleName"
                                                    value="{{ old('middleName') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
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
                                                <input type="text"
                                                    class="form-control @error('lastName') is-invalid @enderror"
                                                    placeholder="Last Name" id="lastName" name="lastName"
                                                    value="{{ old('lastName') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="@error('lastName'){{ $message }}@enderror">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Gender</label>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <fieldset class="form-group">
                                                    <select class="form-select @error('gender') is-invalid @enderror"
                                                        value="{{ old('gender') }}" id="gender" name="gender">
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
                                                <input type="text"
                                                    class="form-control @error('jobTitle') is-invalid @enderror"
                                                    value="{{ old('jobTitle') }}" placeholder="Job Title"
                                                    name="jobTitle" value="{{ old('jobTitle') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
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
                                                <input type="number"
                                                    class="form-control @error('dailyRate') is-invalid @enderror"
                                                    placeholder="Daily Rate in Peso" name="dailyRate"
                                                    value="{{ old('dailyRate') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="@error('dailyRate'){{ $message }}@enderror">
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
                                                <input type="text"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    placeholder="Address" name="address" value="{{ old('address') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@error('address'){{ $message }}@enderror">
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
                                                <input type="text"
                                                    class="form-control @error('contactNumber') is-invalid @enderror"
                                                    placeholder="Contact Number" name="contactNumber"
                                                    value="{{ old('contactNumber') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="@error('contactNumber'){{ $message }}@enderror">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-phone"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Date of Employment</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="date"
                                                    class="form-control @error('DOE') is-invalid @enderror"
                                                    placeholder="Date of Employment" name="DOE"
                                                    value="{{ old('DOE') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="@error('DOE'){{ $message }}@enderror">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-phone"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Site Location</label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-control form-select" name="site_loc" id="site_loc">
                                                    @foreach ($sites as $site)
                                                    
                                                    <option value="{{ $site->id }}" data-site-id="{{ $site->id }}" >
                                                        {{ $site->site_name }} 
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Upload Employee Information</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="importedUsers" class="form-control @error('importedUsers') is-invalid @enderror"
                            value="{{ old('importedUsers') }}" data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="@error('importedUsers'){{ $message }}@enderror" >
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mt-3">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        const form = document.getElementById('employeeForm');
        const siteLocSelect = document.getElementById('site_loc');
        const handleSiteLocChange = function () {
            const selectedSiteId = siteLocSelect.value;
            //console.log("Selected Site ID:", selectedSiteId);
            const currentAction = "{{ route('employees.store', ['siteId' => ':siteId']) }}";
            const newAction = currentAction.replace(':siteId', selectedSiteId);
            form.action = newAction;

            console.log("Form Action:", form.action);
        };

        if (siteLocSelect) {
            siteLocSelect.addEventListener('change', handleSiteLocChange);
        }
    });
    //console.log('test');
</script>




@endsection