<div>
<div wire:ignore.self class="modal fade" data-bs-backdrop='static' data-bs-keyboard="false" tabindex="-2" id="inputDtr" role="dialog">
{{-- <div wire:ignore.self class="modal show" id="onload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> --}}
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Input Time Record of &nbsp;
                    @isset($employeesInfo)
                        @foreach ($employeesInfo as $key=>$item)
                            @if ($key === 0)
                                {{Str::ucfirst(Str::lower($item->first_name))}} &nbsp;
                                {{Str::ucfirst(Str::lower($item->last_name))}}
                                @break
                            @endif
                        @endforeach
                    @endisset
                </h5>
                <a href="{{route('attendance.log.manage')}}" type="button" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body">
                <div class="row my-2">
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <label for="">Filter</label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="input-group">
                            <input type="month" wire:model="monthFilterInputAttendance" class="form-control">
                            {{-- {{Carbon\Carbon::create($monthFilter)->format('m')}} --}}
                            {{-- {{Carbon\Carbon::create($monthFilter)->startOfMonth()->format('Y-m-d')}} --}}
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <a href="#" wire:click.prevent="clearFilterInputAttendance()">Clear Filter</a>
                    </div>
                    <div class="col text-end">
                        <span class="">{{\Carbon\Carbon::now()->toFormattedDateString()}}</span>
                    </div>
                </div>
                @if (session()->has('message'))
                <div class="row my-2">
                    <div class="col-lg-4 col-md-4 col-sm-4"></div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ session('message') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4"></div>
                </div>
                @endif
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Site name</th>
                            <th>Job</th>
                            <th>Daily Rate</th>
                            <th>Days Present</th>
                            <th>Total OT</th>
                            <th>From</th>
                            <th>To</th>
                            <th>
                                <div>Gross</div>
                                <span>OT</span> |
                                <span>Days</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    @isset($employeesInfo)
                        @foreach ($employeesInfo as $key=>$employee)
                        @php
                            $empDaysPresent = DB::table('employee_time_records')->select('days_present')
                                ->where([
                                    ['employee_id', $employeeId],
                                    ['site_id', $employee->working_site_id]
                                    ])
                                    ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                    $filterFromInputAttendance ? $filterFromInputAttendance : Carbon\Carbon::now()->startOfMonth(),
                                    $filterToInputAttendance ? $filterToInputAttendance : Carbon\Carbon::now()->endOfMonth(),
                                ])
                                ->first()->days_present ?? null;

                            $ot = DB::table('employee_time_records')->select('total_ot', 'attendance_from')
                                ->where('employee_id', $employeeId)
                                ->where('site_id', $employee->working_site_id)
                                ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                    $filterFromInputAttendance ? $filterFromInputAttendance : Carbon\Carbon::now()->startOfMonth(),
                                    $filterToInputAttendance ? $filterToInputAttendance : Carbon\Carbon::now()->endOfMonth(),
                                ])
                                ->first()->total_ot ?? null;

                            $dateFromModal = DB::table('employee_time_records')->select('attendance_from')
                                ->where('employee_id', $employeeId)
                                ->where('site_id', $employee->working_site_id)
                                ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                    $filterFromInputAttendance ? $filterFromInputAttendance : Carbon\Carbon::now()->startOfMonth(),
                                    $filterToInputAttendance ? $filterToInputAttendance : Carbon\Carbon::now()->endOfMonth(),
                                ])
                                ->first()->attendance_from ?? null;
                            $dateToModal = DB::table('employee_time_records')->select('attendance_to')
                                ->where('employee_id', $employeeId)
                                ->where('site_id', $employee->working_site_id)
                                ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                    $filterFromInputAttendance ? $filterFromInputAttendance : Carbon\Carbon::now()->startOfMonth(),
                                    $filterToInputAttendance ? $filterToInputAttendance : Carbon\Carbon::now()->endOfMonth(),
                                ])
                                ->first()->attendance_to ?? null;
                        @endphp


                        <tr 
                            wire:key="emp-field-{{ $item->id }}" 
                            role="button" 
                            wire:click.stop="setupInputAttendance(
                                '{{$employee->working_site_id}}',
                                '{{$empDaysPresent}}',
                                '{{$ot}}',
                                '{{$dateFromModal}}',
                                '{{$dateToModal}}'
                            )"
                        >
                            
                            <td class="col-auto">
                                {{$employee->site_name}}
                            </td>
                            <td class="col-auto">
                                {{$employee->job_title}}
                            </td>
                            <td class="col-auto">
                                {{$employee->job_title_rate}}
                            </td>
                            <td class="col-auto">
                                <div>
                                    
                                    @if ($siteId && $employee->working_site_id == $siteId)
                                    <input 
                                        type="number" 
                                        wire:model.lazy="empDaysPresentInput"
                                        value="{{$empDaysPresentInput ?? ''}}" 
                                        class="form-control @error('empDaysPresentInput') is-invalid @enderror"
                                        />
                                        @error('empDaysPresentInput')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    @else
                                        {{$empDaysPresent ?? '-'}}
                                    @endif

                                </div>
                            </td>
                            <td class="col-auto">
                                <div >
                                    @if ($siteId && $employee->working_site_id == $siteId)
                                    <input 
                                        type="number" 
                                        wire:model="otInput"
                                        value="{{$otInput ?? ''}}"
                                        class="form-control" />
                                    @else
                                        {{$ot ?? '-'}}
                                    @endif
                                </div>
                            </td>
                            <td class="col-auto">
                                <div>
                                    @if ($siteId && $employee->working_site_id == $siteId)
                                    <input 
                                        type="date" 
                                        wire:model.lazy="dateFromModalInput"
                                        value="{{$dateFromModalInput ?? ''}}" 
                                        class="form-control @error('dateFromModalInput') is-invalid @enderror" />
                                        @error('dateFromModalInput')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    @else
                                    {{$dateFromModal ?? '-'}}
                                    @endif

                                </div>
                            </td>
                            <td class="col-auto">
                                <div>
                                    @if ($siteId && $employee->working_site_id == $siteId)
                                        <input 
                                            type="date" 
                                            wire:model="dateToModalInput"
                                            value="{{$dateToModalInput ?? ''}}"
                                            class="form-control @error('dateToModalInput') is-invalid @enderror" />
                                            @error('dateToModalInput')
                                            <small class="text-danger">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                    @else
                                        {{$dateToModal ?? '-'}}
                                    @endif
                                </div>
                            </td>
                            <td class="col-auto">
                                @php
                                    (double)$rate = (double)$employee->job_title_rate ?? 0;
                                    (double)$numDays = (double)$empDaysPresent ?? 0;
                                    (double)$totalOt = (double)$ot ?? 0;
                                    (double)$numOt = ((double)$rate / 8) * $totalOt ?? 0;
                                @endphp
                                {{$numOt}} |
                                {{
                                    (double)(($numDays * $rate) + $numOt)
                                }}
                            </td>
                            @if ($employee->working_site_id == $siteId)
                            <td>
                                <button
                                    class="btn btn-info" 
                                    wire:click.stop="saveInputAttendance({{$employee->working_site_id}})">
                                    Save
                                </button>
                                <button
                                    class="btn btn-warning" 
                                    wire:click.stop="cancelInputAttendance()">
                                    Cancel
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <a href="{{route('attendance.log.manage')}}"
                    {{-- type="button"  --}}
                    {{-- id="closeExportModal"  --}}
                    class="btn btn-primary"
                >
                    Done
                </a>
            </div>
        </div>
    </div>
</div>
@push('js-code')
{{-- window.onload = () => {
$('#onload').modal('show');
} --}}
@endpush
</div>
