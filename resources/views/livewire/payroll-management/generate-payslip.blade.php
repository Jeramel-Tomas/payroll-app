<div>
    <div class="row">
        <div class="col col-12">
            <div class="card"> 
                <div class="card-header">
                    <h6 class="text-end">{{\Carbon\Carbon::now()->toFormattedDateString()}}</h6>
                </div>
                <div class="d-flex mb-2 row">
                    {{-- <div class="mr-3" style="max-width: 10rem;"> --}}
                        <div class="row mb-3 align-items-center">
                            <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                <strong>Filter</strong>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="input-group">
                                    <select class="choices form-select" wire:model="workingSite">
                                        <option value="0">Filter by site...</option>
                                        @foreach ($sites as $site)
                                        <option value="{{$site->id}}">{{ $site->site_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <label for="">Date</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <input type="month" class="form-control" wire:model="monthFilter" id="">
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <a href="#" wire:click.prevent="clearFilter()">Clear all</a>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3" >
                                @if($monthFilter)
                                    <a href="{{ route('download.payslip', ['monthFilter' => $monthFilter]) }}" class="btn btn-success">
                                            Generate Payslip
                                    </a>
                                @endif
                            </div>
                        </div>
                        {{--
                    </div> --}}


                    
                </div>
            <div class="table-responsive col p-3">
                {{-- Table start --}}
                <table class="table table-bordered  bg-white table-hover ">
                    <thead class="align-text-center bg-secondary text-white font-bold" >
                        <tr>
                            <th class="border text-center">Name </th>
                            <th class="border text-center">Site Location</th>
                            <th class="border text-center">Job Title</th>
                            <th class="border text-center">Rate</th>
                            <th class="border text-center">Days</th>
                            <th class="border text-center">Total Overtime</th>
                            <th class="border text-center"> Gross Total</th>
                            <th class="border text-center">Deductions</th>
                            <th class="border text-center"> Net Total</th>
                            <th class="border text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 2vh;" class="border">
                    @foreach($getEmployee as $employee)
                    <tr>
                    {{-- @dd($employee->job_title) --}}
                    <td class="d-none"></td>
                    <td class="auto" wire:model="emp_name">{{ Str::ucfirst(Str::lower($employee->first_name)) }} {{ Str::ucfirst(Str::lower($employee->last_name)) }}</td>
                    {{-- <td class="col-1 border">{{ $employee->job_title }}</td> --}}
                    <td class="auto" wire:model="emp_site">
                        @php
                            $siteName = DB::table('working_sites')
                                ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                ->where('employee_working_sites.employee_information_id', $employee->employee_id)
                                ->get();
                        @endphp
                        <ol class="">
                        @foreach ($siteName as $item)
                            <li class="">{{$item->site_name}}</li>
                        @endforeach
                        </ol>
                    </td>
                    <td class="auto">
                        @php
                            $jobTitle = DB::table('working_sites')
                                ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                ->where('employee_working_sites.employee_information_id', $employee->employee_id)
                                ->get();
                        @endphp
                        <ol class="">
                            @foreach ($jobTitle as $item)
                            <li class="">{{$item->job_title ?? 'Not set'}}</li>
                            @endforeach
                        </ol>
                    </td>                    
                    <td class="auto" wire:model="emp_rate">
                        @php
                            $rate = DB::table('working_sites')
                                ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                ->where('employee_working_sites.employee_information_id', $employee->employee_id)
                                ->get();
                        @endphp
                        <ol class="">
                            @foreach ($rate as $item)
                            <li class="">{{$item->job_title_rate ?? 0}}</li>
                            @endforeach
                        </ol>
                    </td>
                    <td class="auto" wire:model="emp_days">
                        @php
                        $attendance = DB::table('employee_time_records')
                            ->where('employee_id', $employee->id)
                            ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                $filterFrom ? $filterFrom : Carbon\Carbon::now()->startOfMonth(),
                                $filterTo ? $filterTo : Carbon\Carbon::now()->endOfMonth(),
                            ])
                            ->sum('days_present');
                            (double)$attendance = $attendance;
                        @endphp
                        {{$attendance}}
                       
                    </td>
                    <td class="col-1 border" wire:model="emp_total_ot">
                        @php
                        // get employee_working_sites
                        $empWorkingSites = DB::table('employee_working_sites')
                            ->where('employee_information_id', $employee->id)
                            ->get();
                        $computeOtPerJobRate = 0;
                        $computeDaysPerJobRate = 0;
                        foreach ($empWorkingSites as $key => $empWorkingSite) {
                            # code...
                            $getRatePerJob = DB::table('employee_time_records')
                                ->where('employee_id', $employee->id)
                                ->where('site_id', $empWorkingSite->working_site_id)
                                ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                    $filterFrom ? $filterFrom : Carbon\Carbon::now()->startOfMonth(),
                                    $filterTo ? $filterTo : Carbon\Carbon::now()->endOfMonth(),
                                ])
                                ->first();
                            $jobRate = !is_null($empWorkingSite->job_title_rate)
                                ? $empWorkingSite->job_title_rate
                                : 0;
                            $totalOtPerJob = $getRatePerJob->total_ot ?? 0;
                            $daysPresentTotal = $getRatePerJob->days_present ?? 0;

                            $computeOtPerJobRate += ($jobRate / 8) * $totalOtPerJob;
                            $computeDaysPerJobRate += $jobRate * $daysPresentTotal;
                        }
                        $otPerJob = DB::table('employee_time_records')
                            ->where('employee_id', $employee->id)
                            ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                $filterFrom ? $filterFrom : Carbon\Carbon::now()->startOfMonth(),
                                $filterTo ? $filterTo : Carbon\Carbon::now()->endOfMonth(),
                            ])
                        ->sum('total_ot');
                        
                        (double)$otPerJob = $otPerJob;
                        @endphp
                        {{$otPerJob}} 
                        {{-- | {{$computeOtPerJobRate}} --}}
                    </td>
                    {{-- gross --}}
                    <td class="auto" >
                        @php
                            // (double)$grossPay = $computeOt + $rateAttendanceTotal;
                            (double)$grossPay = $computeOtPerJobRate + $computeDaysPerJobRate;
                        @endphp
                        {{number_format($grossPay, 2)}}
                    </td>
                    {{-- Deductions --}}
                    <td class="auto">
                        @php
                            $totalCashAdvances = DB::table('employee_cash_advances')
                                ->where('employee_information_id', $employee->employee_id)
                                ->whereBetween(\DB::raw('DATE(cash_advanced_date)'), [
                                    $filterFrom ? $filterFrom : Carbon\Carbon::now()->startOfMonth(),
                                    $filterTo ? $filterTo : Carbon\Carbon::now()->endOfMonth(),
                                ])
                                ->sum('amount');
                        @endphp
                        {{number_format($totalCashAdvances, 2)}}
                    </td>
                    {{-- net pay --}}
                    <td class="auto">
                        @php
                            $netTotal = $grossPay - $totalCashAdvances;
                        @endphp
                        {{number_format($netTotal, 2)}}
                    </td>
                    {{-- for download --}}
                    <td class="col-1 border">
                        <div class="d-flex justify-content-center align-items-center " style="height: 100%">
                            {{-- {{route('dl.pdf', ['id' => $employeeId, 'ecaid' => $cashAdvance->id])}} --}}
                            @php
                                // get per employee attendace date from - to
                                $attendanceFromTo = DB::table('employee_time_records')
                                    ->where('employee_id', $employee->id)
                                    ->whereBetween(\DB::raw('DATE(attendance_from)'), [
                                        $filterFrom ? $filterFrom : Carbon\Carbon::now()->startOfMonth(),
                                        $filterTo ? $filterTo : Carbon\Carbon::now()->endOfMonth(),
                                    ])
                                    ->select('attendance_from', 'attendance_to')
                                    ->first();
                                $dateFrom = $attendanceFromTo->attendance_from ?? '';
                                $dateTo = $attendanceFromTo->attendance_to ?? '';
                                // get all employee job title, site, and rate
                                $jobInfos = DB::table('working_sites')
                                    ->join('employee_working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
                                    ->where('employee_working_sites.employee_information_id', $employee->employee_id)
                                    ->orderBy('working_sites.site_name')
                                    ->get();
                                $jobTitle = "";
                                $empSiteName = "";
                                $empRate = "";
                                foreach ($jobInfos as $key => $jobInfo) {
                                    if ($jobInfo->job_title && $jobInfo->job_title_rate) {
                                        # code...
                                        $jobTitle .= $jobInfo->job_title . '-';
                                        $empSiteName .= $jobInfo->site_name . '-';
                                        $empRate .= $jobInfo->job_title_rate . '-';
                                    }
                                }
                                $jobTitle = rtrim(str_replace('-', '|', $jobTitle), '|');
                                $empSiteName = rtrim(str_replace('-', '|', $empSiteName), '|');
                                $empRate = rtrim(str_replace('-', '|', $empRate), '|');
                                
                            @endphp
                            {{-- {{$jobTitle}} --}}
                            {{-- {{$empSiteName}} --}}
                            {{-- {{$empRate}} --}}
                            <a href="{{route('single.download.payslip', [
                                'id' => $employee->employee_id, 
                                'dateFrom' => $dateFrom,
                                'dateTo' => $dateTo,
                                'emp_name' => $employee->first_name . ' ' . $employee->last_name,
                                'emp_job_title' => $jobTitle,
                                'emp_site' => $empSiteName,
                                'emp_days' => number_format($attendance, 2),
                                'emp_total_ot' => number_format($otPerJob, 2),
                                'emp_rate' => $empRate,
                                'emp_gross_total' => number_format($grossPay, 2),
                                'emp_deductions' => number_format($totalCashAdvances, 2),
                                'emp_final_pay' => number_format($netTotal, 2),
                                'monthFilter' => $monthFilter,
                                ]) }}" 
                                class="{{ $monthFilter ? '':'btn disabled'}}"
                            >
                                <span class="bi bi-download point" style="font-size: 1rem; margin-right: 0.5rem;"
                                    data-toggle="tooltip" title="Download Payslip">
                                </span>
                            </a>
                        </div>
                    </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
                <div class="row">
                    <div class="col d-flex justify-content-start align-items-center">
                        <strong>Total: </strong> <small>{{ $getEmployee->total() }}</small>
                    </div>
                    <div class="col d-flex justify-content-end">
                        {{ $getEmployee->links() }}
                    </div>
                </div>
        </div>
        </div>
    </div>
</div>