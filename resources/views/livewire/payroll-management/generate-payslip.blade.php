<div>
    <div class="card-content row flex-wrap">
        <div class="card-body d-flex justify-content-start align-items-center">
            <div class=" card table-responsive col-12 font-bold p-3">
                <div class="d-flex mb-2 row">
                    {{-- <div class="mr-3" style="max-width: 10rem;"> --}}
                        <div class="row mb-3 align-items-center">
                            <div class="col-lg-1 col-md-1 col-sm-1 text-center">
                                <strong>Filter</strong>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="input-group">
                                    <select class="choices form-select">
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
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <label for="">Date from</label>
                                <input type="date" wire:model="dateFrom" id="">
                                {{-- {{ $dateFrom }} --}}
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <label for="">Date to</label>
                                <input type="date" wire:model="dateTo" id="">
                                {{-- {{ $dateTo }} --}}
                            </div>
                        </div>
                        {{--
                    </div> --}}


                    <div class="d-flex justify-content-start m-0" style="max-width: 15rem; margin-left: 2rem;">
                        
                        @if(!$dateFrom && !$dateTo)
                            <a href="{{ route('download.payslip', ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]) }}" class="btn btn-success d-none" >
                                Generate Payslip
                            </a>
                        @else
                            <a href="{{ route('download.payslip', ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]) }}" class="btn btn-success" >
                                Generate Payslip
                            </a>
                        @endif
                    </div>
                    <div class="" style="max-width: 10rem; margin-left: 3rem;">
                        {{-- @if(!$dateFrom && !$dateTo)
                        <button class="btn btn-outline-success d-none" data-bs-toggle="modal" data-bs-target="#xlarge">
                            Salaries Summary
                        </button>
                        @else
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#xlarge">
                            Salaries Summary
                        </button>
                        @endif --}}

                    </div>
                </div>
                {{-- Table start --}}
                <table class="table bordered bg-white table-hover ">
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
                            <th class="border text-center">Action</th>
                        </tr>
                    </thead>
                    @foreach($getEmployee as $employee)
                    {{-- @dd($employee->job_title) --}}
                    <td class="d-none"></td>
                    <td class="col-2 border" wire:model="emp_name">{{ Str::ucfirst(Str::lower($employee->first_name)) }} {{ Str::ucfirst(Str::lower($employee->last_name)) }}</td>
                    {{-- <td class="col-1 border">{{ $employee->job_title }}</td> --}}
                    <td class="col-1 border">{{ $employee->job_title === null || $employee->job_title === '' ? 'No Job Title' : $employee->job_title }}</td>                    
                    <td class="col-2 border" wire:model="emp_site">{{ $employee->site_name }}</td>
                    <td class="col-1 border" wire:model="emp_days">{{ array_key_exists($employee->employee_id, $totalDays) ?
                        $totalDays[$employee->employee_id] : '0'}}</td>
                    <td class="col-1 border" wire:model="emp_total_ot">{{ number_format(array_key_exists($employee->employee_id, $totalOvertime) ?
                        $totalOvertime[$employee->employee_id] : '0',2)}}</td>
                    {{-- <td class="col-1 border">{{ $employee->daily_rate }}</td> --}}
                    <td class="col-1 border" wire:model="emp_rate">{{  $employee->job_title_rate === null || $employee->job_title_rate === '' ? 0 : $employee->job_title_rate }}</td>
                    @php
                    $OT = array_key_exists($employee->employee_id, $totalOvertime) ?
                    $totalOvertime[$employee->employee_id] : 0;
                    $totalOT = number_format(($employee->job_title_rate /8)* $OT,2);
                    $totalGrossWithOT = array_key_exists($employee->employee_id, $totalDays) ?
                    $totalDays[$employee->employee_id] * $employee->job_title_rate + $totalOT : '0';
                    $deductions = number_format(array_key_exists($employee->employee_id, $totalCashAdvance) ?
                    $totalCashAdvance[$employee->employee_id] : '0',2);
                    $finalPay = number_format($totalGrossWithOT - $deductions,2);
                    @endphp
                    {{-- gross total --}}
                    <td class="col-1 border" wire:model="emp_gross_total">{{ number_format(array_key_exists($employee->employee_id, $totalDays) ?
                        $totalDays[$employee->employee_id] * $employee->job_title_rate + $totalOT : '0',2)}}</td>
                    {{-- deductions --}}
                    <td class="col-1 border" wire:model="emp_deductions">{{ number_format(array_key_exists($employee->employee_id,
                        $totalCashAdvance) ? $totalCashAdvance[$employee->employee_id] : '0',2)}}</td>
                    {{-- net total --}}
                    <td class="col-1 border" wire:model="emp_final_pay">{{ $finalPay }}</td>
                    <td class="col-1 border">
                        <div class="d-flex justify-content-center align-items-center " style="height: 100%">
                            {{-- {{route('dl.pdf', ['id' => $employeeId, 'ecaid' => $cashAdvance->id])}} --}}
                            <a href="{{route('single.download.payslip', [
                                'id' => $employee->employee_id, 
                                'dateFrom' => $dateFrom,
                                'dateTo' => $dateTo,
                                'emp_name' => $employee->first_name . ' ' . $employee->last_name,
                                'emp_job_title' => $employee->job_title,
                                'emp_site' => $employee->site_name,
                                'emp_days' => array_key_exists($employee->employee_id, $totalDays) ? $totalDays[$employee->employee_id] : '0',
                                'emp_total_ot' => number_format(array_key_exists($employee->employee_id, $totalOvertime) ? $totalOvertime[$employee->employee_id] : '0',2),
                                'emp_rate' => $employee->job_title_rate,
                                'emp_gross_total' =>array_key_exists($employee->employee_id, $totalDays) ? $totalDays[$employee->employee_id] * $employee->daily_rate + $totalOT : '0',
                                'emp_deductions' => number_format(array_key_exists($employee->employee_id, $totalCashAdvance) ? $totalCashAdvance[$employee->employee_id] : '0',2),
                                'emp_final_pay' => $finalPay,
                                
                                ]) }}" >
                                <span class="bi bi-download point" style="font-size: 2rem; margin-right: 0.5rem;"
                                    data-toggle="tooltip" title="Download Payslip"></span>
                            </a>
                        </div>
                    </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="col d-flex justify-content-start align-items-center">
                        {{-- <strong>Total: </strong> <small>{{ $getEmployee->total() }}</small> --}}
                    </div>
                    <div class="col d-flex justify-content-end">
                        {{-- {{ $getEmployee->links() }} --}}
                    </div>
                </div>
                {{-- Table end --}}
                <!-- Button trigger for Extra Large  modal -->
                {{-- <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                    data-bs-target="#xlarge">
                    Extra Large Modal
                </button> --}}

                <!--Generate Modal Start-->
                {{-- <div class="modal fade text-left w-100" id="generateModal" tabindex="-1" role="dialog"
                    aria-labelledby="salarySummary" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="salarySummary">Salary Summary</h4>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <label for="">Date from</label>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <label for="">Date to</label>
                                </div>
                            </div>
                            <div class="modal-body">
                                <table class="table bordered bg-white table-hover table-sm">
                                    <thead class="align-text-center">
                                        <tr>
                                            <th class="border text-center">Site </th>
                                            <th class="border text-center">Amount</th>
                                            <th class="border text-center">Cash</th>
                                            <th class="border text-center">Source</th>
                                            <th class="border text-center"> Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            @foreach($getEmployee as $employee)
                                            <td class="d-none"></td>
                                            <td class="col-2 border">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                            
                                            <td class="col-2 border">{{ $employee->site_name }}</td>
                                            <td class="col-1 border">{{ array_key_exists($employee->employee_id, $totalDays) ?
                                                $totalDays[$employee->employee_id] : '0'}}</td>
                                            <td class="col-1 border">{{ number_format(array_key_exists($employee->employee_id, $totalOvertime) ?
                                                $totalOvertime[$employee->employee_id] : '0',2)}}</td>
                                            <td class="col-1 border">{{ $employee->daily_rate }}</td>
                                            @php
                                            $OT = array_key_exists($employee->employee_id, $totalOvertime) ?
                                            $totalOvertime[$employee->employee_id] : 0;
                                            $totalOT = number_format(($employee->daily_rate /8)* $OT,2);
                                            $totalGrossWithOT = array_key_exists($employee->employee_id, $totalDays) ?
                                            $totalDays[$employee->employee_id] * $employee->daily_rate + $totalOT : '0';
                                            $deductions = number_format(array_key_exists($employee->employee_id, $totalCashAdvance) ?
                                            $totalCashAdvance[$employee->employee_id] : '0',2);
                                            $finalPay = number_format($totalGrossWithOT - $deductions,2);
                                            @endphp
                                            <td class="col-1 border">{{ number_format(array_key_exists($employee->employee_id, $totalDays) ?
                                                $totalDays[$employee->employee_id] * $employee->daily_rate + $totalOT : '0',2)}}</td>
                                          
                                            <td class="col-1 border">{{ number_format(array_key_exists($employee->employee_id,
                                                $totalCashAdvance) ? $totalCashAdvance[$employee->employee_id] : '0',2)}}</td>
                                          
                                            <td class="col-1 border">{{ $finalPay }}</td>
                                            </tr>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary ml-1" >
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block ">
                                        <i class=" bi-printer"></i>
                                        Print
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!--Generate Modal End-->
                <!--Salary Summary Modal Start-->
                {{-- <div class="modal fade text-left w-100" id="xlarge" tabindex="-1" role="dialog"
                    aria-labelledby="salarySummary" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="salarySummary">Monthly Salary Summary</h4>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <i data-feather="x"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table bordered bg-white table-hover table-sm">
                                    <thead class="align-text-center">

                                        <tr>
                                            <th class="border text-center">Site </th>
                                            <th class="border text-center">Amount</th>
                                            <th class="border text-center">Cash</th>
                                            <th class="border text-center">Source</th>
                                            <th class="border text-center"> Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td class="d-none"></td>
                                            <td class="col-2 border ">Site Placeholder</td>
                                            <td class="col-1 border">Amount Placeholder</td>
                                            <td class="col-2 border">Cash Placeholder</td>
                                            <td class="col-1 border">Source Placeholder</td>
                                            <td class="col-1 border">Date Placeholder</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Accept</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!--Salary Summary Modal End-->
            </div>

        </div>
    </div>
</div>