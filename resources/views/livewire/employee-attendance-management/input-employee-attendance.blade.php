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
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @isset($employeesInfo)
                        @foreach ($employeesInfo as $key=>$employee)
                        <tr wire:key="emp-field-{{ $item->id }}">
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
                                @php
                                    $daysPresent = DB::table('employee_time_records')->select('days_present')
                                        ->where([
                                            ['employee_id', $employeeId],
                                            ['site_id', $employee->working_site_id]
                                        ])
                                        ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                            $filterFromInputAttendance ? $filterFromInputAttendance : Carbon\Carbon::now()->startOfMonth(),
                                            $filterToInputAttendance ? $filterToInputAttendance : Carbon\Carbon::now()->endOfMonth(),
                                        ])
                                        ->first()->days_present ?? null;
                                @endphp
                                <div 
                                    wire:click.stop="setInputDaysPresent('{{$employee->working_site_id}}', 'daysPresent')"
                                    role="button" class="d-flex align-items-center">
                                    @if (
                                        !empty($daysPresentColumn) && 
                                        $daysPresentColumn === $daysPresentColumnConstant &&
                                        $employee->working_site_id == $siteId
                                        )
                                    <input 
                                        type="text" 
                                        wire:keydown.escape="cancelInput()" 
                                        wire:keydown.enter="saveInputDaysPresent($event.target.value)"
                                        value="{{$daysPresent ?? ''}}" 
                                        class="form-control" />
                                    @else
                                        {{$daysPresent ?? '-'}}
                                    @endif
                                </div>
                            </td>
                            <td class="col-auto">
                                @php
                                    $ot = DB::table('employee_time_records')->select('total_ot', 'attendance_from')
                                        ->where('employee_id', $employeeId)
                                        ->where('site_id', $employee->working_site_id)
                                        ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                            $filterFromInputAttendance ? $filterFromInputAttendance : Carbon\Carbon::now()->startOfMonth(),
                                            $filterToInputAttendance ? $filterToInputAttendance : Carbon\Carbon::now()->endOfMonth(),
                                        ])
                                        ->first()->total_ot ?? null;
                                @endphp
                                <div wire:click.stop="setInputOtTotal({{$employee->working_site_id}}, 'totalOt')" role="button"
                                    class="d-flex align-items-center">
                                    @if (
                                    !empty($totalOtColumn) &&
                                    $totalOtColumn === $totalOtColumnConstant &&
                                    $employee->working_site_id === $siteId
                                    )
                                    <input 
                                        type="text" 
                                        wire:keydown.escape="cancelInput()"
                                        wire:keydown.enter="saveInputOtTotal($event.target.value)" 
                                        value="{{$ot ?? ''}}"
                                        class="form-control" />
                                    @else
                                        {{$ot ?? '-'}}
                                    @endif
                                </div>
                            </td>
                            <td class="col-auto">
                                @php
                                    $dateFromModal = DB::table('employee_time_records')->select('attendance_from')
                                        ->where('employee_id', $employeeId)
                                        ->where('site_id', $employee->working_site_id)
                                        ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                            $filterFromInputAttendance ? $filterFromInputAttendance : Carbon\Carbon::now()->startOfMonth(),
                                            $filterToInputAttendance ? $filterToInputAttendance : Carbon\Carbon::now()->endOfMonth(),
                                        ])
                                        ->first()->attendance_from ?? null;
                                @endphp
                                <div 
                                    wire:click.stop="setInputAttendanceFrom({{$employee->working_site_id}}, 'attendanceFrom')" 
                                    role="button"
                                    class="d-flex align-items-center">
                                    @if (
                                    !empty($attendanceFromColumn) &&
                                    $attendanceFromColumn === $attendanceFromColumnConstant &&
                                    $employee->working_site_id === $siteId
                                    )
                                    <input 
                                        type="date" 
                                        wire:keydown.escape="cancelInput()" 
                                        wire:keydown.enter="saveInputAttendanceFrom($event.target.value)"
                                        value="{{$dateFromModal ?? ''}}" 
                                        class="form-control" />
                                    @else
                                    {{$dateFromModal ?? '-'}}
                                    @endif
                                </div>
                            </td>
                            <td class="col-auto">
                                @php
                                    $dateToModal = DB::table('employee_time_records')->select('attendance_to')
                                        ->where('employee_id', $employeeId)
                                        ->where('site_id', $employee->working_site_id)
                                        ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                            $filterFromInputAttendance ? $filterFromInputAttendance : Carbon\Carbon::now()->startOfMonth(),
                                            $filterToInputAttendance ? $filterToInputAttendance : Carbon\Carbon::now()->endOfMonth(),
                                        ])
                                        ->first()->attendance_to ?? null;
                                @endphp
                                <div 
                                    wire:click.stop="setInputAttendanceTo({{$employee->working_site_id}}, 'attendanceTo')" role="button"
                                    class="d-flex align-items-center">
                                    @if (
                                        !empty($attendanceToColumn) &&
                                        $attendanceToColumn === $attendanceToColumnConstant &&
                                        $employee->working_site_id === $siteId
                                    )
                                        <input 
                                            type="date" 
                                            wire:keydown.escape="cancelInput()"
                                            wire:keydown.enter="saveInputAttendanceTo($event.target.value)" 
                                            value="{{$dateToModal ?? ''}}"
                                            class="form-control" />
                                    @else
                                        {{$dateToModal ?? '-'}}
                                    @endif
                                </div>
                            </td>
                            <td class="col-auto">
                                @php
                                    (double)$rate = (double)$employee->job_title_rate ?? 0;
                                    (double)$numDays = (double)$daysPresent ?? 0;
                                    (double)$totalOt = (double)$ot ?? 0;
                                    (double)$numOt = ((double)$rate / 8) * $totalOt ?? 0;
                                @endphp
                                {{$numOt}} |
                                {{
                                    (double)(($numDays * $rate) + $numOt)
                                }}
                            </td>
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
