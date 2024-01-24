@extends('../layout/layout')


@section('page-heading')
<h4>Employees in {{$workingSite->site_name}}</h4>
@endsection

@section('page-content')
@livewire('working-sites-management.working-sites-employees', ['siteId' => $workingSite->id])
@endsection

