@extends('../layout/layout')


@section('page-heading')
<h4>Backup Database</h4>
@endsection

@section('page-content')

<section class="row">
    <div class="col-12 col-lg-12">
        <div class="card"> {{-- start of card --}}
            <div class="card-header">
                <h6 class="text-end">{{\Carbon\Carbon::now()->toFormattedDateString()}}</h6>
            </div>
            <div class="card-body">
                <div class="row my-2">
                    <div class="col-lg-3 col-md-3 col-sm-3 text-end">
                        <span>
                            Genrate Backup
                        </span>
                        &nbsp;
                        <i class="bi bi-arrow-bar-right"></i>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <a href="{{route('download.db')}}" class="btn btn-primary">
                            <i class="bi bi-download"></i> &nbsp;
                            Download
                        </a>
                    </div>
                </div>
            </div>{{-- end of card body --}}
        </div> {{-- end of card --}}
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