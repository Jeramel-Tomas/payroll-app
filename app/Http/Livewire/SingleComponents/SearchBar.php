<?php

namespace App\Http\Livewire\SingleComponents;

use App\Models\WorkingSite;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;
use Livewire\Component;
use Livewire\WithPagination;

class SearchBar extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';//

    public $searchString = '';
    public $siteName="";
    public $workingSite ,  $assignId;
    public $assignSite = null;

    protected $queryString = [
        'searchString' => ['except' => ''],
        'workingSite' => ['except' => ''],
    ];

    public function updated()
    {
        $this->resetPage();
    }

    public function assignEmployee()
    {
        // dd($this->assignSite );
        // $this->assignSite ? dd($this->assignSite ) : null;
        if ($this->assignSite) {
            $siteID = explode("::", $this->assignSite);
            $employeeID = explode("::", $this->assignSite);

            $empSite = new EmployeeWorkingSite();
            $empSite->employee_information_id = $employeeID[1];
            $empSite->working_site_id = $siteID[0];
            $empSite->save();

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

        if (!empty($this->workingSite)) {
            // dd($this->workingSite);
            $getEmployee->when($this->workingSite, function ($query) {
                $query->where('working_sites.id', $this->workingSite);
            });
        // dd($this->workingSite);
            $this->siteName = WorkingSite::select('site_name')->where('id', $this->workingSite)->first();
        
            $this->siteName = $this->siteName->site_name ?? '';

        }
        
        if ($this->workingSite === "") {//
            $this->workingSite = null;
        }

        $getEmployee->orderBy('employee_information.last_name');
        $getEmployee = $getEmployee->paginate(4);
        // dump($getEmployee);
        // $getEmployee->currentPage();
        return view('livewire.single-components.search-bar', [
            'getEmployee' => $getEmployee,
            'sites' => $sites,
        ]);
        
    }
    
}
