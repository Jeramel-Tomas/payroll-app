<?php

namespace App\Http\Livewire\EmployeeManagement;

use App\Models\WorkingSite;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $worSiteId;
    public $workingSiteName = '', $searchString = '';
    public $siteName = "";
    public $getGender = "";
    public $getAddress = "";
    public $getJobTitle = "";
    public $workingSite, $address, $assignId, $gender, $jobTitle;
    public $assignSite = null;
    public $noResultsMessage = '';
    public $sortOption = 'site';

    protected $queryString = [
        'searchString' => ['except' => ''],
    ];
    public function clearFilter()
    {
        $this->workingSite = null;
        $this->siteName = "";
        $this->gender = null;
        $this->searchString = null;
        $this->address = null;
        $this->jobTitle = null;
        $this->sortOption = 'site';
    }
    public function updated()
    {
        $this->resetPage();
    }

    public function assignEmployee()
    {
        if ($this->assignSite) {
            $siteID = explode("::", $this->assignSite);
            $employeeID = explode("::", $this->assignSite);

            $empSite = new EmployeeWorkingSite();
            $empSite->employee_information_id = $employeeID[1];
            $empSite->working_site_id = $siteID[0];

            EmployeeWorkingSite::updateOrInsert(
                ['employee_information_id' => $employeeID[1]],
                ['working_site_id' => $siteID[0]]
            );

            $this->assignSite = null;

            session()->flash('success', 'Employee Site Added successfully!');
        }
    }

    public function render()
    {

        $employees = EmployeeInformation::all();
        $sites = WorkingSite::all();

        $getEmployee = DB::table('employee_information')
            ->select('employee_information.id AS employee_id', 'employee_information.*')
            ->orderBy('employee_information.first_name', 'asc');

        if (!empty($this->searchString)) {
            $getEmployee->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchString . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchString . '%');
            });
        }
        if (!empty($this->workingSite)) {
            $getEmployee->join('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id');
            $getEmployee->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id');
            $getEmployee->where('working_sites.id', '=', $this->workingSite);
            $this->workingSiteName = WorkingSite::select('site_name')->where('id', $this->workingSite)->first();
            $this->workingSiteName = $this->workingSiteName->site_name ?? '';
        }
        // if ($this->workingSite === "") {
        //     $this->workingSite = null;
        // }
        // if ($this->address === "") {
        //     $this->address = null;
        // }
        // if ($this->jobTitle === "") {
        //     $this->jobTitle = null;
        // }
        // if ($this->gender === "") {
        //     $this->gender = null;
        // }


        $getEmployee = $getEmployee->paginate(25);
        

        if ($getEmployee->isEmpty()) {
            $this->noResultsMessage = 'No employees found';
        } else {
            $this->noResultsMessage = '';
        }
        return view('livewire.employee-management.employee-list', [
            'getEmployees' => $getEmployee,
            'sites' => $sites,
            'workingSiteName' => $this->workingSiteName,
        ]);
    }
}
