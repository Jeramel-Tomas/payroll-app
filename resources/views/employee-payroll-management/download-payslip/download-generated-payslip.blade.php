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
                <p class="text-center fw-bold">Payslip</p>
                <div class="border-top py-2 px-4">
                    <p><strong>Date today:</strong> <span class="border-bottom">{{ Carbon\Carbon::now()->format('F j, Y') }}</span></p>
                    <p>
                        <strong>From:</strong> 
                        <span class="border-bottom" 
                        style="{{ $dateFrom === '' || $dateFrom === null ? 'color: red;' : '' }}"
                        >
                        {{ $dateFrom === '' || $dateFrom === null ? 'Start date not set'  : $dateFrom }}
                        </span>
                    </p>
                    <p>
                        <strong>To:</strong> 
                        <span class="border-bottom" 
                        style="{{ $dateTo === '' || $dateTo === null ? 'color: red;' : '' }}"
                        >
                        {{ $dateTo === '' || $dateTo === null ? 'End date not set'  : $dateTo }}
                        </span>
                    </p>

                </div>
                {{-- <div class=" card table-responsive col-12 p-3"> --}}
                    {{-- Table start --}}
                    <table class="table bordered bg-white">
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
                        <tbody>
                        @foreach($getEmployee as $employee)
                            <tr>
                                <td class="d-none"></td>
                                <td class="col-2 border">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                {{-- <td class="col-1 border">{{ $employee->job_title }}</td> --}}
                                <td class="col-1 border">{{ $employee->job_title === null || $employee->job_title === '' ? 'No Job Title' : $employee->job_title }}</td> 
                                <td class="col-2 border">{{ $employee->site_name }}</td>
                                <td class="col-1 border">{{ array_key_exists($employee->employee_id, $totalDays) ?
                                    $totalDays[$employee->employee_id] : '0'}}</td>
                                <td class="col-1 border">{{ number_format(array_key_exists($employee->employee_id, $totalOvertime) ?
                                    $totalOvertime[$employee->employee_id] : '0',2)}}</td>
                                <td class="col-1 border">{{ $employee->job_title_rate }}</td>
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
                                <td class="col-1 border">{{ number_format(array_key_exists($employee->employee_id, $totalDays) ?
                                    $totalDays[$employee->employee_id] * $employee->job_title_rate + $totalOT : '0',2)}}</td>
                                {{-- deductions --}}
                                <td class="col-1 border">{{ number_format(array_key_exists($employee->employee_id,
                                    $totalCashAdvance) ? $totalCashAdvance[$employee->employee_id] : '0',2)}}</td>
                                {{-- net total --}}
                                <td class="col-1 border">{{ $finalPay }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="5">
                                    <p class="text-start mb-4 pb-4 px-4">Approved by:</p>
                                    <p class=""></p>
                                </td>
                                <td colspan="2">
                                    <p class="mb-4 pb-4 text-end px-3">Received by:</p>
                                    <p class="text-end">Signature over printed name</p>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
    
                    
                {{-- </div> --}}
            </div>
        </div>

    </div>
</body>
</html>