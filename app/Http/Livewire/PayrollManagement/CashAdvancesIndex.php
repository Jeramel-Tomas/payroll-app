<?php

namespace App\Http\Livewire\PayrollManagement;

use Livewire\Component;
use App\Models\WorkingSite;
use App\Models\EmployeeInformation;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeCashAdvance;
use Carbon\Carbon;

class CashAdvancesIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    /* protected $rules = [
        'cashAdvanceAmount' => 'required',
        'cashAdvancedDate' => 'required',
    ]; */

    public $workingSite;
    public $workingSiteName = '', $searchString = '';
    public $fullName = '', $empIdForModal = '';
    public $cashAdvanceAmount = '', 
        $cashAdvancedDate ='', 
        $cashAdvancedPurpose ='';

    public function cancelCreate()
    {
        $this->reset();
    }

    public function clearFilter()
    {
        $this->reset();
    }

    public function createCashAdvance($empId, $fname, $lname)
    {
        $this->fullName = $fname . ' ' . $lname;
        $this->empIdForModal = $empId;
        /* dump($empId);
        dump($fname);
        dump($lname); */
    }

    public function saveCashAdvances()
    {
        /* dump($this->cashAdvanceAmount * 1);
        dump($this->cashAdvancedDate);
        dump($this->cashAdvancedPurpose);
        dump($this->empIdForModal); die; */
       /*  if (empty($this->cashAdvanceAmount) && empty($this->cashAdvancedDate)) {
        } */
        // $validate = $this->validate();
        
        // dump($validate); die;

        $amount = $this->cashAdvanceAmount * 1;
        DB::table('employee_cash_advances')->insert([
            'employee_information_id' => $this->empIdForModal,
            'amount' => $amount,
            'purpose' => $this->cashAdvancedPurpose,
            'cash_advanced_date' => $this->cashAdvancedDate,
            'created_at' => Carbon::now()
        ]);

        $this->reset();
    }

    public function render()
    {
        $employees = EmployeeInformation::orderby('employee_information.last_name', 'asc')
        ->select(
            'employee_information.id',
            'employee_information.first_name',
            'employee_information.last_name',
        );

        $employees->addSelect([
            'total_amount' => EmployeeCashAdvance::selectRaw('SUM(amount)')
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

        return view('livewire.payroll-management.cash-advances-index', [
            'employees' => $employees,
            'sites' => $sites,
        ]);
    }
}
