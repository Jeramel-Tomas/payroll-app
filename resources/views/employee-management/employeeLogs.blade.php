@extends('../layout/layout')
@section('main-content')
<div class="container">
    <h1>Employee Attendance</h1>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    
        <a href="" class="float-right btn btn-primary mr-3 pd-2 mb-2 material-icons" data-toggle="tooltip" title="Add Attendance" >
            <i class="material-icons">add_circle</i>
        </a>
        <a href="" class="float-right btn btn-danger mr-3 pd-2 mb-2" >Report</a>

    <div class="table-responsive ">
        <table class="table border border-secondary border-2 border-lg-3 p-4">
            
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Attendance Status</th>
                    <th>Attendance Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                {{-- @foreach($employees as $key => $employee) --}}
                <tr>
                    <td class="d-none"></td>
                    <td>first_name  </td>
                    <td>
                        <select class="form-control" id="attendance" name="attendance">
                            <option value="1">Whole Day</option>
                            <option value=".5">Half Day</option>
                            <option value="0">Absent</option>
                        </select>
                    </td>
                    <td>

                    </td>
                    <td></td>
                </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
        <a href="" class="float-right btn btn-success mr-3 pd-2 mb-2 material-icons" data-toggle="tooltip" title="Add Attendance" >
            <i class="material-icons">save</i>
        </a>
    </div>
</div>

@endsection