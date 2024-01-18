@extends('../layout/layout')


@section('page-heading')
<h4>Cash Advance Management</h4>
@endsection

{{-- @section('page-content')
@livewire('payroll-management.employee-payroll-info')
@endsection --}}

{{-- show the list of employees --}}
{{-- table columns [name, total, action] --}}
{{-- actions create and view --}}
{{-- 
    create (one at a time)
    --- cash advance modal
    1. input the cash advance
        validations
            must be numbers only
        column in the DB is char max 12
    2. select date
    3. save
    4. save and DL (PDF only)
    5. format in PDF the cash advanced
--}}

{{-- 
    view
    >>> show in another page
    1. show all cash advances in table
        table columns
            date
            amount
            DL
    2. dl in PDF only
    --}}

@push('css-imports')
{{-- <style>
    .in-out-error {
        font-size: 0.875em;
    }
</style> --}}
@endpush