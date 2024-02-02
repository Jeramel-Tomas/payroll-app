<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ public_path('new-assets/assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{ public_path('new-assets/assets/css/pdfDownload.css')}}">
</head>
<body style="margin: auto;">
    <div class="container-fluid">
        <div class="row" style="margin: auto;">
            <div class="col border border-1">
                <h5 class="border-bottom text-center org-name">MGSAMIDAN CONSTRUCTION AND DEVELOPMENT CORPORATION</h5>
                <p class="text-center fw-bold">Payslip Summary</p>
                <div class="border-top py-2 px-4">
                    <p><strong>Date Generated:</strong> <span class="border-bottom">{{ Carbon\Carbon::now()->format('F j, Y') }}</span></p>
                    <p>
                        <strong>From:</strong> 
                        <span class="border-bottom" 
                        style="{{ $startDate === '' || $startDate === null ? 'color: red;' : '' }}"
                        >
                        {{ $startDate === '' || $startDate === null ? 'Start date not set'  : $startDate }}
                        </span>
                    </p>
                    <p>
                        <strong>To:</strong> 
                        <span class="border-bottom" 
                        style="{{ $endDate === '' || $filterTo === null ? 'color: red;' : '' }}"
                        >
                        {{ $endDate === '' || $endDate === null ? 'End date not set'  : $endDate }}
                        </span>
                    </p>

                </div>
                {{-- <div class=" card table-responsive col-12 p-3"> --}}
                    {{-- Table start --}}
                <div class="card col-12">
                    <table class="table auto">
                        <thead class="align-text-center">
                            <tr>
                                <th class="border text-center">Name </th>
                                <th class="border text-center">Job Title</th>
                                <th class="border text-center">Site Location</th>
                                <th class="border text-center">Days</th>
                                <th class="border text-center">Total Overtime</th>
                                <th class="border text-center">Rate</th>
                                <th class="border text-center"> Gross Total</th>
                                <th class="border text-center">Deductions</th>
                                <th class="border text-center"> Net Total</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 2vh;">
                            @php
                                $summaryTotal= 0;
                            @endphp
                            @foreach($getEmployee as $employee)
                            <tr>
                            {{-- @dd($employee->job_title) --}}
                            <td class="d-none"></td>
                            <td class=" border " wire:model="emp_name">{{ Str::ucfirst(Str::lower($employee->first_name)) }} {{ Str::ucfirst(Str::lower($employee->last_name)) }}</td>
                            {{-- <td class="col-1 border">{{ $employee->job_title }}</td> --}}
                            <td class=" border" wire:model="emp_site">
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
                            <td class=" border">
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
                            <td class=" border" wire:model="emp_rate">
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
                            <td class=" border" wire:model="emp_days">
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
                            <td class=" border" >
                                @php
                                    // (double)$grossPay = $computeOt + $rateAttendanceTotal;
                                    (double)$grossPay = $computeOtPerJobRate + $computeDaysPerJobRate;
                                @endphp
                                {{number_format($grossPay, 2)}}
                            </td>
                            {{-- Deductions --}}
                            <td class=" border">
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
                            <td class=" border">
                                @php
                                    $netTotal = $grossPay - $totalCashAdvances;
                                    $summaryTotal += $netTotal;
                                    @endphp
                                    {{-- @dd($summaryTotal); --}}
                                {{number_format($netTotal, 2)}}
                               
                            </td>
                            {{-- <td class="col-1 border">
                                <div class="d-flex justify-content-center align-items-center " style="height: 100%">
                                    @php
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
                                                $jobTitle .= $jobInfo->job_title . '-';
                                                $empSiteName .= $jobInfo->site_name . '-';
                                                $empRate .= $jobInfo->job_title_rate . '-';
                                            }
                                        }
                                        $jobTitle = rtrim(str_replace('-', '|', $jobTitle), '|');
                                        $empSiteName = rtrim(str_replace('-', '|', $empSiteName), '|');
                                        $empRate = rtrim(str_replace('-', '|', $empRate), '|');
                                        
                                    @endphp
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
                                        
                                        ]) }}" >
                                        <span class="bi bi-download point" style="font-size: 1rem; margin-right: 0.5rem;"
                                            data-toggle="tooltip" title="Download Payslip"></span>
                                    </a>
                                </div>
                            </td> --}}
                            </tr>
                            {{-- {{  }} --}}
                            @endforeach
                            
                            
                            </tbody>
                    </table>
                    
                    <span>All Site Total: {{ number_format($summaryTotal, 2) }}</span>
                            
                </div>
            </div>
        </div>

    </div>
</body>
</html>