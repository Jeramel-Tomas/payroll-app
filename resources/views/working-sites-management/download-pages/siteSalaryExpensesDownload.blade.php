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
            <div class="col">
                <div>
                    <h5 class="text-center org-name">MGSAMIDAN CONSTRUCTION AND DEVELOPMENT CORPORATION</h5>
                    <p class="text-center fw-bold">Salary expenses report per site</p>
                </div>
                <div class="border-top py-2 px-4">
                    <p>
                        <strong>Date today:</strong> 
                        <span class="border-bottom">{{ Carbon\Carbon::now()->format('F j, Y') }}</span>
                    </p>
                    <p>
                        <strong>Sites salary expenses on:</strong> 
                        <span class="border-bottom">
                            {{ $date_filtered ? Carbon\Carbon::create($date_filtered)->format('F, Y') : '' }}
                        </span>
                    </p>
                    
                </div>
                <div class="table-responsive" style="width: 80%; margin: auto;">
                    <table class="table table-bordered">
                        <thead class="border-bottom">
                            <tr>
                                <th>Site name</th>
                                <th>Total salary expenses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sumOfTotalSalaryPerSite = 0;
                            @endphp
                            @foreach ($data as $key => $item)
                            <tr class="border-bottom">
                                <td class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <p class="font-bold ms-3 mb-0">
                                            {{$item['site_name']}}
                                        </p>
                                    </div>
                                </td>
                                <td class="col-auto">
                                    {{ number_format($item['salary_expenses'], 2) }}
                                    @php
                                        $sumOfTotalSalaryPerSite += $item['salary_expenses'];
                                    @endphp
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row">Sum of all sites salaries</th>
                                <td>{{$total_salaries ? number_format($total_salaries, 2) : '0.00'}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>