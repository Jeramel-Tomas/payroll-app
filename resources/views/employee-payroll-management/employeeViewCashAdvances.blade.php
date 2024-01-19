@extends('../layout/layout')


@section('page-heading')
<h4>View {{$fullName}} Cash advances</h4>
@endsection

@section('page-content')

{{-- @foreach ($cashAdvances as $item)
    {{$item->amount}}
@endforeach

{{$cashAdvances->links()}} --}}


<section class="row">
    <div class="col-12 col-lg-12">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-end">{{\Carbon\Carbon::now()->toFormattedDateString()}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                            <form action="{{route('date.filter.cashadvance', ['id' => $employeeId])}}">
                                <strong>Filter by date:</strong>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="input-group">
                                    <input type="date" name="dateFrom" value="{{ old('dateFrom', $dateFrom) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="input-group">
                                    <input type="date" name="dateTo" value="{{ old('dateTo', $dateTo) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="input-group">
                                    <button type="submit" class="btn btn-secondary">Ok</button>
                                </div>
                            </div>
                            </form>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <a class="btn btn-info" href="{{route('view.employee.cash.advances', ['id'=>$employeeId])}}">Clear filter</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            {{-- @dd($this->employees) --}}
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Purpose</th>
                                        <th>Date assigned</th>
                                        <th>Date created</th>
                                        <th>Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cashAdvances as $cashAdvance)
                                    <tr>
                                        <td class="col-auto">
                                            <div class="d-flex align-items-center">
                                                {{$cashAdvance->amount}}
                                            </div>
                                        </td>
                                        <td class="col-auto">
                                            <div class="d-flex align-items-center overflow-auto">
                                                {{$cashAdvance->purpose}}
                                            </div>
                                        </td>
                                        <td class="col-auto">
                                            <div class="d-flex align-items-center">
                                                {{
                                                    \Carbon\Carbon::parse($cashAdvance->cash_advanced_date)->toFormattedDateString()
                                                }}
                                            </div>
                                        </td>
                                        <td class="col-auto">
                                            <div class="d-flex align-items-center">
                                                {{
                                                    \Carbon\Carbon::parse($cashAdvance->created_at)->toFormattedDateString()
                                                }}
                                            </div>
                                        </td>
                                        <td class="col-auto">
                                            <div class="d-flex align-items-center">
                                                <a href="{{route('dl.pdf', ['id' => $employeeId, 'ecaid' => $cashAdvance->id])}}">
                                                    <i class="bi bi-box-arrow-down"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row" style="width: 80%; margin: auto;">
                            <div class="col d-flex justify-content-start align-items-center">
                                <strong>Total: </strong> &nbsp; {{ $cashAdvances->total() }}
                            </div>
                            <div class="col d-flex justify-content-end">
                                {{ $cashAdvances->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection



@push('css-imports')
{{-- <style>
    .in-out-error {
        font-size: 0.875em;
    }
</style> --}}
@endpush