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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeePayrollInfoController extends Controller
{
    public $dateFrom = '',
        $dateTo = '';
    //masadutak ag aramid manen query,inkargak amin router haha
    //dibale han makita ta dretso download XD
    public $emp_name='',
        $emp_job_title='',
        $emp_site ='',
        $emp_days=0,
        $emp_total_ot=0,
        $emp_rate =0,
        $emp_gross_total = 0,
        $emp_deductions = 0,
        $emp_final_pay=0;
    public function index()
    {
        return view('employee-payroll-management.payrollManagementIndex');
    }

    public function generatePayslip()
    {
        return view('employee-payroll-management.generatePayslipIndex');
    }
    public function generateSinglePayslipDownload(Request $request)
    {
        // dd($request->query());
        $this->dateFrom = $request->query('dateFrom');
        $this->dateTo = $request->query('dateTo');
        $this->emp_name = $request->query('emp_name');
        $this->emp_job_title = $request->query('emp_job_title');
        $this->emp_site = $request->query('emp_site');
        $this->emp_days = $request->query('emp_days');
        $this->emp_rate = $request->query('emp_rate');
        $this->emp_gross_total = $request->query('emp_gross_total');
        $this->emp_deductions = $request->query('emp_deductions');
        $this->emp_final_pay = $request->query('emp_final_pay');

        $pdf = PDF::loadView('employee-payroll-management.download-payslip.download-single-payslip',
            [
                'dateFrom' => $this->dateFrom,
                'dateTo' => $this->dateTo,
                'emp_name' => $this->emp_name,
                'emp_job_title' => $this->emp_job_title,
                'emp_site' => $this->emp_site,
                'emp_days' => $this->emp_days,
                'emp_total_ot' => $this->emp_total_ot,
                'emp_rate' => $this->emp_rate,
                'emp_gross_total' =>$this->emp_gross_total,
                'emp_deductions' => $this->emp_deductions,
                'emp_final_pay' => $this->emp_final_pay,
            ]
        );
        // return view(
        //     'employee-payroll-management.download-payslip.download-single-payslip',
        //     [
        //         'dateFrom' => $this->dateFrom,
        //         'dateTo' => $this->dateTo,
        //         'emp_name' => $this->emp_name,
        //         'emp_job_title' => $this->emp_job_title,
        //         'emp_site' => $this->emp_site,
        //         'emp_days' => $this->emp_days,
        //         'emp_total_ot' => $this->emp_total_ot,
        //         'emp_rate' => $this->emp_rate,
        //         'emp_gross_total' =>$this->emp_gross_total,
        //         'emp_deductions' => $this->emp_deductions,
        //         'emp_final_pay' => $this->emp_final_pay,
        //     ]
        // );
        
        $fileName = Str::title($this->emp_name).'-'.Carbon::now()->toDateString().'.pdf';
        return $pdf->download($fileName);
    }
    public function generatePayslipDownload(Request $request)
    {
        $this->dateFrom = $request->query('dateFrom');
        $this->dateTo = $request->query('dateTo');
        $employees = EmployeeInformation::all();
        $sites = WorkingSite::all();
        $getAllPayslip = DB::table('employee_information')
            ->leftJoin('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id')
            ->leftJoin('working_sites', 'working_sites.id', '=', 'employee_working_sites.working_site_id')
            ->select('employee_information.id AS employee_id', 'employee_information.*', 'employee_working_sites.*', 'working_sites.*')
            ->whereNull('employee_working_sites.employee_information_id')
            ->orWhereNotNull('employee_working_sites.employee_information_id')
            ->orderBy('employee_working_sites.working_site_id')
            ->get();
        // dd($getAllPayslip);
        foreach ($getAllPayslip as $value) {
            $empCashAdvance = DB::table('employee_cash_advances')
                ->where('employee_information_id', $value->employee_id)
                ->where('cash_advanced_date', '<=', Carbon::now()->endOfMonth()->toDateString());
            if ($this->dateFrom && $this->dateTo) {
                $empCashAdvance->where('cash_advanced_date', '>=', $this->dateFrom);
                $empCashAdvance->where('cash_advanced_date', '<=', $this->dateTo);
            }
            $getCashAdvance[] = $empCashAdvance->get();
            // dump($getCashAdvance);
        }

        foreach ($getAllPayslip as $value) {
            // dump($value->employee_id);
            $empTimeLogs = DB::table('employee_time_logs')
                ->where('employee_information_id', $value->employee_id)
                ->where('attendance_date', '<=', Carbon::now()->endOfMonth()->toDateString());
            if ($this->dateFrom && $this->dateTo) {
                $empTimeLogs->where('attendance_date', '>=', $this->dateFrom);
                $empTimeLogs->where('attendance_date', '<=', $this->dateTo);
            }
            // where(from to) from = 2024-1-15 to = 2024-1-31
            // where(attendance_date >= from)
            // where(attendance_date <= to)
            $getTimeLogs[] = $empTimeLogs->get();
            // dump($getTimeLogs);
        }
        $empTotalDays = [];
        $empNumberOfDays = [];
        $overTimeWithKey = [];
        $empTotalOverTime = [];
        $empTotalCashAdvance = [];
        $cashAdvanceWithKey = [];
        foreach ($getCashAdvance as $key => $value) {
            if (count($value) > 0) {
                foreach ($value as $v2) {
                    $cashAdvanceWithKey[$v2->employee_information_id][] = $v2->amount;
                }
            }
        }
        foreach ($getTimeLogs as $key => $value) {
            if (count($value) > 0) {
                foreach ($value as $v2) {
                    if ($v2->morning_in && $v2->morning_out && $v2->afternoon_in && $v2->afternoon_out) {
                        $empNumberOfDays[$v2->employee_information_id][] = 1;
                    } elseif ($v2->morning_in && $v2->morning_out) {
                        $empNumberOfDays[$v2->employee_information_id][] = 0.5;
                    } elseif ($v2->afternoon_in && $v2->afternoon_out) {
                        $empNumberOfDays[$v2->employee_information_id][] = 0.5;
                    }
                    //OT
                    $overtimeInDateTime = new Carbon($v2->overtime_in);
                    $overtimeOutDateTime = new Carbon($v2->overtime_out);
                    if ($overtimeInDateTime && $overtimeOutDateTime) {
                        $timeDifference = $overtimeOutDateTime->diff($overtimeInDateTime);
                        $hours = $timeDifference->h;
                        $minutes = $timeDifference->i;
                        $seconds = $timeDifference->s;

                        $decTimeDifferenceIntial = ($hours * 60) + $minutes + ($seconds / 60);
                        $decTimeDifference = $decTimeDifferenceIntial / 60;
                        $overTimeWithKey[$v2->employee_information_id][] = $decTimeDifference;
                    }
                }
            }
        }
        //cash advance
        foreach ($cashAdvanceWithKey as $key => $value) {
            if (count($value) > 1) {
                $sumCA = 0;
                foreach ($value as $val) {
                    $sumCA += $val;
                }
                $empTotalCashAdvance[$key] = $sumCA;
            } else {
                $empTotalCashAdvance[$key] = $value[0];
            }
        }
        //OT
        foreach ($overTimeWithKey as $key => $value) {
            if (count($value) > 1) {
                $sumOT = 0;
                foreach ($value as $val) {
                    $sumOT += $val;
                }
                $empTotalOverTime[$key] = $sumOT;
            } else {
                $empTotalOverTime[$key] = $value[0];
            }
        }
        //timelog
        foreach ($empNumberOfDays as $key => $value) {
            if (count($value) > 1) {
                foreach ($value as $val) {
                    $empTotalDays[$key] = $val + $val;
                }
            } else {
                $empTotalDays[$key] = $value[0];
            }
        }
        //employee-payroll-management.employeeViewCashAdvances
        $pdf = PDF::loadView('employee-payroll-management.download-payslip.download-generated-payslip', [
            'getEmployee' => $getAllPayslip,
            'sites' => $sites,
            'employees' => $employees,
            'totalDays' => $empTotalDays,
            'totalOvertime' => $empTotalOverTime,
            'totalCashAdvance' => $empTotalCashAdvance,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]);
        // dd($pdf);
        // $fileName = $this->emp_name.Carbon::now().'.pdf';
        if($this->dateFrom && $this->dateTo){

            $fileName = 'Date-'.$this->dateFrom.'-'.$this->dateTo.'-'.Carbon::today()->toDateString().'.pdf';
        }else{
            $fileName = 'No-set-date'.'-'.Carbon::today()->toDateString().'.pdf';
        }
        // dd($fileName);
        // return $pdf->download($fileName);
        // return $pdf->download($fileName);
        // dd($fileName);
        // return view(
        //     'employee-payroll-management.download-payslip.download-generated-payslip',
        //     [
        //         'getEmployee' => $getAllPayslip,
        //         'sites' => $sites,
        //         'employees' => $employees,
        //         'totalDays' => $empTotalDays,
        //         'totalOvertime' => $empTotalOverTime,
        //         'totalCashAdvance' => $empTotalCashAdvance,
        //     ]
        // );
        // $pdf = PDF::loadView('employee-payroll-management.download-cash-advances.cashAdvancedDl', $data);
        // $pdf->save(public_path('test.pdf'));
        // $fileName = $empInfo->last_name.$empInfo->first_name.Carbon::now().'.pdf';
        return $pdf->download($fileName);
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
        $fileName = $empInfo->last_name.$empInfo->first_name.Carbon::now().'.pdf';
        return $pdf->download($fileName);
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
        $fileName = $empInfo->last_name . $empInfo->first_name . Carbon::now() . '.pdf';
        return $pdf->download($fileName);
    }

}
