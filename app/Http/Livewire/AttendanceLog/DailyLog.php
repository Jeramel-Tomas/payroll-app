<?php

namespace App\Http\Livewire\AttendanceLog;

use Livewire\Component;
use App\Models\WorkingSite;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\UsersImport;
// use App\Models\Employee;
use App\Models\EmployeeTimeLog;
// use Illuminate\Database\Query\JoinClause;
use Livewire\WithFileUploads;
// use Illuminate\Support\Facades\Request;

class DailyLog extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $searchString = "", $workingSiteName = "";
    public $workingSite, $fileExcel; 
    public $editingEmployeeId = "";
    public $morningIn = "", $morningOut = "", 
        $afternoonIn = "", $afternoonOut = "",
        $overtimeIn = "", $overtimeOut = "";
    public $filterDate = "";
    public $fullName = "";
    public $empInformationId = "";
    public $showExport = false;

    /* protected $rules = [
        'morningIn' => 'regex:/^(0?[1-9]|1[0-2]):[0-5][0-9]$/|min:3'
    ]; */
    protected $messages = [
        'morningIn.regex' => 'Invalid input. Follow this format hh:mm.',
        'morningOut.regex' => 'Invalid input. Follow this format hh:mm.',
        'afternoonIn.regex' => 'Invalid input. Follow this format hh:mm.',
        'afternoonOut.regex' => 'Invalid input. Follow this format hh:mm.',
        'overtimeIn.regex' => 'Invalid input. Follow this format hh:mm.',
        'overtimeOut.regex' => 'Invalid input. Follow this format hh:mm.',
    ];

    protected $listeners = [
        'clearExportModalComponents' => 'clearExportModalComponents',
    ];

    public function updated()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->workingSite = null;
        $this->workingSiteName = "";
        $this->filterDate = "";
    }

    public function clearExportModalComponents()
    {
        $this->emit('clearChildComponentProperties');
    }

    public function importDailyLog()
    {
        if ($this->fileExcel) {
            dd($this->fileExcel);
            // $importedFile = Excel::toArray([], $this->fileExcel);
            // dump(Excel::raw($this->fileExcel));
            // dd($importedFile);
        }
    }

    public function inlineEditEmployeeLog($empid, $amin, $amout, $pmin, $pmout, $otin, $otout)
    {
        // set edit inline flag
        $this->editingEmployeeId = $empid;
        $this->morningIn = $amin ? Carbon::parse($amin)->format('h:i') : '';
        $this->morningOut = $amout ? Carbon::parse($amout)->format('h:i') : '';
        $this->afternoonIn = $pmin ? Carbon::parse($pmin)->format('h:i') : '';
        $this->afternoonOut = $pmout ? Carbon::parse($pmout)->format('h:i') : '';
        $this->overtimeIn = $otin ? Carbon::parse($otin)->format('h:i') : '';
        $this->overtimeOut = $otout ? Carbon::parse($otout)->format('h:i') : '';
        // $this->morningIn = $amin;
       /*  $this->emit('testing', 'success');
        $this->dispatchBrowserEvent('testing', ['success' => 'Your message has been sent successfully!']); */
    }

    public function validateTimeLogs()
    {
        // dd(json_decode($this->morningIn));
        if(empty($this->morningIn) && empty($this->afternoonIn) && empty($this->overtimeIn)) {
            $this->emit('warning', 'warning');
            $this->dispatchBrowserEvent('warning');

            return [];
        }
        
        if (!empty($this->morningIn)) {
            // dump($this->morningIn);
            $validateMorningInput = $this->validate([
                'morningIn' => ['regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
                'morningOut' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/']
            ]);
        }

        if (!empty($this->afternoonIn)) {
            // dump($this->morningIn);
            $validateAfternoonInput = $this->validate([
                'afternoonIn' => ['regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
                'afternoonOut' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/']
            ]);
        }

        if (!empty($this->overtimeIn) && (!empty($this->morningIn) && !empty($this->afternoonIn))) {
            // dump($this->morningIn);
            $validateOtInput = $this->validate([
                'overtimeIn' => ['regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
                'overtimeOut' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/']
            ]);
        }

        return [
            'amTimeLog' => $validateMorningInput ?? null,
            'pmTimeLog' => $validateAfternoonInput ?? null,
            'otTimeLog' => $validateOtInput ?? null
        ];
    }

    public function saveTimeLogs()
    {
        // dd($this->validateTimeLogs());
        if (empty($this->editingEmployeeId)) {
            return;
        }

        if (empty($this->validateTimeLogs())) {
            return;
        }

        $timeLogs = array_filter($this->validateTimeLogs(), function($values) {
            return $values;
        });

        $dateToSaveUpdate = !empty($this->filterDate) ? $this->filterDate : Carbon::parse(Carbon::now())->format('Y-m-d');
        // dump($timeLogs);
        DB::table('employee_time_logs')
        ->updateOrInsert(
            [
                'employee_information_id' => $this->editingEmployeeId, 
                'attendance_date' => $dateToSaveUpdate
            ],
            [
                'morning_in' => isset($timeLogs['amTimeLog']) && !empty($timeLogs['amTimeLog'])
                    ? $timeLogs['amTimeLog']['morningIn'] 
                    : null,
                'morning_out' => isset($timeLogs['amTimeLog']) && !empty($timeLogs['amTimeLog'])
                    ? $timeLogs['amTimeLog']['morningOut']
                    : null,
                'afternoon_in' => isset($timeLogs['pmTimeLog']) && !empty($timeLogs['pmTimeLog'])
                    ? $timeLogs['pmTimeLog']['afternoonIn']
                    : null,
                'afternoon_out' => isset($timeLogs['pmTimeLog']) && !empty($timeLogs['pmTimeLog'])
                    ? $timeLogs['pmTimeLog']['afternoonOut']
                    : null,
                'overtime_in' => isset($timeLogs['otTimeLog']) && !empty($timeLogs['otTimeLog'])
                    ? $timeLogs['otTimeLog']['overtimeIn']
                    : null,
                'overtime_out' =>  isset($timeLogs['otTimeLog']) && !empty($timeLogs['otTimeLog'])
                    ? $timeLogs['otTimeLog']['overtimeOut']
                    : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );

        $this->reset(
            'editingEmployeeId',
            'morningIn',
            'morningOut',
            'afternoonIn',
            'afternoonOut',
            'overtimeIn',
            'overtimeOut'
        );

        $this->emit('success', 'success');
        $this->dispatchBrowserEvent('success');
    }

    public function editCancelEmployeeLog($empId)
    {   
        if ($this->editingEmployeeId === $empId) {
            $this->editingEmployeeId = '';
        }
    }

    /* public function mount()
    {
        $this->morningIn = $this->employees->morningIn;
        $this->morningOut = $this->employees->morningOut; 
        $this->afternoonIn = $this->employees->afternoonIn;
        $this->afternoonOut = $this->employees->afternoonOut;
        $this->overtimeIn = $this->employees->overtimeIn;
        $this->overtimeOut =  $this->employees->overtimeOut;
    } */

    

    public function render()
    {
        $this->importDailyLog();
        $this->fileExcel = "";
        $today = Carbon::now();
        $dateToFilter = $this->filterDate ?? $today;
        $dateToFilter = Carbon::parse($dateToFilter)->format('Y-m-d');
        
        
        // $employees = EmployeeInformation::query()
        $employees = EmployeeInformation::orderby('employee_information.last_name', 'asc')
        ->select(
            'employee_information.id',
            'employee_information.employee_uuid',
            'employee_information.first_name',
            'employee_information.last_name',
            'employee_information.employee_uuid',
        );
        
        $employees->addSelect(['morningIn' => EmployeeTimeLog::select('morning_in')
            ->whereColumn('employee_information_id', 'employee_information.id')
            ->whereDate('attendance_date', $dateToFilter)
        ]);

        $employees->addSelect([
            'morningOut' => EmployeeTimeLog::select('morning_out')
            ->whereColumn('employee_information_id', 'employee_information.id')
            ->whereDate('attendance_date', $dateToFilter)
        ]);

        $employees->addSelect([
            'afternoonIn' => EmployeeTimeLog::select('afternoon_in')
            ->whereColumn('employee_information_id', 'employee_information.id')
            ->whereDate('attendance_date', $dateToFilter)
        ]);

        $employees->addSelect([
            'afternoonOut' => EmployeeTimeLog::select('afternoon_out')
            ->whereColumn('employee_information_id', 'employee_information.id')
            ->whereDate('attendance_date', $dateToFilter)
        ]);

        $employees->addSelect([
            'overtimeIn' => EmployeeTimeLog::select('overtime_in')
            ->whereColumn('employee_information_id', 'employee_information.id')
            ->whereDate('attendance_date', $dateToFilter)
        ]);

        $employees->addSelect([
            'overtimeOut' => EmployeeTimeLog::select('overtime_Out')
            ->whereColumn('employee_information_id', 'employee_information.id')
            ->whereDate('attendance_date', $dateToFilter)
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

        $employees = $employees->paginate(5);
        // $employees = $employees->toSql();
        $sites = WorkingSite::all();
        // $this->employees = $employees;
        // dump($employees);

        // dump(Carbon::parse($today)->format('Y-m-d'));
        return view('livewire.attendance-log.daily-log', [
            'employees' => $employees,
            'sites' => $sites,
            'today' => $today->toFormattedDateString(),
        ]);
    }
}
