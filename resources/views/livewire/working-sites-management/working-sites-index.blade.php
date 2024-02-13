<div>
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="text-end">{{\Carbon\Carbon::now()->toFormattedDateString()}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-1"></div>
                                <div class="col-md-6 align-items-center">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Site name..."
                                            aria-describedby="button-addon2"
                                            wire:model.debounce.3000="searchString">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-2 align-items-center">
                                    <div class="input-group">
                                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#createSite">
                                            Create
                                        </button>
                                    </div>
                                </div>
                            </div>
    
                            <div class="table-responsive" style="width: 80%; margin: auto;">
                                <table class="table table-hover table-bordered align-items-center">
                                    <thead>
                                        <tr>
                                            <th>Site name</th>
                                            <th># employees</th>
                                            <th data-bs-toggle="tooltip" title="Show assigned employees | Add employee | Delete site">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sites as $site)
                                        <tr>
                                            <td class="col-auto">
                                                <div class="d-flex align-items-center">
                                                   {{ $site->site_name }}
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    {{ $site->emp_count }}
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    {{-- unassigned emp and assign --}}
                                                    
                                                    <a href="{{route('working.site.assigned.employees', ['siteId' => $site->id])}}" >
                                                        <span data-bs-toggle="tooltip" title="Show assigned employees">
                                                            <i class="bi bi-person-lines-fill"></i>
                                                        </span>
                                                    </a>  &nbsp; | &nbsp;
                                                    
                                                    <a href="#" wire:click.prevent="addEmployeeToSiteModal({{$site->id}}, '{{$site->site_name}}')"
                                                        data-bs-toggle="modal" data-bs-target="#addEmployeeToSite">
                                                        <span data-bs-toggle="tooltip" title="Add employee">
                                                            <i class="bi bi-person-plus"></i>
                                                        </span>
                                                    </a>  
                                                    &nbsp; | &nbsp;
                                                    <a href="#" 
                                                        wire:click.prevent="editSiteName({{$site->id}}, '{{$site->site_name}}')" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalToEdit">
                                                        <span data-bs-toggle="tooltip" title="Edit site name">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </span>
                                                    </a>
                                                    &nbsp; | &nbsp; &nbsp; &nbsp; &nbsp;
                                                    <a href="#" 
                                                        class="float-end"
                                                        wire:click.prevent="confirmSiteDelete({{$site->id}}, '{{$site->site_name}}')" 
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#confirmDelete"
                                                        >
                                                        <span data-bs-toggle="tooltip" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
    
                            <div class="row" style="width: 80%; margin: auto;">
                                <div class="col d-flex justify-content-start align-items-center">
                                    <strong>Total: </strong> &nbsp; {{ $sites->total() }}
                                </div>
                                <div class="col d-flex justify-content-end">
                                    {{ $sites->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- wire:ignore.self --}}
    <!-- Modal Create Site -->
    <div wire:ignore.self class="modal fade" id="createSite" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Create Working site
                    </h5>
                    <button type="button" wire:click.stop="resetProps()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>
                                    Site name:
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" wire:model="siteNameValue" class="form-control"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Required">
                            </div>
                        </div>
                    </div>
    
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.stop="resetProps()" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    @if ($siteNameValue)
                    <button type="button" wire:click="saveSiteName()" class="btn btn-primary"
                        data-bs-dismiss="modal">Save</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirm Delete Site -->
    <div wire:ignore.self class="modal fade" id="confirmDelete" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header alert alert-warning">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Confirm action
                    </h5>
                    <button type="button" wire:click.stop="resetProps()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="">
                        @if ($employeeCount > 0)
                            <p>
                                Sorry you cannot delete this site! &nbsp;
                                <strong>{{$siteName}}</strong> working site 
                                {{ $employeeCount > 1 
                                    ? ' have ' . $employeeCount .' Employees ' 
                                    : ' has ' . $employeeCount . ' Employee'}} 
                                 assigned to it! 
                            </p>
                        @else
                        <p>
                            You are about to permanently remove {{$siteName}}
                            Working site.
                        </p>
                        @endif
                    </div>
    
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.stop="resetProps()" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    @if ($employeeCount === 0)
                        <button type="button" wire:click="deleteSite({{$siteId}})" class="btn btn-danger"
                            data-bs-dismiss="modal">Confirm</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Employee to a Site -->
    <div wire:ignore.self class="modal fade" id="addEmployeeToSite" tabindex="-1" aria-labelledby="addEmployeeToSite"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header alert alert-info">
                    <h5 class="modal-title" id="addEmployeeToSite">
                        Add employee to {{$siteNameModalToAddEmp}}
                    </h5>
                    <button type="button" wire:click.stop="resetProps()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" >
                    <div class="text-center"
                        {{-- wire:loading.delay.longest --}}
                        wire:loading.delay.longest 
                        wire:target="saveEmployeesToSite({{$siteIdModalToAddEmp}})">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-center">Updating {{$siteNameModalToAddEmp}}...</p>
                    </div>
                    @if (session()->has('message'))
                    <div class="row">
                        <div class="alert alert-success d-flex  justify-content-between" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <div class="text-center">
                                {{ session('message') }}
                            </div>
                            <i role="button" data-bs-dismiss="alert" class="bi bi-x"></i>
                        </div>
                    </div>
                    @endif
                    <div class="form-body mb-4"  wire:loading.remove>
                        <div class="row">
                            <div class="col-md-4">
                                <label>
                                    Search employee:
                                </label>
                            </div>
                            <div class="col-md-8 form-group">
                                
                                {{-- @dump($employees->items) --}}
                                <input type="text" 
                                    class="form-control" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Employees" placeholder="Search employees..."
                                    autocomplete="off"
                                    wire:model.debounce.500ms="searchQueryString" 
                                    wire:keydown.escape="resetProps()"
                                    {{!empty($searchQueryString) ? 'onblur="this.focus()"'. 'autofocus':'' }}
                                    >
                                <div wire:loading class="px-3 py-2">
                                    <div>
                                        Searching...
                                    </div>
                                </div>
                                @if ($searchQueryString)
                                {{-- <div class="{{!empty($searchQueryString) ? 'd-inline' : 'd-none'}}"> --}}
                                <div>
                                    <ul class="list-unstyled border-start border-end border-bottom">
                                        @if (count($employees) > 0)
                                            @foreach ($employees as $employee)
                                            <li class="px-3 py-2" role="button"
                                                wire:click="selectEmployee({{$employee->id}})"
                                            >
                                                {{$employee->first_name}} {{$employee->last_name}}
                                            </li>
                                            @endforeach
                                        @else
                                            <li class="px-3 py-2">No reslut!</li>
                                        @endif
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if (count($selectedEmps) > 0)  
                        <hr> 
                        @if (count($existingEmployeesInWorkingSite) > 0)
                        <div class="row">
                            <div class="col">
                                <ul class="list-group">
                                    <li class="list-group-item active bg-warning" aria-current="true">
                                        List of employees already added
                                    </li>
                                    @foreach ($existingEmployeesInWorkingSite as $empName)
                                        <li class="list-group-item text-warning">
                                            {{$empName}}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div> 
                        @endif

                        <div class="row my-4">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <ul class="list-group">
                                    <li class="list-group-item active" aria-current="true">List of employees to be added</li>
                                    @foreach ($selectedEmps as $key=>$selectedEmp)
                                        <li class="list-group-item">
                                            {{$selectedEmp}}
                                            <span role="button"
                                                wire:click="removeFromSelectedEmp({{$key}})" 
                                                class="float-end">
                                                <i class="bi bi-trash"></i>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        @endif
                    </div>
    
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.stop="resetProps()" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    @if (count($selectedEmps) > 0 && count($existingEmployeesInWorkingSite) === 0)
                        <button 
                            type="button" 
                            wire:click="saveEmployeesToSite({{$siteIdModalToAddEmp}})" 
                            class="btn btn-primary"
                            >
                            Add
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal to update site --}}
    <div wire:ignore.self class="modal fade" id="modalToEdit" tabindex="-1" aria-labelledby="addEmployeeToSite"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header alert alert-info">
                    <h5 class="modal-title" id="addEmployeeToSite">
                        Updating {{$siteNameToEdit}}
                    </h5>
                    <button 
                        type="button" 
                        wire:click.stop="resetProps()" 
                        class="btn-close" 
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <div class="text-center" wire:loading.delay.longest
                        wire:target="saveEmployeesToSite({{$siteIdModalToAddEmp}})">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div> --}}
                    @if (session()->has('message'))
                    <div class="row">
                        <div class="alert alert-success d-flex  justify-content-between" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <div class="text-center">
                                {{ session('message') }}
                            </div>
                            <i role="button" data-bs-dismiss="alert" class="bi bi-x"></i>
                        </div>
                    </div>
                    @endif
                    <div class="form-body mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label>
                                    Site name:
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input 
                                    type="text" 
                                    wire:model.debounce.3000="siteNameToEdit"
                                    value="{{$siteNameToEdit ?? ''}}"
                                    class="form-control {{empty($siteNameToEdit) ? 'is-invalid' : ''}}" 
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top" 
                                    title="Site name">
                                @if (empty($siteNameToEdit))
                                    <div class="invalid-feedback">
                                        Site name is required!
                                    </div>
                                @endif
                            </div>
                        </div>
    
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.stop="resetProps()" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        @if (!empty($siteNameToEdit))    
                        <button 
                            type="button" 
                            wire:click="saveToUpdateWorkingSite()"
                            class="btn btn-primary">
                            Save changes
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('js-code')
Livewire.on('created', param => {
    Toastify({
        text: param.message,
        duration: 3000,
        close:true,
        gravity:"top",
        position: "center",
        backgroundColor: "#5cb85c",
    }).showToast();
});

Livewire.on('deleted', param => {
    Toastify({
        text: param.message,
        duration: 3000,
        close:true,
        gravity:"top",
        position: "center",
        backgroundColor: "#5cb85c",
    }).showToast();

});

{{-- Livewire.on('tooltipHydrate', () => {
    $('[data-bs-toggle="tooltip"]').tooltip();
}); --}}

@endpush