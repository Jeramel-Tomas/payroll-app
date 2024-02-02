<?php

namespace App\Http\Livewire\PayrollManagement;

use Livewire\Component;
use App\Models\WorkingSite;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeJobInformation;

class EmployeePayrollInfo extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public $workingSite;
    public $workingSiteName = '', $searchString = '';
    public $employeeIdSetToShowEditInput = '', $columnToEdit = '';
    public $jobTitle = 'jobTitle', 
        $dailyRate = 'dailyRate',
        $dailyTimeScheduleAM = 'dailyTimeScheduleAm',
        $dailyTimeSchedulePM = 'dailyTimeSchedulePm',
        $employmentStatus = 'employmentStatus',
        $payDay = 'payday';
    public $jobTitleValue = '', 
        $dailyRateValue = '',
        $dailyTimeScheduleAMValue = '',
        $dailyTimeSchedulePMValue = '',
        $employmentSatusValue = '',
        $payDayValue = '';
    public $fullName = '', $empIdForModal  = '';
    public $selectedDayoff = [];

    public function clearFilter()
    {
        $this->workingSite = null;
        $this->workingSiteName = "";
    }

    public function cancelEdit()
    {
        $this->reset();
        // dump($this->employeeIdSetToShowEditInput);
    }

    /* public function change()
    {
        dump($this->payDayValue);
    } */

    public function cellToEdit($employeeId, $inputValue, $column)
    {
        // dump($employeeId); die;
        $this->employeeIdSetToShowEditInput = $employeeId;
        // if ($column === $this->jobTitle) {
            $this->columnToEdit = $column;
        // }
        $this->jobTitleValue = $column === $this->jobTitle ? $inputValue : '';
        $this->dailyRateValue = $column === $this->dailyRate ? $inputValue : '';
        $this->dailyTimeScheduleAMValue = $column === $this->dailyTimeScheduleAM ? $inputValue : '';
        $this->dailyTimeSchedulePMValue = $column === $this->dailyTimeSchedulePM ? $inputValue : '';
        $this->employmentSatusValue = $column === $this->employmentStatus ? $inputValue : '';
        $this->payDayValue = $column === $this->payDay ? $inputValue : '';
    }
    
    public function saveCellToEdit($employeeId, $valueToSave, $column)
    {
        $columnInSnake = Str::snake($column, '_');
        // 11 max characters for value to save
        $employeeInfo = ['job_title', 'daily_rate'];
        $employeeJobInfo = ['daily_time_schedule_am', 'daily_time_schedule_pm', 'employment_status', 'payday'];
        // save to emplyee_information job_title and daily_rate
        if (in_array($columnInSnake, $employeeInfo)) {
            // update employee_information table
            // dump($employeeId . ' value = ' . $valueToSave);
            DB::table('employee_information')
                ->updateOrInsert(
                    ['id' => $employeeId],
                    [$columnInSnake => $valueToSave]
                );
        }

        if (in_array($columnInSnake, $employeeJobInfo)) {
            // udpdate employee_job_information
            DB::table('employee_job_information')
                ->updateOrInsert(
                    ['employee_information_id' => $employeeId],
                    [$columnInSnake => $valueToSave]
                );
        }
        $this->reset();
        // dump(Str::snake($columnToSave, '_'));
        // save to employee_job_information daily_time_schedule_am, daily_time_schedule_pm, and employment_status
    }

    public function setDataToModal($employeeId, $firstName, $lastName)
    {
        $this->fullName = $firstName . ' ' . $lastName;
        $this->empIdForModal = $employeeId;
    }

    public function saveDayOff()
    {
        // dump('....saving');
        // dump($this->selectedDayoff);
        // dump($dayOffs);
        if (count($this->selectedDayoff) === 0) {
            $this->emit('warning1', 'warning');
            $this->dispatchBrowserEvent('warning1');
            $this->reset();
        }
        $dayOffs = implode('-', $this->selectedDayoff);
        // save or update the employee_job_information table
        DB::table('employee_job_information')
            ->updateOrInsert(
                ['employee_information_id' => $this->empIdForModal],
                ['day_off' => $dayOffs]
            );
    }

    public function render()
    {
        $employees = EmployeeInformation::orderby('employee_information.last_name', 'asc')
        ->select(
            'employee_information.id',
            'employee_information.employee_uuid',
            'employee_information.first_name',
            'employee_information.last_name',
            'job_title',
            'daily_rate',
        );

        $employees->addSelect([
            'employment_status' => EmployeeJobInformation::select('employment_status')
            ->whereColumn('employee_information_id', 'employee_information.id')
        ]);
        $employees->addSelect([
            'daily_time_schedule_am' => EmployeeJobInformation::select('daily_time_schedule_am')
            ->whereColumn('employee_information_id', 'employee_information.id')
        ]);
        $employees->addSelect([
            'daily_time_schedule_pm' => EmployeeJobInformation::select('daily_time_schedule_pm')
            ->whereColumn('employee_information_id', 'employee_information.id')
        ]);
        $employees->addSelect([
            'payday' => EmployeeJobInformation::select('payday')
                ->whereColumn('employee_information_id', 'employee_information.id')
        ]);
        $employees->addSelect([
            'day_off' => EmployeeJobInformation::select('day_off')
                ->whereColumn('employee_information_id', 'employee_information.id')
        ]);

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

        $employees = $employees->paginate(25);
        // $employees = $employees->toSql();
        $sites = WorkingSite::all();

        return view('livewire.payroll-management.employee-payroll-info', [
            'employees' => $employees,
            'sites' => $sites,
        ]);
    }
}
