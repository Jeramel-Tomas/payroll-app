<?php

namespace App\Http\Livewire\AttendanceLog;

use Livewire\Component;
use App\Models\WorkingSite;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use Illuminate\Support\Carbon;

class DailyLog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $searchString = "", $workingSiteName = "";
    public $workingSite;

    public function updated()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->workingSite = null;
         $this->workingSiteName = "";
    }

    public function render()
    {
        $employees = EmployeeInformation::orderby('employee_information.last_name', 'asc')->select(
            'employee_information.id',
            'employee_information.employee_uuid',
            'employee_information.first_name',
            'employee_information.last_name',
        );
    
        if (!empty($this->searchString)) {
            $employees->orWhere('first_name', 'like', "%" . $this->searchString . "%");
            $employees->orWhere('last_name', 'like', "%" . $this->searchString . "%");
        }

        if (!empty($this->workingSite)) {
            // dd($this->workingSite);
            $employees->join('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id');
            $employees->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id');
            $employees->where('working_sites.id', '=', $this->workingSite);
            // $employees->addSelect('working_sites.site_name');
            $this->workingSiteName = WorkingSite::select('site_name')->where('id', $this->workingSite)->first();
            $this->workingSiteName = $this->workingSiteName->site_name ?? '';
        }

        $employees = $employees->paginate(2);
        $sites = WorkingSite::all();
        $today = Carbon::today();
        
        return view('livewire.attendance-log.daily-log', [
            'employees' => $employees,
            'sites' => $sites,
            'today' => $today->toFormattedDateString(),
        ]);
    }
}
