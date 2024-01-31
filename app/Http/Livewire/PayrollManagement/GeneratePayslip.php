<?php

namespace App\Http\Livewire\PayrollManagement;
// use Carbon\Carbon;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\WorkingSite;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GeneratePayslip extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $workingSite;
    public $workingSiteName = '', $searchString = '';
    public $dateFrom = '',
        $dateTo = '';
    public $dateFromPayslip = '',
        $dateToPayslip = '';
    public $days = 0,
        $dailyRate = 0,
        $grossTotal = 0;
    public function updated()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->workingSite = null;
        $this->workingSiteName = "";
        $this->dateFrom = "";
        $this->dateTo = "";


    }
    
    public function render()
    {
        $employees = EmployeeInformation::all();
        $sites = WorkingSite::all();

        $getEmployeePayslip = DB::table('employee_information')
            ->leftJoin('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id')
            ->leftJoin('working_sites', 'working_sites.id', '=', 'employee_working_sites.working_site_id')
            ->select('employee_information.id AS employee_id', 'employee_information.*', 'employee_working_sites.*', 'working_sites.*')
            ->whereNull('employee_working_sites.employee_information_id')
            ->orWhereNotNull('employee_working_sites.employee_information_id')
            ->paginate(5);
            // dd($getEmployeePayslip);
        //cash advance
        foreach ($getEmployeePayslip as $value) {
            $empCashAdvance = DB::table('employee_cash_advances')
                ->where('employee_information_id', $value->employee_id)
                ->where('cash_advanced_date', '<=', Carbon::now()->endOfMonth()->toDateString());
            if ($this->dateFrom && $this->dateTo) {
                $empCashAdvance->where('cash_advanced_date', '>=', $this->dateFrom);
                $empCashAdvance->where('cash_advanced_date', '<=', $this->dateTo);
            }
            $getCashAdvance[] = $empCashAdvance->get();
        }
        //timelog
        foreach ($getEmployeePayslip as $value) {
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
        }
        // dump($getTimeLogs);
        // dump($getTimeLogs);
        // 2024-1-12 (YY-MM-DD)
        // 2024-1-31
        // dump(Carbon::now()->endOfMonth()->toDateString());

        $empTotalDays = [];
        $empNumberOfDays = [];
        $overTimeWithKey = [];
        $empTotalOverTime = [];
        $empTotalCashAdvance = [];
        // $empCashAdvance = [];
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


        return view(
            'livewire.payroll-management.generate-payslip',
            [
                'getEmployee' => $getEmployeePayslip,
                'sites' => $sites,
                'employees' => $employees,
                'totalDays' => $empTotalDays,
                'totalOvertime' => $empTotalOverTime,
                'totalCashAdvance' => $empTotalCashAdvance,
            ]
        );
    }
}
