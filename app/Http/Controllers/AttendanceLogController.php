<?php

namespace App\Http\Controllers;

use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;

class AttendanceLogController extends Controller
{
    public function index()
    {
        $employees = EmployeeInformation::paginate(5);
        $sites = WorkingSite::all();
        // $employees = Employee::paginate(15)->withQueryString();
        // $employees = DB::table('employee_info')->simplePaginate(1);
        //dd($employees);
        return view('attendance-log-management.attendanceLogIndex', ['employees' => $employees, 'sites' => $sites]);
    }

    public function showAttendanceLogPerSite(string $siteId)
    {
        $sites = WorkingSite::all();

        $employeesWorkingSite = DB::table('employee_working_sites')
            ->join('employee_information', 'employee_working_sites.employee_information_id', '=', 'employee_information.id')
            ->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
            ->where('working_site_id', '=', $siteId)
            ->paginate(5);
        // dd($employeesWorkingSite);
        return view('attendance-log-management.attendanceLogPerSite', compact('employeesWorkingSite', 'sites'));
    }

    public function saveAttendanceAjax(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->get('daysOfWork'));
            $daysOfWork = $request->get('daysOfWork');
        }
        // dd('false');
        return response()->json(array('msg' => 'Saved the attendance'));
    }
}
