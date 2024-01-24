<?php

namespace App\Http\Livewire\WorkingSitesManagement;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\WorkingSite;
use Illuminate\Support\Arr;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;

class WorkingSitesIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected  $listeners = ['refreshComponent' => '$refresh'];

    public $searchString = '';
    public $siteNameValue = '',
        $siteId = '',
        $siteName = '',
        $employeeCount = 0;
    public $searchQueryString;
    public $employees = [];
    public $selectedEmps = [], 
        $selectedEmpsSameWorkingSite = [],
        $existingEmployeesInWorkingSite = [];
    public $siteIdModalToAddEmp = '',
        $siteNameModalToAddEmp = '';

    public function resetProps()
    {
        $this->reset();
    }

    /* public function updating()
    {
        $existingEmp = $this->getExistingEmployeeInThisSite();
        if ($existingEmp) {
            $this->existingEmployeesInWorkingSite = $existingEmp;
        }
    } */

    public function removeFromSelectedEmp($keyId)
    {
        if ($keyId) {
            $filtered = Arr::where($this->selectedEmps, function ($value, $key) use ($keyId) {
                if ($key !== $keyId) {
                    return [$key => $value];
                }
            });
            $this->selectedEmps = $filtered;
            // $this->updated();
            if ($this->existingEmployeesInWorkingSite) {
                unset($this->existingEmployeesInWorkingSite[$keyId]);
            }
        }
    }

    public function saveEmployeesToSite($siteId)
    {
       
        /* foreach ($this->selectedEmps as $key => $value) {
            if ($this->checkEmpSameWorkingSite($key, $siteId)) {
                $this->selectedEmpsSameWorkingSite[$key] = $value;
            }
        } */
        $success = false;
        if (count($this->selectedEmpsSameWorkingSite) > 0) {
            $notExistingEmpInThisSite = array_diff($this->selectedEmps, $this->selectedEmpsSameWorkingSite);
            // $existingEmp = array_intersect_key($this->selectedEmpsSameWorkingSite, $this->selectedEmps);
            
            if (count($notExistingEmpInThisSite) > 0) {
                foreach ($notExistingEmpInThisSite as $key => $value) {
                    $data[] = [
                        'employee_information_id' => $key,
                        'working_site_id' => $siteId,
                        'created_at' => Carbon::now()
                    ];
                }
                // dump('data-to-insert', $data); die;
                $success = DB::table('employee_working_sites')->insert($data);
            }
            // $this->existingEmployeesInWorkingSite = $existingEmp ?? null;
            // dump($this->existingEmployeesInWorkingSite);
        } else {
            foreach ($this->selectedEmps as $key => $value) {
                $data[] = [
                    'employee_information_id' => $key,
                    'working_site_id' => $siteId,
                    'created_at' => Carbon::now()
                ];
            }
            $success = DB::table('employee_working_sites')->insert($data);
        }

        if ($success) {
            session()->flash('message', 'Working site successfully updated.');
            $this->reset(
                'selectedEmps',
                'selectedEmpsSameWorkingSite',
                'searchQueryString',
                'existingEmployeesInWorkingSite',
            );
            return $success;
        }

        return $success;
    }

    public function addEmployeeToSiteModal($siteId, $siteName)
    {
        $this->siteIdModalToAddEmp = $siteId;
        $this->siteNameModalToAddEmp = $siteName;
    }

    private function checkEmpSameWorkingSite($empId, $siteId)
    {
        if ($empId && $siteId) {
            $findEmpSite = EmployeeWorkingSite::where('employee_information_id', $empId)
                ->where('working_site_id', $siteId)->get();
            return $findEmpSite->count() > 0 ? true : false;
        }
    }

    public function selectEmployee($employeeId)
    {
    //    dump($employeeId);
        $getEmp = EmployeeInformation::where('id', $employeeId)->first();
        // $this->searchQueryString = $getEmp->first_name . ' ' . $getEmp->last_name;
        $array = [
            $getEmp->id => $getEmp->first_name . ' ' . $getEmp->last_name
        ];
        // dump($getEmp);
        $this->selectedEmps = $this->selectedEmps + $array;

        foreach ($this->selectedEmps as $key => $value) {
            if ($this->checkEmpSameWorkingSite($key, $this->siteIdModalToAddEmp)) {
                $this->selectedEmpsSameWorkingSite[$key] = $value;
            }
        }
        $this->getExistingEmployeeInThisSite();
       /*  $existingEmp = $this->getExistingEmployeeInThisSite();
        if ($existingEmp) {
            $this->existingEmployeesInWorkingSite = $existingEmp;
        } */
        /* $this->reset([
            'searchQueryString',
            'employees'
        ]); */
    }

    private function getExistingEmployeeInThisSite()
    {
         $this->existingEmployeesInWorkingSite = array_intersect_key($this->selectedEmpsSameWorkingSite, $this->selectedEmps);
    }

    public function confirmSiteDelete($siteId, $name)
    {
        $this->siteId = $siteId;
        $this->siteName = $name;

        $findEmp = EmployeeWorkingSite::where('working_site_id', $siteId)->get();
        // dump($findEmp);
        $this->employeeCount = $findEmp->count();
    }

    public function deleteSite($siteId)
    {
        $ws = WorkingSite::find($siteId);
        $ws->delete();

        $this->emit('deleted', ['message' => 'Working site is successfully deleted!']);
        $this->dispatchBrowserEvent('deleted');
    }

    public function saveSiteName()
    {
        WorkingSite::create([
            'site_name' => $this->siteNameValue,
            'created_at' => Carbon::now()
        ]);

        $this->emit('created', ['message' => 'Working site is successfully created!']);
        $this->dispatchBrowserEvent('created');
    }

    public function render()
    {
        
        // dump($this->siteIdModalToAddEmp);
        if ($this->searchQueryString) {
            $empInfo = EmployeeInformation::where('first_name', 'like', '%' . $this->searchQueryString . '%')
                ->orWhere('last_name', 'like', '%' . $this->searchQueryString . '%')
                ->get();    
            $this->employees = $empInfo;
            // $this->employees = $empInfo->count() > 0 ? $empInfo : '';
            // ->toArray();
            // dump($this->employees);
            
        }

        $workingSites = WorkingSite::orderby('working_sites.site_name', 'asc')
        ->select(
            'working_sites.id',
            'working_sites.site_name',
        );

        $workingSites->addSelect([
            'emp_count' => EmployeeWorkingSite::selectRaw('COUNT(employee_information_id)')
            ->whereColumn('working_site_id', 'working_sites.id')
            ->groupBy('working_site_id')
        ]);

        if (!empty($this->searchString)) {
            $workingSites->orWhere('working_sites.site_name', 'like', $this->searchString . "%");
        }

        $sites = $workingSites->paginate(25);
        // dump($workingSites->get());

        return view('livewire.working-sites-management.working-sites-index', [
            'sites' => $sites,
        ]);
    }
}
