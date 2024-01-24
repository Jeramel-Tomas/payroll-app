@extends('../layout/layout')


@section('page-heading')
<h4>Working Sites Management</h4>
@endsection

@section('page-content')
    @livewire('working-sites-management.working-sites-index')
@endsection

@push('css-imports')
{{-- <style>
    .in-out-error {
        font-size: 0.875em;
    }
</style> --}}
@endpush