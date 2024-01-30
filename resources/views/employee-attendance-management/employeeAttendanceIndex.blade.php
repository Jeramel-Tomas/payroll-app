@extends('../layout/layout')


@section('page-heading')
<h4>Attendance Management</h4>
@endsection

@section('page-content')
    @livewire('employee-attendance-management.employee-attendance')
@endsection

@push('css-imports')
{{-- <style>
    .in-out-error {
        font-size: 0.875em;
    }
</style> --}}
@endpush