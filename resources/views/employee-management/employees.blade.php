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
<section class="section">
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
        <section class="section">
            <div class=" table-responsive col-12">
                <table class="table bordered bg-white">
                    <thead class="alig-text-center">
                        <tr>
                            <th class="border text-center">Name </th>
                            <th class="border text-center">Gender</th>
                            <th class="border text-center">Job Title</th>
                            <th class="border text-center">Daily Rate</th>
                            <th class="border text-center">Address</th>
                            <th class="border text-center">Contact Number</th>
                            <th class="border text-center">Site Location</th>
                            <th class="border text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($getEmployee as $key => $employee)
                        <tr>
                            <td class="d-none">{{ $employee->employee_id }}</td>
                            <td class="col-2 border ">{{ $employee->first_name }} {{ $employee->middle_name }} {{
                                $employee->last_name }}</td>
                            <td class="col-1 border">{{ $employee->gender }}</td>
                            <td class="col-2 border">{{ $employee->job_title }}</td>
                            <td class="col-1 border">{{ $employee->daily_rate }}</td>
                            <td class="col-2 border">{{ $employee->address }}</td>
                            <td class="col border">{{ $employee->contact_number }}</td>
                            @if(!empty($employee->site_name))
                            <td class="col-1 border">{{ $employee->site_name }}</td>
                            @else
                            <td class="col-3">
                                {{-- <p class="text-center">{{ $employee->first_name }} is not assigned to any site</p> --}}
                                <form action="{{ route('employees.addSite') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="empID" value="{{ $employee->employee_id }}">
                                    <div class="form-group row ">
                                        <div class="col-3"></div>
                                        <div class="col-sm-6">
                                            <select class="form-control form-select" name="working_site" required>
                                                <option value="">Select Site</option>
                                                @foreach ($sites as $site)
                                                <option value="{{ $site->id }}" {{ ($site->id ===
                                                    $employee->working_site_id) ? 'selected' : '' }}>
                                                    {{ $site->site_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-center ">
                                            <button type="submit" class="btn btn-sm-primary mr-4 ">
                                                <i class="bi bi-save " style="font-size: 2rem;"></i>
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </td>

                            @endif

                            <td class="col-1 border">
                                <div class="d-flex justify-content-center align-items-center" style="height: 100%">
                                    <a href="{{ route('employees.edit', ['empid' =>  $employee->employee_id]) }}">
                                        <span class="bi bi-pencil-square" style="font-size: 2rem; margin-right: 0.5rem;"
                                            data-toggle="tooltip" title="Edit"></span>
                                    </a>
{{  $employee->employee_id }}
                                    <a href="{{ route('employees.show', ['empid' =>  $employee->employee_id]) }}">
                                        <span class="bi bi-eye" style="font-size: 2rem;" data-toggle="tooltip"
                                            title="View"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="row mt-1">
                <div class="col">
                    {!! $getEmployee->onEachSide(4)->links('pagination::bootstrap-5') !!}
                </div>
            </div>
    </div>
</section>

@endsection
