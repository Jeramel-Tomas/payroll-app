<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use Illuminate\Support\Carbon;

class AttendanceLogController extends Controller
{
    public function index()
    {
        /* $employees = EmployeeInformation::select(
            'id', 
            'employee_uuid', 
            'first_name',
            'last_name',
            )
            ->paginate(5);
        $sites = WorkingSite::all(); */
        
        // $employees = Employee::paginate(15)->withQueryString();
        // $employees = DB::table('employee_info')->simplePaginate(1);
        //dd($employees);
        // return view('attendance-log-management.attendanceLogIndex', ['employees' => $employees, 'sites' => $sites]);
        return view('attendance-log-management.attendanceLogIndex');
    }

    public function attendanceSheetIndex()
    {
        return view('attendance-log-management.attendanceSheetIndex');
    }

    public function showAttendanceLogPerSite(string $siteId)
    {
        $sites = WorkingSite::all();
        $specificSite = WorkingSite::find($siteId);

        $employeesWorkingSite = DB::table('employee_working_sites AS ews')
            ->join('employee_information AS ei', 'ews.employee_information_id', '=', 'ei.id')
            ->join('working_sites AS ws', 'ews.working_site_id', '=', 'ws.id')
            ->leftJoin('attendance_logs AS al', 'ews.employee_information_id', '=', 'al.employee_information_id')
            ->select(
                'ei.first_name', 
                'ei.last_name', 
                'ei.id AS employeeId', 
                'ei.job_title',
                'al.attendance_status',
                'al.overtime_per_day',
                'al.attendance_date'
            )
            ->where('working_site_id', '=', $siteId)
            ->paginate(5);
        // dd($employeesWorkingSite);

        /* dump($today = Carbon::today());
        dump($today = Carbon::now()); */
        return view('attendance-log-management.attendanceLogPerSite', compact('employeesWorkingSite', 'sites', 'specificSite'));
    }

    public function saveAttendanceAjax(Request $request)
    {
        if ($request->ajax()) {
            // dd($request->get('employeeId'));
            /* $daysOfWorked = $request->get('daysOfWorked');
            $employeeId = $request->get('employeeId'); */
            if ($request->get('daysOfWorked') && $request->get('employeeId')) {
                DB::table('attendance_logs')
                    ->updateOrInsert(
                        [
                            'employee_information_id' => $request->get('employeeId'), 
                            'attendance_date' => Carbon::today()
                        ],
                        [
                            'employee_information_id' => $request->get('employeeId'),
                            'attendance_status' => $request->get('daysOfWorked'),
                            'attendance_date' => Carbon::today(),
                            'created_at' => Carbon::today(),
                            'updated_at' => Carbon::today()
                        ]
                    );
            }
           
            if ($request->get('ovetimeHours') && $request->get('employeeId')) {
                DB::table('attendance_logs')
                    ->where('employee_information_id', $request->get('employeeId'))
                    ->where('attendance_date', Carbon::today())
                    ->update(['overtime_per_day' => $request->get('ovetimeHours')]);
            }

        }
        // dd('false');
        return response()->json(array('msg' => 'Saved the attendance'));
    }
}
