@extends('../layout/layout')
@section('main-content')

<div class="container-fluid ">
    <h1>Add Employee</h1>
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

</div>
@endsection