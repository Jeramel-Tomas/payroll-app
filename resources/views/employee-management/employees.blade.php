@extends('../layout/layout')

@push('sites-leftside-menu')
@foreach ($sites as $site)
<li class="submenu-item ">
    <a href="{{ route('attendance.showlog.persite', ['siteId' => $site->id]) }}">{{
        $site->site_name }}</a>
</li>
@endforeach
@endpush

@section('main-content')
<div class="container">
    <h1>Employee Data</h1>
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


    <div class=" table-responsive">
        <table class="table border">
            <thead>
                <tr>
                    <th class="border ">Name</th>
                    <th class="border ">Gender</th>
                    <th class="border">Job Title</th>
                    <th class="border">Daily Rate</th>
                    <th class="border">Address</th>
                    <th class="border">Contact Number</th>
                    <th class="border">Site Location</th>
                    <th class="border">Action</th>
                    <th class="border">Assign to Site</th>
                </tr>
            </thead>
            <tbody>

                @foreach($employees as $key => $employee)
                <tr>
                    <td class="d-none">{{ $employee->id }}</td>
                    <td class="col-3 border">{{ $employee->first_name }} {{ $employee->middle_name }} {{
                        $employee->last_name }}</td>
                    <td class="col-2 border">{{ $employee->gender }}</td>
                    <td class="col-2 border">{{ $employee->job_title }}</td>
                    <td class="col-2 border">{{ $employee->daily_rate }}</td>
                    <td class="col-2 border">{{ $employee->address }}</td>
                    <td class="col-2 border">{{ $employee->contact_number }}</td>
                    <td class="col-2 border">{{ $employee->address }}</td>

                    <td class="">
                        <a href="{{ route('employees.edit', ['empid' =>  $employee->id]) }}">
                            <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                        </a>
                        <a href="{{ route('employees.show', ['empid' =>  $employee->id]) }}">
                            <i class="material-icons" data-toggle="tooltip" title="View">visibility</i>
                        </a>
                    </td>
                    <td>
                        {{-- -----------------minor changes starts here-----------------}}
                        <form action="{{ route('employees.addSite') }}" method="POST">
                            @csrf
                            <input type="hidden" name="empID" value="{{ $employee->id }}">
                            <div class="form-group row ">
                                <div class="col-sm-12">
                                    <select class="form-control" name="working_site" required>
                                        @foreach ($sites as $site)
                                        <option value="{{ $site->id }}">{{ $site->site_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end ">
                                <button type="submit" class="btn btn-primary mr-4 material-icons ">save</button>
                            </div>
                        </form>
                        {{-- -----------------some changes ends here--------------- --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="row mt-5">
        <div class="col">
            {!! $employees->onEachSide(4)->links('pagination::bootstrap-4') !!}
        </div>
    </div>
</div>

@endsection