<div>
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="text-end">{{\Carbon\Carbon::now()->toFormattedDateString()}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3 align-items-center">
                                <div class="col-lg-1 col-md-1 col-sm-1"></div>
                                <div class="col-lg-1 col-md-1 col-sm-1 text-end">
                                    <strong>Filter</strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <div class="input-group">
                                        <input 
                                            type="month" 
                                            class="form-control" 
                                            wire:model="filterByMonth"
                                            value="{{$filterByMonth ?? ''}}"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-5 col-lg-5 col-sm-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Site name..." aria-label=""
                                            aria-describedby="button-addon2" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Search by site name" wire:model.debounce.3000="searchString">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <a href="#" wire:click.prevent="clearFilter()">Clear all</a>
                                </div>
                            </div>
                            @php
                                $sumOfTotalSalaryPerSite = 0;
                                $data = [];
                            @endphp
                            @if ($filterByMonth)    
                            <div class="row my-2">
                                <div class="col">
                                    <h5 class="border-bottom text-center">
                                        {{$filterByMonthFormatted ?? ''}}
                                        
                                    </h5>
                                    <span class="float-end">
                                        <a href="#" wire:click="downLoadSummary()" class="btn btn-primary">Download</a>
                                    </span>
                                </div>
                            </div>
                            @endif
    
                            <div class="table-responsive" style="width: 80%; margin: auto;">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Site name</th>
                                            <th>Total salary expenses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach ($sites as $index => $site)
                                        <tr>
                                            <td class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    <p class="font-bold ms-3 mb-0">
                                                        {{$site->site_name}}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                @php
                                                    foreach ($totalSalaries as $siteId => $totalSalary) {
                                                        if ($siteId === $site->id) {
                                                            $sumOfTotalSalaryPerSite += $totalSalary;
                                                            echo number_format($totalSalary, 2);
                                                        }
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th scope="row">Sum of all sites salaries</th>
                                            <td>{{number_format($sumOfTotalSalaryPerSite, 2)}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
    
                            <div class="row" style="width: 80%; margin: auto;">
                                <div class="col d-flex justify-content-start align-items-center">
                                    <strong>Total: </strong> &nbsp; {{ $sites->count() }}
                                </div>
                                {{-- <div class="col d-flex justify-content-end">
                                    {{ $sites->links() }}
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
