<div>
<section class="row">
    <div class="col-12 col-lg-12">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="">
                            <a href="{{route('working.sites.index')}}" class="btn btn-primary">
                                <i class="bi bi-arrow-return-left"></i> &nbsp; Back
                            </a>
                            <span class="float-end">{{\Carbon\Carbon::now()->toFormattedDateString()}}</span>
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive" style="width: 80%; margin: auto;">
                            <table class="table table-hover table-bordered align-items-center">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Job title</th>
                                        <th>Daily Rate</th>
                                        <th data-bs-toggle="tooltip"
                                            title="Unassign employee">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siteEmployees as $employee)
                                    <tr>
                                        <td class="col-auto">
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $fullName = strtolower($employee->firstname . ' ' . $employee->lastname);
                                                    $fullName = ucwords($fullName);
                                                @endphp
                                                {{$fullName}}
                                                {{-- {{ $employee->firstname . ' '}}
                                                {{ $employee->lastname }} --}}
                                            </div>
                                        </td>
                                        <td class="col-auto">
                                            <div
                                                wire:click.stop="editJobTitleBySite({{$employee->employee_information_id}}, '{{$employee->working_site_id}}', 'jobTitle')"
                                                role="button"
                                                class="d-flex align-items-center"
                                            >
                                                @if (!empty($jobTitleColumn) && $jobTitleColumn === $jobTitleColumnConstant && $employee->employee_information_id === $employeeId)
                                                    <input 
                                                        type="text" 
                                                        wire:keydown.escape="cancelEditing()" 
                                                        wire:keydown.enter="saveJobTitle($event.target.value)"
                                                        value="{{$employee->job_title ?? ''}}"
                                                        class="form-control" />
                                                
                                                @else
                                                    {{$employee->job_title ?? '-'}}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="col-auto">
                                            <div
                                                wire:click.stop="editJobTitleRateBySite({{$employee->employee_information_id}}, '{{$employee->working_site_id}}', 'jobTitleRate')"
                                                role="button"
                                            >
                                                @if (
                                                    !empty($jobTitleRateColumn) && 
                                                    $jobTitleRateColumn === $jobTitleRateColumnConstant && 
                                                    $employee->employee_information_id === $employeeId
                                                )
                                                <input type="text" wire:keydown.escape="cancelEditing()" wire:keydown.enter="saveJobTitleRate($event.target.value)"
                                                    value="{{$employee->job_title_rate ?? ''}}" class="form-control" />
                                                
                                                @else
                                                {{$employee->job_title_rate ?? '-'}}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="col-auto">
                                            <div class="d-flex align-items-center">
                                                {{-- unassigned emp and assign --}}
                                                <a href="#"
                                                    wire:click.prevent="confirmDeletion({{$employee->employee_information_id}}, '{{$employee->working_site_id}}', '{{$fullName}}')"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDelete">
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
                                <strong>Total: </strong> &nbsp; {{ $siteEmployees->total() }}
                            </div>
                            <div class="col d-flex justify-content-end">
                                {{ $siteEmployees->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    

<!-- Modal Confirm Delete Employee Site -->
<div wire:ignore.self class="modal fade" id="confirmDelete" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header alert alert-warning">
                <h5 class="modal-title" id="exampleModalLabel">
                    Confirm action
                </h5>
                <button type="button" wire:click.stop="cancelDeletion()" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body ">
                <div class="">
                    <p class="text-center">
                        You are about to unassigned {{$empFullName}} in this site.
                    </p>
                    <p class="text-center">
                        <strong>Are you sure you want to continue this action?</strong>
                    </p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" wire:click.stop="cancelDeletion()" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cancel</button>
                
                <button type="button" wire:click="deleteEmployeeFromSite()" class="btn btn-danger"
                    data-bs-dismiss="modal">Confirm</button>
               
            </div>
        </div>
    </div>
</div>


@push('js-code')
       {{--  Livewire.on('created', param => {
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
    
        }); --}}
    
        {{-- Livewire.on('tooltipHydrate', () => {
        $('[data-bs-toggle="tooltip"]').tooltip();
        }); --}}
    
        @endpush
</div>
