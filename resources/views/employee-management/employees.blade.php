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

    {{-- @dd($getEmployee) --}}
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
        
                @foreach($getEmployee as $key => $employee)

                <tr>
                    <td class="d-none">{{ $employee->id }}</td>
                    <td class="col-2 border">{{ $employee->first_name }} {{ $employee->middle_name }} {{
                        $employee->last_name }}</td>
                    <td class="col-1 border">{{ $employee->gender }}</td>
                    <td class="col-2 border">{{ $employee->job_title }}</td>
                    <td class="col-1 border">{{ $employee->daily_rate }}</td>
                    <td class="col-2 border">{{ $employee->address }}</td>
                    <td class="col border">{{ $employee->contact_number }}</td>
                    <td class="col-1 border">{{ $employee->site_name }}</td>

                    <td class="col-1 border">
                        <a href="{{ route('employees.edit', ['empid' =>  $employee->employee_id]) }}">
                            <span class="bi bi-pencil-square" data-toggle="tooltip" title="Edit"></span>
                        </a>
                        <a href="{{ route('employees.show', ['empid' =>  $employee->employee_id]) }}">
                            <span class="bi bi-eye" data-toggle="tooltip" title="View"></span>
                        </a>
                    </td>
                    <td class="col-3">
                        <form action="{{ route('employees.addSite') }}" method="POST">
                            @csrf
                            <input type="hidden" name="empID" value="{{ $employee->employee_id }}">
                            <div class="form-group row ">
                                <div class="col-sm-12">
                                    <select class="form-control form-select" name="working_site" required>
                                        @foreach ($sites as $site)
                                        <option value="{{ $site->id }}" {{ ($site->id === $employee->working_site_id) ? 'selected' : '' }}>
                                            {{ $site->site_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end ">
                                <button type="submit" class="btn btn-sm-primary mr-4 ">
                                    <i class="bi bi-save" ></i>
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="row mt-5">
        <div class="col">
            {{-- {!! $getEmployee->onEachSide(4)->links('pagination::bootstrap-4') !!} --}}
        </div>
    </div>
</div>

@endsection