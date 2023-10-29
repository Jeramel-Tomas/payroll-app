<div>
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            @php
                                $date = $filterDate
                                    ? \Carbon\Carbon::create($filterDate)->toFormattedDateString()
                                    : $today;
                            @endphp
                            {{-- @if ($filterDate)
                                <h6 class="text-end">{{ \Carbon\Carbon::create($filterDate)->toFormattedDateString() }}</h6>
                            @else
                                <h6 class="text-end">{{ $today }}</h6>
                            @endif --}}
                            <h6 class="text-end">{{ $date }}</h6>
                            <h4 class="text-center border-bottom text-muted">{{ $workingSiteName }}</h4>
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
                                    {{-- <div>@json($workingSite)</div> --}}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <input type="date" wire:model="filterDate" class="form-control" min="2018-01-01" >
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <a href="#" wire:click.prevent = "clearFilter()">Clear all</a>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col d-flex justify-content-start align-items-center">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Employee name..."
                                            aria-label="Recipient's username" aria-describedby="button-addon2"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="i.e. (Last name, First name, or both)"
                                            wire:model.debounce.3000 = "searchString"
                                            >
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col d-flex justify-content-end">
                                    <button class="btn btn-info">Upload Time Record</button>
                                </div>
                            </div>
    
                            <div class="table-responsive">
                                {{-- @dd($this->employees) --}}
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>In <small>(AM)</small></th>
                                            <th>Out <small>(AM)</small></th>
                                            <th>In <small>(PM)</small></th>
                                            <th>Out <small>(PM)</small></th>
                                            <th>In <small>(OT)</small></th>
                                            <th>Out <small>(OT)</small></th>
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
                                                    @if ($editingEmployeeId === $employee->id)
                                                        <input 
                                                        type="text" 
                                                        wire:model="morningIn"
                                                        id="morningIn"
                                                            class="form-control"
                                                            maxlength="16" />
                                                        @error('morningIn') <span class="text-danger in-out-error">{{ $message }}</span> @enderror
                                                    @else
                                                        <p class="mb-0 am-in">{{ $employee->morningIn ? \Carbon\Carbon::parse($employee->morningIn)->format('h:i') : '-'}} </p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div>
                                                    @if ($editingEmployeeId === $employee->id)
                                                        <input 
                                                            type="text" 
                                                            wire:model.defer='morningOut' 
                                                            class="form-control" 
                                                            maxlength="5">
                                                        @error('morningOut') <span class="text-danger in-out-error">{{ $message }}</span> @enderror
                                                    @else
                                                        <p class=" mb-0">{{$employee->morningOut ? \Carbon\Carbon::parse($employee->morningOut)->format('h:i') : '-'}}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div>
                                                    @if ($editingEmployeeId === $employee->id)
                                                        <input 
                                                            type="text" 
                                                            wire:model.defer='afternoonIn' 
                                                            class="form-control" 
                                                            maxlength="5">
                                                        @error('afternoonIn') <span class="text-danger in-out-error">{{ $message }}</span> @enderror
                                                    @else
                                                        <p class=" mb-0">{{$employee->afternoonIn ? \Carbon\Carbon::parse($mployee->afternoonIn)->format('h:i') : '-'}}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div>
                                                    @if ($editingEmployeeId === $employee->id)
                                                        <input 
                                                            type="text" 
                                                            wire:model.defer='afternoonOut' 
                                                            class="form-control" 
                                                            maxlength="5">
                                                        @error('afternoonOut') <span class="text-danger in-out-error">{{ $message }}</span> @enderror
                                                    @else
                                                        <p class=" mb-0">{{$employee->afternoonOut ? \Carbon\Carbon::parse($mployee->afternoonOut)->format('h:i') : '-'}}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div>
                                                    @if ($editingEmployeeId === $employee->id)
                                                        <input 
                                                            type="text" 
                                                            wire:model.defer="overtimeIn" 
                                                            class="form-control" 
                                                            maxlength="5">
                                                        @error('overtimeIn') <span class="text-danger in-out-error">{{ $message }}</span> @enderror
                                                    @else
                                                        <p class=" mb-0">{{$employee->overtimeIn ? \Carbon\Carbon::parse($employee->overtimeIn)->format('h:i') : '-'}}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div>
                                                    @if ($editingEmployeeId === $employee->id)
                                                        <input 
                                                            type="text" 
                                                            wire:model.defer="overtimeOut" 
                                                            class="form-control" 
                                                            maxlength="5">
                                                        @error('overtimeOut') <span class="text-danger in-out-error">{{ $message }}</span> @enderror
                                                    @else
                                                        <p class=" mb-0">{{$employee->overtimeOut ? \Carbon\Carbon::parse($employee->overtimeOut)->format('h:i') : '-'}}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                @if ($editingEmployeeId !== $employee->id)
                                                    <a href="#" class="edit-button" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" 
                                                        wire:click.prevent="inlineEditEmployeeLog(
                                                            {{ $employee->id }}, 
                                                            '{{ $employee->morningIn }}',
                                                            '{{ $employee->morningOut }}',
                                                            '{{ $employee->afternoonIn }}',
                                                            '{{ $employee->afternoonOut }}',
                                                            '{{ $employee->overtimIn }}',
                                                            '{{ $employee->overtimeOut }}'
                                                            )"> 
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                @else
                                                    <a href="#" class="text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Save" wire:click.prevent="saveTimeLogs()">
                                                        <i class="bi bi-save2-fill"></i>
                                                    </a> &nbsp;
                                                    <a href="#" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel" wire:click.prevent="editCancelEmployeeLog({{$employee->id}})">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col d-flex justify-content-start align-items-center">
                                    <strong>Total: </strong> <small>{{ $employees->total() }}</small>
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
</div>

{{-- <script>
    $this->dispatchBrowserEvent('testing',['success' => 'Your message has been sent successfully!']);
    
    Livewire.on('test', message => {
        console.log(message.detail.success);
        console.log(message.success);
        console.log('message.detail.success');
    });
</script> --}}



@push('js-code')
{{-- var ed = document.getElementsByClassName('.edit-button');

ed.addEventListener('click', function() {
    console.log('edit-button clicked');
    console.log(document.getElementsByClassName('.am-in').html());
}); --}}


$('.edit-button').click( function(e) {
    console.log('edit-button clicked');
    console.log($('.am-in').html());
});

Livewire.on('success', success => {
    Toastify({
        text: "Successfully updated daily time record!",
        duration: 3000,
        close:true,
        gravity:"top",
        position: "center",
        backgroundColor: "#5cb85c",
    }).showToast();
});

Livewire.on('warning', warning => {
    Toastify({
        text: "There is nothing to save!",
        duration: 3000,
        close:true,
        gravity:"top",
        position: "center",
        backgroundColor: "#ffc107",
    }).showToast();
});

{{-- document.getElementById('top-center').addEventListener('click', () => {
Toastify({
text: "This is toast in top center",
duration: 3000,
close:true,
gravity:"top",
position: "center",
backgroundColor: "#5cb85c",
}).showToast();
}) --}}

@endpush
