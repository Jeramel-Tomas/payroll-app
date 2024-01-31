<?php

namespace App\Http\Livewire\WorkingSitesManagement;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class WorkingSitesEmployees extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $searchString = '';
    public $siteId;
    public $jobTitleColumnConstant = 'jobTitle',
        $jobTitleRateColumnConstant = 'jobTitleRate';
    public $employeeId = '', 
        $siteIdEdit = '', 
        $jobTitleColumn = '',
        $jobTitleRateColumn = '';
    public $empFullName = '';

    public function confirmRemoveEmpoyeeFromSite($empId, $siteId)
    {

    }

    public function editJobTitleBySite($empId, $siteId, $columnName)
    {
        $this->employeeId = $empId;
        $this->siteIdEdit = $siteId;
        $this->jobTitleColumn = $columnName;
    }

    public function saveJobTitle($value)
    {
        // save into employee_working_sites
        DB::table('employee_working_sites')
            ->where('employee_information_id', $this->employeeId)
            ->where('working_site_id', $this->siteIdEdit)
            ->update([
                'emp_job_title' => $value,
                'updated_at' => Carbon::now()
            ]);

        $this->cancelEditing();
    }

    public function editJobTitleRateBySite($empId, $siteId, $columnName)
    {
        $this->employeeId = $empId;
        $this->siteIdEdit = $siteId;
        $this->jobTitleRateColumn = $columnName;
    }

    public function saveJobTitleRate($value)
    {
        DB::table('employee_working_sites')
        ->where('employee_information_id', $this->employeeId)
            ->where('working_site_id', $this->siteIdEdit)
            ->update([
                'job_title_rate' => $value,
                'updated_at' => Carbon::now()
            ]);

        $this->cancelEditing();
    }

    public function cancelEditing()
    {
        $this->reset([
            'jobTitleColumn',
            'employeeId',
            'siteIdEdit',
            'jobTitleRateColumnConstant',
            'jobTitleRateColumn',
        ]);
    }

    public function deleteEmployeeFromSite()
    {
        // delete employee from employee_working_sites
        DB::table('employee_working_sites')
            ->where('employee_information_id', $this->employeeId)
            ->where('working_site_id', $this->siteId)
            ->delete();
        $this->cancelDeletion();
    }

    public function confirmDeletion($empId, $siteId, $empName)
    {
        // confirm deletion
        $this->employeeId = $empId;
        $this->siteIdEdit = $siteId;
        $this->empFullName = $empName;
    }

    public function cancelDeletion()
    {
        // reset props
        $this->reset([
            'employeeId',
            'siteIdEdit',
            'empFullName',
        ]);
    }

    public function render()
    {
        $employeesInWorkingSite = DB::table('employee_information')
            ->select(
                'employee_information.last_name as lastname',
                'employee_information.first_name as firstname',
                'employee_working_sites.employee_information_id',
                'employee_working_sites.working_site_id',
                'working_sites.site_name',
                'employee_working_sites.job_title',
                'employee_working_sites.job_title_rate'
            )
            ->orderBy('employee_information.last_name');
        $employeesInWorkingSite->where('working_sites.id', '=', $this->siteId);
        $employeesInWorkingSite->join(
            'employee_working_sites',
            'employee_information.id',
            '=',
            'employee_working_sites.employee_information_id'
        );
        $employeesInWorkingSite->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id');
        $siteEmployees = $employeesInWorkingSite->paginate(50);
        

        return view('livewire.working-sites-management.working-sites-employees', ['siteEmployees' => $siteEmployees]);
    }

}
