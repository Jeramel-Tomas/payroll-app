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
                            <div class="row mb-3 align-items-center">
                                <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                    <strong>Filter</strong>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="input-group">
                                        <select class="choices form-select" wire:model="workingSite">
                                            <option value="0">Filter by site...</option>
                                            @foreach ($sites as $site)
                                            <option value="{{$site->id}}">{{ $site->site_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <a href="#" wire:click.prevent="clearFilter()">Clear all</a>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col d-flex justify-content-start align-items-center">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Employee name..."
                                            aria-label="Recipient's username" aria-describedby="button-addon2"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="i.e. (Last name, First name, or both)"
                                            wire:model.debounce.3000="searchString">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="table-responsive" style="width: 80%; margin: auto;">
                                {{-- @dd($this->employees) --}}
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $index => $employee)
                                        <tr wire:key="emp-field-{{ $employee->id }}">
                                            <td class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    <p class="font-bold ms-3 mb-0">
                                                        {{ Str::ucfirst(Str::lower($employee->last_name)) }},
                                                        {{ Str::ucfirst(Str::lower($employee->first_name )) }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div>
                                                    {{$employee->total_amount ?? '0.00'}}
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div style="vertical-align: middle;">
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Create">
                                                        <a href="#" wire:click.prevent="createCashAdvance({{$employee->id}}, '{{$employee->first_name}}', '{{$employee->last_name}}')" 
                                                            data-bs-toggle="modal" data-bs-target="#addCashAdvanced"> 
                                                            <i class="bi bi-plus-square"></i> 
                                                        </a>
                                                    </span>
                                                    &nbsp; 
                                                    <a href="{{route('view.employee.cash.advances', ['id'=>$employee->id])}}" 
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                        <i class="bi bi-view-list"></i>
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
                                    <strong>Total: </strong> &nbsp; {{ $employees->total() }}
                                </div>
                                <div class="col d-flex justify-content-end">
                                    {{ $employees->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- wire:ignore.self --}}
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="addCashAdvanced" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Create cash advance to: &nbsp;
                        <small>{{$fullName}}</small>
                    </h5>
                    <button type="button" wire:click.stop="cancelCreate()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- {{$empIdForModal}} --}}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>
                                    Amount 
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" wire:model="cashAdvanceAmount" class="form-control" data-bs-toggle="tooltip" data-bs-placement="top" title="Required" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>
                                    Date 
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="date" wire:model="cashAdvancedDate" class="form-control" data-bs-toggle="tooltip" data-bs-placement="top" title="Required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Purpose</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <textarea wire:model="cashAdvancedPurpose" class="form-control">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.stop="cancelCreate()" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    @if ($cashAdvanceAmount && $cashAdvancedDate)    
                        <button type="button" wire:click="saveCashAdvances()"
                            class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@push('js-code')
{{-- Livewire.on('warning1', warning => {
Toastify({
text: "There is no data have been saved!",
duration: 3000,
close:true,
gravity:"top",
position: "center",
backgroundColor: "#ffc107",
}).showToast();
}); --}}
$('[data-bs-toggle="tooltip"]').tooltip();
{{-- $(document).ready(function() {

// ...

// Enable Bootstrap tooltips on page load
$('[data-bs-toggle="tooltip"]').tooltip();

// Ensure Livewire updates re-instantiate tooltips
if (typeof window.Livewire !== 'undefined') {
window.Livewire.hook('message.processed', (message, component) => {
$('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();
});
}

console.log('document ready gumagana')

}); --}}
@endpush