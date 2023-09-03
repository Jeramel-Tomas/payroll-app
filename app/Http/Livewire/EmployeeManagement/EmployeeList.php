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
    public $searchString = '';
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
        $this->assignEmployee();
        $sites = WorkingSite::all();

        $getEmployee = DB::table('employee_information')
            ->leftJoin('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id')
            ->leftJoin('working_sites', 'working_sites.id', '=', 'employee_working_sites.working_site_id')
            ->select('employee_information.id AS employee_id', 'employee_information.*', 'employee_working_sites.*', 'working_sites.*');

        if (!empty($this->searchString)) {
            $getEmployee->when($this->searchString, function ($query) {
                $query->where(function ($query) {
                    $query->where('first_name', 'like', '%' . $this->searchString . '%')
                        ->orWhere('last_name', 'like', '%' . $this->searchString . '%');
                });
            });
        }
        //filter by work site
        if (!empty($this->workingSite)) {

            $getEmployee->when($this->workingSite, function ($query) {
                $query->where('working_sites.id', $this->workingSite);
            });

            $this->siteName = WorkingSite::select('site_name')->where('id', $this->workingSite)->first();

            $this->siteName = $this->siteName->site_name ?? '';
        }
        // filter by gender
        if (!empty($this->gender)) {

            $getEmployee->when($this->gender, function ($query) {
                $query->where('employee_information.gender', $this->gender);
            });
            $this->getGender = EmployeeInformation::select('gender')
                ->where('gender', strtolower($this->gender))
                ->first();

            $this->getGender = $this->gender->gender ?? '';
        }
        //filter by address
        if (!empty($this->address)) {
            $getEmployee->when($this->address, function ($query) {
                $query->where('employee_information.address', $this->address);
            });
            $this->getAddress = EmployeeInformation::select('address')
                ->where('address', strtolower($this->address))
                ->first();


            $this->getAddress = $this->address->address ?? '';
        }
        //filter by job title
        if (!empty($this->jobTitle)) {
            $getEmployee->when($this->jobTitle, function ($query) {
                $query->where('employee_information.job_title', $this->jobTitle);
            });
            $this->getJobTitle = EmployeeInformation::select('job_title')
                ->where('job_title', strtolower($this->jobTitle))
                ->first();

            $this->getJobTitle = $this->jobTitle->jobTitle ?? '';
        }

        if ($this->workingSite === "") {
            $this->workingSite = null;
        }
        if ($this->address === "") {
            $this->address = null;
        }
        if ($this->jobTitle === "") {
            $this->jobTitle = null;
        }
        if ($this->gender === "") {
            $this->gender = null;
        }


        $getEmployee->orderBy('employee_information.last_name', 'asc');
        $uniqueAddresses = EmployeeInformation::distinct()->pluck('address');
        $uniqueJob = EmployeeInformation::distinct()->pluck('job_title');
        $getEmployee = $getEmployee->paginate(4);

        if ($getEmployee->isEmpty()) {
            $this->noResultsMessage = 'No employees found';
        } else {
            $this->noResultsMessage = '';
        }
        return view('livewire.employee-management.employee-list', [
            'getEmployees' => $getEmployee,
            'sites' => $sites,
            'uniqueAddresses' => $uniqueAddresses,
            'uniqueJob' =>  $uniqueJob,
        ]);
    }
}
