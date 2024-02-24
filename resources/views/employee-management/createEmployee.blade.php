@extends('../layout/layout')

@push('sites-leftside-menu')
@foreach ($sites as $site)
<li class="submenu-item ">
    {{-- <a href="{{ route('employees.store', ['siteId' => $site->id]) }}">{{
        $site->site_name }}</a> --}}
</li>
@endforeach
@endpush

@section('page-heading')
<h1 class="text-center">Add Employee</h1>
@endsection

@section('page-content')
@if(session('success') && session('success_expires_at'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
<script>
    setTimeout(function() {
            document.querySelector('.alert-success').style.display = 'none';
        }, {{ now()->diffInMilliseconds(session('success_expires_at')) }});
</script>
@endif
@if(session('danger') && session('danger_expires_at'))
<div class="alert alert-danger">
    {{ session('danger') }}
</div>
<script>
    setTimeout(function() {
            document.querySelector('.alert-danger').style.display = 'none';
        }, {{ now()->diffInMilliseconds(session('danger_expires_at')) }});
</script>
@endif
<section class="section">
    <div class="row">
        <div class="col col-2"></div>
        <div class="col-8 col-sm-8 col-md-8 col-lg-8">
            <div class="card border shadow p-3 mb-5 bg-body-tertiary rounded">
                <div class="card-header p-0">
                    <h2 class="text-start mb-0">Fill up Employee Information</h2>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal"
                            action="{{ route('employees.store') }}" method="POST"
                            id="employeeForm">
                            @csrf
                            <div class="form-body ">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="text-end">First Name</p>
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
                                        <p class="text-end">Middle Name</p>
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
                                        <p class="text-end">Last Name</p>
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
                                        <p class="text-end">Gender</p>
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
                                    {{-- <div class="col-md-4">
                                        <p class="text-end">Job Title</p>
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
                                    </div> --}}
                                    {{-- <div class="col-md-4">
                                        <p class="text-end">Daily Rate</p>
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
                                    </div> --}}
                                    <div class="col-md-4">
                                        <p class="text-end">Address</p>
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
                                        <p class="text-end">Contact Number</p>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text"
                                                    class="form-control @error('contactNumber') is-invalid @enderror"
                                                    placeholder="Contact Number" name="contactNumber"
                                                    value="{{ is_null(old('contactNumber')) ? '+639' : old('contactNumber') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@error('contactNumber'){{ $message }}@enderror">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-phone"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-end">Date of Employment</p>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="date"
                                                    class="form-control @error('DOE') is-invalid @enderror"
                                                    placeholder="Date of Employment" name="DOE" value="{{ old('DOE') ?: date('Y-m-d') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@error('DOE'){{ $message }}@enderror">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-phone"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <p class="text-end">Site Location</p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-control form-select" name="site_loc" id="site_loc">
                                                    @foreach ($sites as $site)
                                                    <option value="{{ $site->id }}" data-site-id="{{ $site->id }}">
                                                        {{ $site->site_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}
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
        <div class="col-2 col-sm-2 col-md-2 col-lg-2">
        </div>
    </div>

</section>

@push('js-code')
    // Script used to update the id that is to be passed into the form
    // siteId will change according to the selected dropdown siteId
    document.addEventListener('DOMContentLoaded', function () {
        
        const form = document.getElementById('employeeForm');
        const siteLocSelect = document.getElementById('site_loc');
        
        const handleSiteLocChange = function () {
            const selectedSiteId = siteLocSelect.value;

            const currentAction = "{{ route('employees.store', ['siteId' => ':siteId']) }}";
            const newAction = currentAction.replace(':siteId', selectedSiteId);
            form.action = newAction;

            console.log("Form Action:", form.action);
        };

        if (siteLocSelect) {
            siteLocSelect.value = 1;
            siteLocSelect.addEventListener('change', handleSiteLocChange);
            siteLocSelect.dispatchEvent(new Event('change'));
        }
    });
@endpush





@endsection