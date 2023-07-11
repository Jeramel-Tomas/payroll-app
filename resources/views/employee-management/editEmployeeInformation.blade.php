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
    <h1>Edit Employee Information</h1>
    <form action="{{ route('employees.update', ['empid' => $employee->id]) }}" method="POST">
        {{-- {{ route('employees.update') }} --}}
        @csrf
        @method('PATCH')
        <!-- Display the employee information for editing -->
        <input type="text" class="form-control d-none" id="emp_id" name="emp_id" value="{{ $employee->id }}">
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" value="{{ $employee->first_name }}">
        </div>

        <div class="mb-3">
            <label for="middleName" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middleName" name="middleName"
                value="{{ $employee->middle_name }}">
        </div>

        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" value="{{ $employee->last_name }}">
        </div>

        <!-- Add other fields for editing the employee information -->

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection