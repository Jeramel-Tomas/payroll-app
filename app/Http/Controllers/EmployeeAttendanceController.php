<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeAttendanceController extends Controller
{
    public function index()
    {
        return view('employee-attendance-management.employeeAttendanceIndex');
    }
}
