<?php

namespace App\Http\Livewire\EmployeeAttendanceManagement;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\WorkingSite;
use Livewire\WithPagination;
use App\Models\EmployeeInformation;

class EmployeeAttendance extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $workingSiteName = '', 
        $searchString = '',
        $workingSiteFilter = '',
        $monthFilter = '',
        $filterFrom = '',
        $filterTo = '';

    public function clearFilter()
    {
        $this->reset();
    }

    public function showInputEmployeeAttendance($empId)
    {
        $this->emit('inputEmployeeDtr', $empId);
    }

    public function render()
    {
        $employees = EmployeeInformation::orderby('employee_information.last_name', 'asc')
            ->select(
                'employee_information.id',
                'employee_information.employee_uuid',
                'employee_information.first_name',
                'employee_information.last_name',
            );

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

        if (!empty($this->monthFilter)) {
            $this->filterFrom = Carbon::create($this->monthFilter)->startOfMonth();
            $this->filterTo = Carbon::create($this->monthFilter)->endOfMonth();
        }

        $employees = $employees->paginate(25);
        $sites = WorkingSite::all();

        return view('livewire.employee-attendance-management.employee-attendance',[
            'employees' => $employees,
            'sites' => $sites,
        ]);
    }
}
