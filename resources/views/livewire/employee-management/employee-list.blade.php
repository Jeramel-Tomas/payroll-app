<div>
    <section class="section">
        <div class="text-center">
            <h1 class="text-center text-uppercase fs-3 text mb-1 fw-bolder">
                @if ($workingSite === 0)
                All Site Employees
                @else
                {{ $workingSiteName }} Employees
                @endif
            </h1>
        </div>
            <div class="container-fluid card">

                {{-- Session Message handlers start --}}
                @if (session()->has('success'))
                
                <div class="alert alert-success mt-2">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error') && session('error_expires_at'))
                <div class="alert alert-error mt-2">
                    {{ session('error') }}
                </div>
                @endif
                @if(session('danger') && session('danger_expires_at'))
                <div class="alert alert-danger mt-2">
                    {{ session('danger') }}
                </div>
                @endif

                @error('importedUsers')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                {{-- Session Message handlers end --}}
                
                <div class="card-content row  p-0 flex-wrap">
                    <div class="col-lg-4 col-md-4 col-sm-4 p-2">
                        <div class="input-group">
                            <select class="choices form-select" wire:model="workingSite">
                                <option value="0">Filter by site...</option>
                                @foreach ($sites as $site)
                                <option value="{{$site->id}}">{{ $site->site_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col col-lg-6 col-md-6 col-sm-6 d-flex justify-content-start align-items-center p-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Employee name..."
                                aria-label="Recipient's username" aria-describedby="button-addon2" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="i.e. (Last name, First name, or both)"
                                wire:model.debounce.3000="searchString">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body col-lg-2 col-md-2 col-sm-2 p-2 ">
                        <div class="col-lg-12 col-md-12 col-sm-12  ml-2 p-12 d-flex justify-content-end">
                            <a class="btn btn-warning " href="#" wire:click.prevent = "clearFilter()">Clear all Filter</a>
                        </div>
                        
                    </div>

                    
                </div>
                
                <hr class="p-0 m-0">
                <div class="card-content row  p-2 ">
                    <div class="col-12 col-md-6 p-2 d-flex justify-content-start">
                        {{-- <a href="{{ route('download.template') }}" type=" submit" class="btn btn-secondary"
                            id="template" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Download template for mass employee upload">Generate Template
                        </a> --}}
                    </div>
                    <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center ml-md-2" id="addForm">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            Add Employee
                        </a>
                        <button type="submit" class="btn btn-success ms-3" id="openForm" disabled>
                            Upload Employee Information
                        </button>
                    </div>

                    <div class="d-none p-2 col-12 col-md-6 d-flex justify-content-end align-items-center" id="fileForm">
                        <form class="form form-horizontal " action="{{ route('import') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-12 d-flex align-items-center">
                                <input type="file" name="importedUsers"
                                    class="form-control @error('importedUsers') is-invalid @enderror"
                                    value="{{ old('importedUsers') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="@error('importedUsers'){{ $message }}@enderror">
                                <button type="submit" class="btn btn-primary ms-2" id="fileFormSubmit">Upload</button>
                            </div>
                        </form>
                    </div>
                    
                    
                    
                </div>


                <div class=" table-responsive col-12  ">
                    {{-- Table start --}}
                    <table class="table bordered bg-white table-hover">
                        <thead class="align-text-center bg-secondary text-white ">
                            <tr>
                                <th class="border text-center">Name </th>
                                <th class="border text-center">Gender</th>
                                <th class="border text-center">Job Title</th>
                                <th class="border text-center">Daily Rate</th>
                                <th class="border text-center">Site Location</th>
                                {{-- <th class="border text-center">Contact Number</th> --}}
                                {{-- <th class="border text-center">Site Location</th> --}}
                                {{-- <th class="border text-center">Employment Status</th> --}}
                                <th class="border text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($getEmployees->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">{{ $noResultsMessage }}</td>
                                </tr>
                            @else
                                @foreach($getEmployees as $key => $employee)
                                <tr>
                                    <td class="d-none">{{ $employee->employee_id }}</td>
                                    <td class="auto" wire:model="emp_name">{{ Str::ucfirst(Str::lower($employee->first_name)) }} {{ Str::ucfirst(Str::lower($employee->last_name)) }}</td>
                                    <td class="col-1 border">{{ Str::ucfirst(Str::lower($employee->gender)) }}</td>
                                    <td class="auto border">
                                        @php
                                            $jobTitle = DB::table('working_sites')
                                                ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                                ->where('employee_working_sites.employee_information_id', $employee->employee_id)
                                                ->get();
                                        @endphp
                                        <ol class="">
                                            @if($jobTitle->isEmpty())
                                                <p class="text-warning dance-animation">No Job Title</p>
                                            @else
                                                @foreach ($jobTitle as $item)
                                                <li class="{{$item->job_title ?? 'text-danger'}}">{{$item->job_title ?? 'Not set'}}</li>
                                                @endforeach
                                            @endif
                                        </ol>
                                    </td>
                                    <td class="auto border" wire:model="emp_rate">
                                        @php
                                            $rate = DB::table('working_sites')
                                                ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                                ->where('employee_working_sites.employee_information_id', $employee->employee_id)
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
                                    </td>
                                    <td class="auto border" wire:model="emp_site">
                                        @php
                                            $siteName = DB::table('working_sites')
                                                ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                                ->where('employee_working_sites.employee_information_id', $employee->employee_id)
                                                ->get();
                                        @endphp
                                        <ol class="">
                                        @if($siteName->isEmpty())
                                            <p class="text-warning dance-animation">No Site Assigned</p>
                                        @else
                                        @foreach ($rate as $item)
                                            <li class="{{$item->site_name ?? 'text-danger'}}">{{$item->site_name ?? 'Not set'}}</li>
                                            @endforeach
                                        @endif
                                        </ol>
                                    </td>
                                    {{-- <td class="col border">{{ $employee->contact_number }}</td> --}}
                                    {{-- @if(!empty($employee->site_name))
                                    <td class="col-1 border">{{ $employee->site_name }}</td>
                                    @else --}}
                                    {{-- <td class="col-3"> --}}
                                        {{-- <p class="text-center">{{ $employee->first_name }} is not assigned to any site</p>
                                        --}}
                                        {{-- <form action="{{ route('employees.addSite') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="empID" value="{{ $employee->employee_id }}">
                                            <div class="form-group row ">
                                                <div class="col-3"></div>
                                                <div class="col-sm-6">
                                                    <select class="form-control form-select" name="working_site" required
                                                        wire:model="assignSite">
                                                        <option value="">Select Site</option>
                                                        @foreach ($sites as $site)
                                                        <option value="{{ $site->id }}::{{$employee->employee_id}}" {{ ($site->
                                                            id ===
                                                            $employee->working_site_id) ? 'selected' : '' }}>
                                                            {{ $site->site_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                
                                            </div>

                                        </form> --}}
                                    {{-- </td> --}}
                                    
                                    {{-- @endif --}}
                                    {{-- <td class="col border">{{ isset($employee->employment_date) && $employee->employment_date === null || $employee->employment_date === ""? "unemployed": $employee->employment_date  }}</td> --}}
                                    <td class="col-1 border">
                                        <div class="d-flex justify-content-center align-items-center" style="height: 100%">
                                            <a href="{{ route('employees.edit', ['empid' =>  $employee->employee_id]) }}">
                                                <span class="bi bi-pencil-square" style="margin-right: 0.5rem;"
                                                    data-toggle="tooltip" title="Edit"></span>
                                            </a>
                                            <a href="{{ route('employees.show', ['empid' =>  $employee->employee_id]) }}">
                                                <span class="bi bi-eye-fill" data-toggle="tooltip"
                                                    title="View"></span>
                                            </a>
                                        </div>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{-- Table end --}}

                    {{-- Export Testing start hidden--}}
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6 d-none">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Export Employee Information</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form form-horizontal" action="{{ route('export') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <a href="{{ route('export') }}" class="btn btn-primary">Export</a>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- Export Testing end --}}
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-start align-items-center">
                        <strong>Total: </strong> <small>{{ $getEmployees->total() }}</small>
                    </div>
                    <div class="col d-flex justify-content-end">
                        {{ $getEmployees->links() }}
                    </div>
                </div>
            </div>
    </section>
</div>