@extends('../layout/layout')


@section('page-heading')
<h4>View {{$fullName}} Cash advances</h4>
@endsection

@section('page-content')

<section class="row">
    <div class="col-12 col-lg-12">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="">
                            <span class="float-start">
                                <a 
                                    href="{{route('cash.advanced.index')}}"
                                    class="btn btn-primary"
                                >
                                    <i class="bi bi-arrow-return-left"></i> &nbsp; Back
                                </a>
                            </span>
                            <span class="float-end">{{\Carbon\Carbon::now()->toFormattedDateString()}}</span>
                        </h6>
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
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Purpose</th>
                                        <th>Date assigned</th>
                                        <th>Date created</th>
                                        <th>DL | Edit</th>
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
                                                </a>  &nbsp; &nbsp;
                                                <a href="#"
                                                    {{-- href="{{route('cash.advances.edit', ['id' => $employeeId, 'ecaid' => $cashAdvance->id])}}" --}}
                                                    id="editCashAdvance"
                                                    data-caid="{{ $cashAdvance->id }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editCashAdvanceModal"
                                                >
                                                    <i class="bi bi-pencil-square"></i>
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

<div class="modal fade" id="editCashAdvanceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit cash advance</h5>
                <a href="{{route('view.employee.cash.advances', ['id'=>$employeeId])}}" 
                    class="btn-close" 
                    {{-- data-bs-dismiss="modal"  --}}
                    aria-label="Close"></a>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissible fade d-none" id="successfullEdit" role="alert">
                    Successfully updated!
                </div>
                <form {{-- action="{{route('save.edit.cash.advance')}}" --}}>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">
                            <span class="text-danger">*</span>
                            Amount:
                        </label>
                        <input type="number" name="amount" class="form-control" id="amount" value="">
                        <input type="hidden" name="cashAdvanceId" class="form-control" id="cashAdvanceId" value="">
                        <span class="text-danger amountError"></span>
                    </div>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">
                            <span class="text-danger">*</span>
                            Date:
                        </label>
                        <input type="date" name="cashAdvanceDate" class="form-control" id="cashAdvanceDate" value="">
                        <span class="text-danger dateError"></span>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Purpose:</label>
                        <textarea class="form-control" id="purpose"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="saveChanges" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js-code')
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    console.log('doc is reday');
   {{-- $('#editCashAdvance').on('click',function (event) { --}}
   $(document).on('click', "#editCashAdvance",  function (event) {
        event.preventDefault();
    
        var caid = $(this).data('caid');

        console.log(caid);
        $.ajax({
            url: '/payroll/cash-advanced/edit/{eid}',
            type: 'GET',
            data: { caidEdit: caid },
            success: function(response)
            {
                console.log(response.data);
                {{-- $('#exampleModalLabel').text('loading'); --}}
                $('#amount').prop('value', response.data.amount);
                $('#cashAdvanceId').prop('value', response.data.id);
                $('#cashAdvanceDate').prop('value', response.data.cash_advanced_date);
                $('#purpose').text(response.data.purpose);
            }
        });
    });

    $(document).on('click', '#saveChanges', function (event) {
        event.preventDefault();
        var amountVal = $('#amount').prop('value');
        var cashAdvanceId = $('#cashAdvanceId').prop('value');
        var cashAdvanceDate = $('#cashAdvanceDate').prop('value');
        var purpose = $('#purpose').prop('value');

        if (amountVal === '') {
            $('.amountError').html("Amount must not be empty! but you can put 0.");
        } else if (cashAdvanceDate === '') {
            $('.dateError').html("Date must not be empty!");
        } else {
            $('.amountError').html('');
            $('.dateError').html('');
            console.log('start saving in the DB...');
            console.log('success message...');

            $.ajax({
                url: '/payroll/cash-advanced/save-cashadvance-edit/',
                type: 'POST',
                data: { 
                    caId: cashAdvanceId,
                    amount: amountVal,
                    caDate: cashAdvanceDate,
                    purpose: purpose
                },
                success: function(response) {
                    console.log(response.message);
                    $('#successfullEdit').removeClass(' d-none');
                    $('#successfullEdit').addClass(' show');

                    setTimeout(function () {
                        $('#successfullEdit').addClass(' fade d-none');
                        $('#successfullEdit').removeClass(' show');
                    }, 5000);
                    {{-- successfullEdit --}}
                }
            });
        }
        console.log($('#amount').prop('value'));
    });

});
@endpush