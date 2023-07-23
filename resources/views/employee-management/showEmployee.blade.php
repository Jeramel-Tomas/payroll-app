@extends('../layout/layout')
@section('main-content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<section class="section">
    <div class="modal fade in" id="viewEmployeeModal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> {{ $employee->first_name }} {{ $employee->last_name }} Information</h5>
                    <a href="{{ route('employees.list') }}" class="close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Job title</label>
                        <input type="text" class="form-control" disabled value="{{ $employee->job_title }}">
                    </div>
                    <div class="form-group">
                        <label>Daily Rate</label>
                        <input type="text" class="form-control" disabled value="{{ $employee->daily_rate }}">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <input type="text" class="form-control" disabled value="{{ $employee->gender }}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" disabled value="{{ $employee->address }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" disabled value="{{ $employee->contact_number }}">
                    </div>
                    <div class="form-group">
                        <label>Date of Employment</label>
                        <input type="text" class="form-control" disabled value="{{ $employee->employment_date }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('employees.list') }}" class="btn btn-secondary">Close</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#viewEmployeeModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
</script>

@endsection