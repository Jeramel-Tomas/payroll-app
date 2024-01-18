<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeePayrollInfoController extends Controller
{
    public function index()
    {
        return view('employee-payroll-management.payrollManagementIndex');
    }

    public function generatePayslip()
    {
        return view('employee-payroll-management.generatePayslipIndex');
    }

    public function cashAdvancedIndex()
    {
        return view('employee-payroll-management.cashAdvancedIndex');
    }
}
