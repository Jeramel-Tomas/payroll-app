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
                <h5 class="border-bottom text-center ">MGSAMIDAN CONSTRUCTION AND DEVELOPMENT CORPORATION</h5>
                <p class="text-center fw-bold">EMPLOYEE PAYSLIP </p>
                <div class="border-top py-2 px-4 text-end">
                     <p><strong>Date Generated:</strong> <span class="border-bottom">{{ Carbon\Carbon::now()->format('F j, Y') }}</span></p>
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
                <p class="text-center">
                    <strong>Site Location:</strong> <span>{{ $emp_site }}</span>
                </p>
                <div class="container mt-4  table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td class="fw-bold">Employee Name:</td>
                            <td>{{ $emp_name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Job Title:</td>
                            <td>{{ $emp_job_title }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Rate:</td>
                            <td>{{ $emp_rate }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Days:</td>
                            <td>{{ $emp_days }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Total Overtime:</td>
                            <td>{{ $emp_total_ot }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Gross Total:</td>
                            <td>{{ $emp_gross_total }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Deductions:</td>
                            <td>{{ $emp_deductions }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Net Total:</td>
                            <td>{{ $emp_final_pay }}</td>
                        </tr>
                    </table>
                    
                </div>
                
                {{-- <div class=" card table-responsive col-12 p-3"> --}}
                    {{-- Table start --}}
                    <table class="table bordered bg-white">
                        <thead class="align-text-center">
                        </thead>
                        <tbody>
                            <tr>
                                {{-- <td class="d-none"></td>
                                <td class="col-2 border">{{ $emp_name }}</td>
                                <td class="col-1 border">{{ $emp_job_title }}</td>
                                <td class="col-2 border">{{ $emp_site }}</td>
                                <td class="col-1 border">{{ $emp_days }}</td>
                                <td class="col-1 border">{{ $emp_total_ot }}</td>
                                <td class="col-1 border">{{ $emp_rate }}</td>
                                <td class="col-1 border">{{ $emp_gross_total }}</td>
                                <td class="col-1 border">{{ $emp_deductions }}</td>
                                <td class="col-1 border">{{ $emp_final_pay }}</td> --}}
                            </tr>
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