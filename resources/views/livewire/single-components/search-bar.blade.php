<div>

    <section class="section">
        <div class="text-center">
            <h1 class="text-center text-uppercase fs-3 text mb-0 fw-bolder"> 
                {{-- {{ $siteName }} --}}
                @if ($workingSite == 0)
                    All Site Employees
                @else
                {{ $siteName }} Employees
                @endif
            </h1>
            {{-- <h1 class="mb-5">Employee Data</h1> --}}
        </div>
        <div class="container card ">
            {{-- @dump($workingSite) --}}
            
            {{-- Session Message handlers start --}}
            @if (session()->has('success'))
            {{-- @if(session('success') && session('success_expires_at') ) --}}
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
            {{-- Session Message handlers end --}}
            <div class="card-content row  p-0">
                <div class="card-body col-lg-2 col-md-2 col-sm-2">
                    <div class="input-group">
                        <select class="choices form-select" wire:model="workingSite">
                            <option value="">All Employees...</option>
                            @foreach ($sites as $site)
                            <option value="{{$site->id}}">{{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- @json($workingSite) --}}
                </div>
                <div class="col col-lg-4 col-md-4 col-sm-4 d-flex justify-content-start align-items-center">
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
                <div class="card-body col-lg-2 col-md-2 col-sm-2">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-secondary " id="template" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Download template for mass employee upload">
                            Generate Template
                        </button>
                    </div>
                </div>
                <div class="card-body col-lg-4 col-md-4 col-sm-4">
                    <div class="col-12 d-flex justify-content-end align-items-center ml-2" id="addForm">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            Add Employee
                        </a>
                        <button type="submit" class="btn btn-success ms-3" id="openForm">
                            Upload Employee Information
                        </button>
                    </div>

                    <div class="d-none" id="fileForm">
                        <form class="form form-horizontal " action="{{ route('import') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex align-items-center">
                                <input type="file" name="importedUsers"
                                    class="form-control @error('importedUsers') is-invalid @enderror"
                                    value="{{ old('importedUsers') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="@error('importedUsers'){{ $message }}@enderror">
                                <button type="submit" class="btn btn-primary ms-2" id="fileFormSubmit">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row d-flex justify-content-end mb-2">
                    <div class="col-2 col-sm-2 d-flex justify-content-end">
                        
                    </div>
                </div>
            </div>
            

            <div class=" table-responsive col-12 font-bold">
                {{-- Table start --}}
                <table class="table bordered bg-white table-hover table-sm">
                    <thead class="align-text-center">
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
                        {{-- @dump($employee->employee_id) --}}
                        <tr>
                            <td class="d-none">{{ $employee->employee_id }}</td>
                            <td class="col-2 border ">{{ Str::ucfirst(Str::lower($employee->first_name)) }} {{
                                Str::ucfirst(Str::lower($employee->middle_name)) }} {{
                                Str::ucfirst(Str::lower($employee->last_name)) }}</td>
                            <td class="col-1 border">{{ Str::ucfirst(Str::lower($employee->gender)) }}</td>
                            <td class="col-2 border">{{ Str::ucfirst(Str::lower($employee->job_title)) }}</td>
                            <td class="col-1 border">{{ Str::ucfirst(Str::lower($employee->daily_rate)) }}</td>
                            <td class="col-2 border">{{ Str::ucfirst(Str::lower($employee->address)) }}</td>
                            <td class="col border">{{ $employee->contact_number }}</td>
                            @if(!empty($employee->site_name))
                            <td class="col-1 border">{{ $employee->site_name }}</td>
                            @else
                            <td class="col-3">
                                {{-- <p class="text-center">{{ $employee->first_name }} is not assigned to any site</p>
                                --}}
                                <form action="{{ route('employees.addSite') }}" method="POST">
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
                                        {{-- <div class="d-flex justify-content-center ">
                                            <button type="submit" class="btn btn-sm-primary mr-4 ">
                                                <i class="bi bi-save " style="font-size: 2rem;"></i>
                                            </button>
                                        </div> --}}
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
            <div class="row mt-1">
                <div class="col d-flex justify-content-end">
                    {{-- {!! $getEmployee->onEachSide(4)->links('pagination::bootstrap-5') !!} --}}
                    {{ $getEmployee->links() }}
                </div>
            </div>
        </div>
    </section>

</div>
