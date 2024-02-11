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
    public $workingSiteName = '', 
        $searchString = '',
        $workingSiteFilter = '';
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
        // $employees = EmployeeInformation::all();
        $sites = WorkingSite::all();

        $employees = DB::table('employee_information')
            ->select('employee_information.id AS employee_id', 'employee_information.*');

        if ($this->monthFilter) {
            $this->filterFrom = Carbon::create($this->monthFilter)->startOfMonth();
            $this->filterTo = Carbon::create($this->monthFilter)->endOfMonth();
        }

        if (!empty($this->searchString)) {
            $employees->orWhere('first_name', 'like', "%" . $this->searchString . "%");
            $employees->orWhere('last_name', 'like', "%" . $this->searchString . "%");
        }

        if (!empty($this->workingSiteFilter)) {
            $employees->join('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id');
            $employees->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id');
            $employees->where('working_sites.id', '=', $this->workingSiteFilter);
            $this->workingSiteName = WorkingSite::select('site_name')->where('id', $this->workingSiteFilter)->first();
            $this->workingSiteName = $this->workingSiteName->site_name ?? '';
        }

        $getEmployeePayslip = $employees->paginate(25);


        // $getEmployeePayslip = $getEmployeePayslip->paginate(25);
        return view(
            'livewire.payroll-management.generate-payslip',
            [
                'getEmployee' => $getEmployeePayslip,
                'sites' => $sites,
            ]
        );
    }
}
