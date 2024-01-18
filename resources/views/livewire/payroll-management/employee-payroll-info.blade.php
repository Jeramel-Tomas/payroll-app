<div>
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="text-end">{{\Carbon\Carbon::now()->toFormattedDateString()}}</h6>
                           {{--  @php
                            $date = $filterDate
                            ? \Carbon\Carbon::create($filterDate)->toFormattedDateString()
                            : $today;
                            @endphp
                            <h6 class="text-end">{{ $date }}</h6>
                            <h4 class="text-center border-bottom text-muted">{{ $workingSiteName }}</h4> --}}
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
                                {{-- <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <input type="date" wire:model="filterDate" class="form-control" min="2018-01-01">
                                    </div>
                                </div> --}}
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
                                {{-- <div class="col d-flex justify-content-end">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#myModal">
                                        Export Format
                                    </button>
                                </div> --}}
                                {{-- <div class="col d-flex justify-content-end">
                                    <button class="btn btn-info">Upload Time Record</button>
                                </div> --}}
                            </div>
    
                            <div class="table-responsive">
                                {{-- @dd($this->employees) --}}
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Job title</th>
                                            <th>Daily rate</th>
                                            <th>AM <small>Schedule</small></th>
                                            <th>PM <small>Schedule</small></th>
                                            {{-- <th>Employment status</th> --}}
                                            <th>Payday</th>
                                            <th>Day-off</th>
                                            {{-- <th>Action</th> --}}
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
                                                @if ($columnToEdit !== $jobTitle)
                                                <div role="button" class="" wire:click.stop="cellToEdit({{ $employee->id }}, '{{ $employee->job_title }}', 'jobTitle')" >
                                                @else
                                                <div>
                                                @endif
                                            
                                                    @if (!empty($employeeIdSetToShowEditInput) && $employeeIdSetToShowEditInput === $employee->id && $columnToEdit === $jobTitle)
                                                        <input type="text" wire:model="jobTitleValue" class="form-control" />
                                                        <div class="pt-2 px-2">
                                                            <i class="text-primary bi bi-save" wire:click.stop="saveCellToEdit({{ $employee->id }}, '{{ $jobTitleValue }}', 'jobTitle')"></i>
                                                            <i role="button" class="text-danger bi bi-x-square" wire:click.stop="cancelEdit()"></i>
                                                        </div>
                                                    @else
                                                        {{ $employee->job_title ?? '-' }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                @if ($columnToEdit !== $dailyRate)
                                                <div role="button" class="" wire:click.stop="cellToEdit({{ $employee->id }}, '{{ $employee->daily_rate }}', 'dailyRate')">
                                                @else
                                                <div>
                                                @endif

                                                    @if (!empty($employeeIdSetToShowEditInput) && $employeeIdSetToShowEditInput === $employee->id && $columnToEdit === $dailyRate)
                                                        <input type="text" wire:model="dailyRateValue" class="form-control" />
                                                        <div class="pt-2 px-2">
                                                            <i class="text-primary bi bi-save" wire:click.stop="saveCellToEdit({{ $employee->id }}, '{{ $dailyRateValue }}', 'dailyRate')"></i>
                                                            <i role="button" class="text-danger bi bi-x-square" wire:click.stop="cancelEdit()"></i>
                                                        </div>
                                                    @else
                                                        {{ $employee->daily_rate ?? '-' }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">                                                
                                                @if ($columnToEdit !== $dailyTimeScheduleAM)
                                                <div role="button" class="" wire:click.stop="cellToEdit({{ $employee->id }}, '{{ $employee->daily_time_schedule_am }}', 'dailyTimeScheduleAm')">
                                                @else
                                                <div>
                                                @endif
                                                    @if (!empty($employeeIdSetToShowEditInput) && $employeeIdSetToShowEditInput === $employee->id && $columnToEdit === $dailyTimeScheduleAM)
                                                        <input type="text" wire:model="dailyTimeScheduleAMValue" class="form-control" />
                                                        <div class="pt-2 px-2">
                                                            <i class="text-primary bi bi-save" wire:click.stop="saveCellToEdit({{ $employee->id }}, '{{ $dailyTimeScheduleAMValue }}', 'dailyTimeScheduleAm')"></i>
                                                            <i role="button" class="text-danger bi bi-x-square" wire:click.stop="cancelEdit()"></i>
                                                        </div>
                                                    @else
                                                        {{ $employee->daily_time_schedule_am ?? '-'}}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                @if ($columnToEdit !== $dailyTimeSchedulePM)
                                                <div role="button" class="" wire:click.stop="cellToEdit({{ $employee->id }}, '{{ $employee->daily_time_schedule_pm }}', 'dailyTimeSchedulePm')">
                                                @else
                                                <div>
                                                @endif
                                                    @if (!empty($employeeIdSetToShowEditInput) && $employeeIdSetToShowEditInput === $employee->id && $columnToEdit === $dailyTimeSchedulePM)
                                                        <input type="text" wire:model="dailyTimeSchedulePMValue" class="form-control" />
                                                        <div class="pt-2 px-2">
                                                            <i class="text-primary bi bi-save" wire:click.stop="saveCellToEdit({{ $employee->id }}, '{{ $dailyTimeSchedulePMValue }}', 'dailyTimeSchedulePm')"></i>
                                                            <i role="button" class="text-danger bi bi-x-square" wire:click.stop="cancelEdit()"></i>
                                                        </div>
                                                    @else
                                                        {{ $employee->daily_time_schedule_pm ?? '-'}}
                                                    @endif
                                                </div>
                                            </td>
                                            {{-- <td class="col-auto">
                                                @if ($columnToEdit !== $employmentStatus)
                                                <div role="button" class="" wire:click.stop="cellToEdit({{ $employee->id }}, '{{ $employee->employment_status }}', 'employmentStatus')">
                                                @else
                                                <div>
                                                @endif
                                                    @if (!empty($employeeIdSetToShowEditInput) && $employeeIdSetToShowEditInput === $employee->id && $columnToEdit === $employmentStatus)
                                                        <input type="text" wire:model="employmentSatusValue" class="form-control" />
                                                        <div class="pt-2 px-2">
                                                            <i class="text-primary bi bi-save" wire:click.stop="saveCellToEdit({{ $employee->id }}, '{{ $employmentSatusValue }}', 'employmentStatus')"></i>
                                                            <i role="button" class="text-danger bi bi-x-square" wire:click.stop="cancelEdit()"></i>
                                                        </div>
                                                    @else
                                                        {{ $employee->employment_status ?? '-'}}
                                                    @endif
                                                </div>
                                            </td> --}}
                                            <td class="col-auto">
                                               @if ($columnToEdit !== $payDay)
                                                <div role="button" class="" wire:click.stop="cellToEdit({{ $employee->id }}, '{{ $employee->payday }}', 'payday')">
                                                @else
                                                <div>
                                                @endif
                                                    @if (!empty($employeeIdSetToShowEditInput) && $employeeIdSetToShowEditInput === $employee->id && $columnToEdit === $payDay)
                                                    {{-- <input type="text" wire:model="payDayValue" class="form-control" /> --}}
                                                    {{-- // M = monthly, D = daily, S = Semi monthly (15 and end of the month) --}}
                                                    {{-- {{$payDayValue}} --}}
                                                    <select wire:model="payDayValue" {{-- wire:change="change" --}}>
                                                        <option value="">--</option>
                                                        <option value="D">Daily</option>
                                                        <option value="S">Fifteen last</option>
                                                        <option value="M">Monhtly</option>
                                                    </select>
                                                    <div class="pt-2 px-2">
                                                        <i class="text-primary bi bi-save" wire:click.stop="saveCellToEdit({{ $employee->id }}, '{{ $payDayValue }}', 'payday')"></i>
                                                        <i role="button" class="text-danger bi bi-x-square" wire:click.stop="cancelEdit()"></i>
                                                    </div>
                                                    @else
                                                        @if ($employee->payday === 'D')
                                                            Daily
                                                        @elseif($employee->payday === 'S')
                                                            Fifteen last
                                                        @elseif($employee->payday === 'M')
                                                            Monthly
                                                        @else
                                                            -
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <div role="button" wire:click.stop="setDataToModal({{$employee->id}}, '{{$employee->first_name}}', '{{$employee->last_name}}')" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    {{ $employee->day_off ?? '-'}}
                                                </div>
                                            </td>
                                            {{-- <td class="col-auto">
                                                <a href="#" wire:click.stop="setDataToModal({{$employee->id}})" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <span class="bi bi-eye-fill" data-bs-toggle="tooltip" data-bs-placement="top" title="View"></span>
                                                </a>
                                            </td> --}}
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

    {{-- wire:ignore.self --}}
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{$fullName}}</h5>
                    <button type="button" wire:click.stop="cancelEdit()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- {{$empIdForModal}} --}}
                    {{-- Set day off --}}
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" wire:model.defer="selectedDayoff" value="M" class="form-check-input" id="checkbox2">
                            <label for="checkbox2">Mondays</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" wire:model.defer="selectedDayoff" value="T" class="form-check-input" id="checkbox2">
                            <label for="checkbox2">Tuesdays</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" wire:model.defer="selectedDayoff" value="W" class="form-check-input" id="checkbox2">
                            <label for="checkbox2">Wednesdays</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" wire:model.defer="selectedDayoff" value="Th" class="form-check-input" id="checkbox2">
                            <label for="checkbox2">Thursdays</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" wire:model.defer="selectedDayoff" value="F" class="form-check-input" id="checkbox2">
                            <label for="checkbox2">Fridays</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" wire:model.defer="selectedDayoff" value="Sat" class="form-check-input" id="checkbox2">
                            <label for="checkbox2">Saturdays</label>
                        </div>
                    </div>
                    <div class="form-check">
                        <div class="checkbox">
                            <input type="checkbox" wire:model.defer="selectedDayoff" value="Sun" class="form-check-input" id="checkbox2">
                            <label for="checkbox2">Sundays</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.stop="cancelEdit()" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click.prevent="saveDayOff()" data-bs-dismiss="modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js-code')
Livewire.on('warning1', warning => {
    Toastify({
        text: "There is no data have been saved!",
        duration: 3000,
        close:true,
        gravity:"top",
        position: "center",
        backgroundColor: "#ffc107",
    }).showToast();
});
@endpush



