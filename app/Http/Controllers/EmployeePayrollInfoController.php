<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
// use Barryvdh\DomPDF\PDF as PDF;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use App\Models\EmployeeCashAdvance;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;

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

    public function cashAdvancedView(Request $request)
    {
        // get name from epmloyee_information table
        $empInfo = EmployeeInformation::find($request->id);
        $fullName = $empInfo->first_name . ' ' . $empInfo->last_name;
        $employeeId = $empInfo->id;
        // get all cash advances from employee_cash_advances table
        $cashAdvances = EmployeeCashAdvance::where('employee_information_id', $request->id)
            ->orderBy('created_at');
            // ->paginate(25);
        // ->simplePaginate(1);
        if ($request->dateFrom && $request->dateTo) {
            # code...
            // dump($request->dateFrom);
            // dump($request->dateTo);
            // print_r(Carbon::parse($request->dateTo)->format('Y-m-d H:i:s'));
            // $cashAdvances->where('created_at', '>=', Carbon::parse($request->dateFrom)->format('Y-m-d H:i:S'));
            // $cashAdvances->where('created_at', '<=', Carbon::parse($request->dateTo)->format('Y-m-d H:i:S'));
            $cashAdvances->where([
                ['created_at', '<=', Carbon::parse($request->dateTo)->format('Y-m-d H:i:s')],
                ['created_at', '>=', Carbon::parse($request->dateFrom)->format('Y-m-d H:i:s')],
            ]);
        }

        $empCashAdvances = $cashAdvances->paginate(25);      
        // $cashAdvances = EmployeeCashAdvance::find($request->id);
        // dump($cashAdvances);
        // dump($request->id);
        return view('employee-payroll-management.employeeViewCashAdvances', [
            'fullName' => $fullName,
            'cashAdvances' => $empCashAdvances,
            'employeeId' => $employeeId,
            'dateFrom' => $request->dateFrom ?? '',
            'dateTo' => $request->dateTo ?? '',
        ]);
    }

    public function downLoadPdf(Request $request)
    {
        // dump($request->id);
        // dump($request->ecaid);
        $empInfo = EmployeeInformation::find($request->id);
        $cashAdvances = EmployeeCashAdvance::find($request->ecaid);
        $data = [
            'employee' => $empInfo,
            'cashAdvances' => $cashAdvances,
        ];

        $pdf = PDF::loadView('employee-payroll-management.download-cash-advances.cashAdvancedDl', $data);
        // $pdf->save(public_path('test.pdf'));
        $fileName = $empInfo->last_name.$empInfo->first_name.Carbon::now().'pdf';
        return $pdf->download($fileName);
        /* $users = User::get();

        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            'users' => $users
        ];

        $pdf = PDF::loadView('myPDF', $data);

        return $pdf->download('itsolutionstuff.pdf'); */
    }

    public function downLoadPdfFiltered(Request $request)
    {
        $empInfo = EmployeeInformation::find($request->id);

        $cashAdvances = EmployeeCashAdvance::where('employee_information_id', $request->ecaid)
            ->orderBy('created_at');
        $cashAdvances = EmployeeCashAdvance::find($request->ecaid);
        $data = [
            'employee' => $empInfo,
            'cashAdvances' => $cashAdvances,
        ];

        $pdf = PDF::loadView('employee-payroll-management.download-cash-advances.cashAdvancedDl', $data);
        // $pdf->save(public_path('test.pdf'));
        return $pdf->download('test.pdf');
    }

}
