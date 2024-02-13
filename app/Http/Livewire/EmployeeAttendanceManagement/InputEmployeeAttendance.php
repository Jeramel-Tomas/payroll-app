<?php

namespace App\Http\Livewire\EmployeeAttendanceManagement;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeTimeLog;
use App\Models\EmployeeTimeRecords;

class InputEmployeeAttendance extends Component
{
    protected $listeners = [
        'inputEmployeeDtr',
    ];

    public $employees;
    public $employeeId;
    public $siteId = '', $daysPresentColumn = '';
    public $daysPresentColumnConstant = 'daysPresent';
    public $totalOtColumnConstant = 'totalOt',
        $attendanceFromColumnConstant = 'attendanceFrom',
        $attendanceToColumnConstant = 'attendanceTo';
    public $totalOtColumn = '', 
        $attendanceFromColumn = '',
        $attendanceToColumn = '';

    public $monthFilterInputAttendance = '',
        $filterFromInputAttendance = '',
        $filterToInputAttendance = '';

    public $empDaysPresentInput,
        $otInput,
        $dateFromModalInput,
        $dateToModalInput;

    protected $rules = [
        'empDaysPresentInput' => 'required',
        'dateFromModalInput' => 'required',
        'dateToModalInput' => 'required',
    ];
    
    public function clearFilterInputAttendance()
    {
        $this->monthFilterInputAttendance = '';
        $this->filterFromInputAttendance = '';
        $this->filterToInputAttendance = '';
    }

    /**
     * method listener from parent EmployeeAttendance
     */
    public function inputEmployeeDtr($empId)
    {
        $this->employeeId = $empId;
    }

    public function setupInputAttendance($siteId, $empDaysPresent, $ot, $dateFromModal, $dateToModal)
    {        
        $this->siteId = $siteId;
        if ($empDaysPresent) {
            $this->empDaysPresentInput = $empDaysPresent;
        }

        if ($ot) {
            $this->otInput = $ot;
        }

        if ($dateFromModal) {
            $this->dateFromModalInput = $dateFromModal;
        }

        if ($dateToModal) {
            $this->dateToModalInput = $dateToModal;
        }
    }

    public function saveInputAttendance($siteId)
    {
        $this->validate();

        DB::table('employee_time_records')
        ->updateOrInsert(
            ['employee_id' => $this->employeeId, 'site_id' =>  $siteId],
            [
                'days_present' => $this->empDaysPresentInput,
                'total_ot' => $this->otInput ?? null,
                'attendance_from' => $this->dateFromModalInput,
                'attendance_to' => $this->dateToModalInput,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
       
        $this->cancelInputAttendance();
        session()->flash('message', 'Updated successfully.');
    }

    public function cancelInputAttendance()
    {
        $this->reset([
            'siteId',
            'empDaysPresentInput',
            'otInput',
            'dateFromModalInput',
            'dateToModalInput',
        ]);
    }

    public function cancelInput()
    {
        $this->siteId = '';
        $this->daysPresentColumn = '';
        $this->totalOtColumn = '';
        $this->attendanceFromColumn = '';
        $this->attendanceToColumn = '';
    }

    public function render()
    {
        $employees = EmployeeInformation::orderby('employee_information.last_name', 'asc')
        ->select(
            'employee_information.id',
            'employee_information.first_name',
            'employee_information.last_name',
            'employee_working_sites.job_title',
            'employee_working_sites.job_title_rate',
            'working_sites.site_name',
            'employee_working_sites.working_site_id'
        );

        $employees->join('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id');
        $employees->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id');
        
        $employees->where('employee_information.id', $this->employeeId);
        $employees = $employees->get();
       
        if ($this->monthFilterInputAttendance) {
            $this->filterFromInputAttendance = Carbon::create($this->monthFilterInputAttendance)->startOfMonth();
            $this->filterToInputAttendance = Carbon::create($this->monthFilterInputAttendance)->endOfMonth();
        }

        return view('livewire.employee-attendance-management.input-employee-attendance', [
            'employeesInfo' => $employees
        ]);
    }

}
