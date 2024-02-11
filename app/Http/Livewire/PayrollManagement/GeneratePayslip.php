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
    public $monthFilter = '',
        $filterFrom = '',
        $filterTo = '';

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
        $this->filterFrom = '';
        $this->filterTo = '';
        $this->monthFilter = '';
    }
    
    public function render()
    {
        $employees = EmployeeInformation::all();
        $sites = WorkingSite::all();

        $getEmployeePayslip = DB::table('employee_information')
            ->select('employee_information.id AS employee_id', 'employee_information.*')
            ->orderBy('employee_information.first_name', 'asc');
            // ->paginate(25);

        if ($this->monthFilter) {
            $this->filterFrom = Carbon::create($this->monthFilter)->startOfMonth();
            $this->filterTo = Carbon::create($this->monthFilter)->endOfMonth();
        }
        if (!empty($this->workingSite)) {
            $getEmployeePayslip->join('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id');
            $getEmployeePayslip->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id');
            $getEmployeePayslip->where('working_sites.id', '=', $this->workingSite);
            $this->workingSiteName = WorkingSite::select('site_name')->where('id', $this->workingSite)->first();
            $this->workingSiteName = $this->workingSiteName->site_name ?? '';
        }



        $getEmployeePayslip = $getEmployeePayslip->paginate(25);
        return view(
            'livewire.payroll-management.generate-payslip',
            [
                'getEmployee' => $getEmployeePayslip,
                'sites' => $sites,
                'employees' => $employees,
                // 'totalDays' => $empTotalDays,
                // 'totalOvertime' => $empTotalOverTime,
                // 'totalCashAdvance' => $empTotalCashAdvance,
            ]
        );
    }
}
