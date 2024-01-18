<?php

namespace App\Http\Livewire\AttendanceLog;

use Carbon\Carbon;
use Livewire\Component;
// use Maatwebsite\Excel;
use App\Models\WorkingSite;
use Illuminate\Support\Collection;
use App\Models\EmployeeInformation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeExportForAttendance;

// use Maatwebsite\Excel\Excel as Excel;

class ShowExport extends Component
{
    // public Collection $allEmployees;
    // protected EmployeeExportForAttendance $empExportAttendance;

    public $modalTitle = "Export employee";
    public $showExportModal = false;
    public $typetoExport = "", $timeRecordType = "", $halfMonthType = "";
    public $employeeCount = 0;
    public $allEmployee = 'allEmployee', 
        $bySite = 'bySite', 
        $individual = 'individual',
        $dailyTimeRecord = 'dailyTimeRecord',
        $halfMonth = 'halfMonth',
        $changeRadioValueToWords = "",
        $selectedMonthForMonthType = "",
        $selectedDateForDTR = "",
        $selectedWorkingSite = "";
    public $showExportButton = false;
    public $listOfSites = "";
    // by site
    public $dtrTypeBySite = "";
    public $dtrSelectedDateBySite = "";
    public $selectedSecondHalfMonthForMonthTypeBySite = "";
    public $selectedFirstHalfMonthForMonthTypeBySite = "";

    // private $workingSiteEmployeesData = [];
    public $employeesData = [];

    protected $listeners = [
        'clearChildComponentProperties' => 'clearPropertiesOnModalClose',
    ];

    private $excelExtension = '.xlsx';

    public function clearPropertiesOnModalClose()
    {
        // dd('clearPropertiesOnModalClose');
        /* $this->typetoExport = "";
        $this->typetoExport = "";
        $this->timeRecordType = "";
        $this->halfMonthType = "";
        $this->changeRadioValueToWords = "";
        $this->selectedMonthForMonthType = "";
        $this->employeeCount = 0; */
        $this->reset();
    }

    public function clearAllEmployeesExportSelectionData()
    {
        $this->halfMonthType = "";
        // $this->selectedFirstHalfMonthForMonthTypeBySite = "";
        // $this->selectedSecondHalfMonthForMonthTypeBySite = "";
        // $this->selectedMonthForMonthType = "";

        $this->selectedDateForDTR = "";
        $this->timeRecordType = "";
        $this->employeeCount = 0;
        $this->showExportButton = false;
    }

    public function clearAllBySiteExportSelectionData()
    {
        $this->selectedWorkingSite = "";
        $this->dtrTypeBySite = "";
        $this->dtrSelectedDateBySite = "";
        $this->employeeCount = 0;
        $this->showExportButton = false;
    }

    private function getSelectedCustomHeadings()
    {
        if ($this->selectedDateForDTR && $this->timeRecordType) {
            return [
                'daily' => [
                    'date' => $this->selectedDateForDTR,
                    'timeRecordType' => $this->timeRecordType,
                ]
            ];
        }

        if ($this->selectedMonthForMonthType && $this->halfMonthType) {
            return [
                'halfMonth' => [
                    'selectedMonthForMonthType' => $this->selectedMonthForMonthType,
                    'halfMonthType' => $this->halfMonthType,
                ]
            ];
        }

        if ($this->dtrTypeBySite) {
            $bySiteFirstHalfSecondHalfType = $this->dtrTypeBySite === 'firstHalf' || $this->dtrTypeBySite === 'secondHalf' ? $this->dtrTypeBySite : '';
            
            if ($this->selectedFirstHalfMonthForMonthTypeBySite) {
                $selectedFirstSecondHalfMonth = $this->selectedFirstHalfMonthForMonthTypeBySite;
            }

            if ($this->selectedSecondHalfMonthForMonthTypeBySite) {
                $selectedFirstSecondHalfMonth = $this->selectedSecondHalfMonthForMonthTypeBySite;
            }

            if ($this->dtrTypeBySite === 'dailyTimeRecord' && $this->dtrSelectedDateBySite) {
                return [
                    'daily' => [
                        'date' => $this->dtrSelectedDateBySite,
                        'timeRecordType' => $this->dtrTypeBySite,
                    ]
                ];
            }

            if ($bySiteFirstHalfSecondHalfType && $selectedFirstSecondHalfMonth) {
                return [
                    'halfMonth' => [
                        'selectedMonthForMonthType' => $selectedFirstSecondHalfMonth,
                        'halfMonthType' => $bySiteFirstHalfSecondHalfType,
                    ]
                ];
            }
        }

        return [];
    }

    public function exportEmplyeeData()
    {
        // dump([$this->getAllEmplyees()]);
        // dump($this->empExportAttendance->headings(['Id', 'Name']));
        $customHeadings = $this->getSelectedCustomHeadings();
        // dump($customHeadings); die;
        /* if () {
            # code...
        } */
        /* $customHeadings = [
            'daily' => [
                'date' => $this->selectedDateForDTR,
                'timeRecordType' => $this->timeRecordType,
            ],
            'halfMonth' => [
                'selectedMonthForMonthType' => $this->selectedMonthForMonthType,
                'halfMonthType' => $this->halfMonthType,
            ],
        ]; */
        
        // $this->getAllEmplyees(),
        $toExport = new EmployeeExportForAttendance(
            $this->employeesData,
            $customHeadings 
        );
        /* $toExport = new EmployeeExportForAttendance(
            $this->getAllEmplyees(), 
            $this->selectedMonthForMonthType, 
            $this->halfMonthType
        ); */
        $fileExtensionNameToAdd = Carbon::parse(Carbon::now())->format('YmdHis') . '_';
        $fileName = "";
        if ($this->selectedMonthForMonthType) {
            $fileName = $fileExtensionNameToAdd . $this->selectedMonthForMonthType;
        }

        if ($this->selectedFirstHalfMonthForMonthTypeBySite) {
            $fileName = $fileExtensionNameToAdd . $this->selectedFirstHalfMonthForMonthTypeBySite;
        }

        if ($this->selectedSecondHalfMonthForMonthTypeBySite) {
            $fileName = $fileExtensionNameToAdd . $this->selectedSecondHalfMonthForMonthTypeBySite;
        }

        if ($this->dtrSelectedDateBySite) {
            $fileName = $fileExtensionNameToAdd . $this->dtrSelectedDateBySite;
        }

        if ($this->selectedDateForDTR) {
            $fileName = $fileExtensionNameToAdd . $this->selectedDateForDTR;
        }
        // $eefa = new EmployeeExportForAttendance;
        // $customHeadings = $eefa->headings($this->customHeadingsForHalfMonth($this->selectedMonthForMonthType, $this->halfMonthType));
        // dump($customHeadings);
        // $employeeData = $eefa->collection($this->getAllEmplyees());
        // dump($employeeData);
        // dd(new EmployeeExportForAttendance($headings));
        if (Excel::download($toExport, $fileName . $this->excelExtension)) {
            $this->clearPropertiesOnModalClose();
            return Excel::download($toExport, $fileName . $this->excelExtension);
        }
        // return Excel::download($toExport, $fileName.$this->excelExtension);
    }

    public function render()
    {
        if ($this->typetoExport === $this->allEmployee) {
            $this->clearAllBySiteExportSelectionData();
            $this->checkTimeRecordTypeAllEmployees($this->timeRecordType);
            $this->employeesData = $this->getAllEmplyees();
        } 

        // Select by site
        if ($this->typetoExport === $this->bySite) {
            $this->clearAllEmployeesExportSelectionData();
            $this->listOfSites = $this->getListOfSites();
        }
        // Select by site
        if ($this->selectedWorkingSite) {
            // dtrTypBySite values might be dailyTimeRecord, firstHalf or secondHalf
            $this->getSelectedWorkingSite($this->dtrTypeBySite);
            // dump($this->selectedWorkingSite);
            // dump($this->getAllEmplyees($this->selectedWorkingSite));
        }
        // dump($this->customHeadingsForHalfMonth(''));
        if ($this->halfMonthType && $this->halfMonthType !== "") {
            $this->changeRadioValueToWords = $this->cammelCaseToWords($this->halfMonthType);
        }

        return view('livewire.attendance-log.show-export');
    }

    private function getListOfSites()
    {
        $sites = WorkingSite::all();
        if ($sites->count() > 0) {
            return $sites;
        }
        return "";
    }

    private function getSelectedWorkingSite($dtrTypeBySite="")
    {
        if ($dtrTypeBySite === 'dailyTimeRecord') {
            # code... daily time record
            if ($this->dtrSelectedDateBySite) {
                # code...
                // dump($this->dtrSelectedDateBySite);
                $this->employeesData = $this->getAllEmplyees($this->selectedWorkingSite);
                $this->employeeCount = count($this->getAllEmplyees($this->selectedWorkingSite));
                $this->showExportButton = true;
            }
        }

        if ($dtrTypeBySite === 'firstHalf') {
            $this->selectedSecondHalfMonthForMonthTypeBySite = "";
            if ($this->selectedFirstHalfMonthForMonthTypeBySite) {
                $this->employeesData = $this->getAllEmplyees($this->selectedWorkingSite);
                $this->employeeCount = count($this->getAllEmplyees($this->selectedWorkingSite));
                $this->showExportButton = true;
            }
        }

        if ($dtrTypeBySite === 'secondHalf') {
            $this->selectedFirstHalfMonthForMonthTypeBySite = "";
            if ($this->selectedSecondHalfMonthForMonthTypeBySite) {
                # code...
                $this->employeesData = $this->getAllEmplyees($this->selectedWorkingSite);
                $this->employeeCount = count($this->getAllEmplyees($this->selectedWorkingSite));
                $this->showExportButton = true;
            }
        }
    }

    private function getAllEmplyees($workingSite = "")
    {
        $employees = EmployeeInformation::orderby('employee_information.last_name', 'asc')
        ->select(
            'employee_information.id',
            'employee_information.first_name',
            'employee_information.last_name',
        );
        // ->get();

        if (!empty($workingSite)) {
            $employees->join('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id');
            $employees->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id');
            $employees->where('working_sites.id', '=', $workingSite);
        }

        $allEmployees = $employees->get();
        $data = [];
        
        foreach ($allEmployees as $value) {
            // dump($value->last_name);
            $data[] = [
                'id' => $this->changeIdCharacters($value->id),
                'name' => $value->last_name . ', ' . $value->first_name
            ];
        }

        return $data;
    }
    // timeRecordType = dailyTimeRecord
    // timeRecordType = halfMonth
    private function checkTimeRecordTypeAllEmployees($timeRecordtype)
    {
        if ($timeRecordtype === $this->dailyTimeRecord) {
            // process dailyTimeRecordTimeRecordType()
            $this->dailyTimeRecordTimeRecordType();
        }

        if ($timeRecordtype === $this->halfMonth) {
            // process halfMonthTimeRecordType()
            $this->halfMonthTimeRecordType();
        }
    }

    private function dailyTimeRecordTimeRecordType()
    {
        $this->showExportButton = false;

        if ($this->selectedDateForDTR) {
            $this->halfMonthType = "";
            $this->selectedMonthForMonthType = "";
            $this->employeeCount = count($this->getAllEmplyees());
            $this->showExportButton = true;
        }
    }

    private function halfMonthTimeRecordType()
    {
        $this->showExportButton = false;
        
        if ($this->selectedMonthForMonthType) {
            $this->selectedDateForDTR = "";
            $this->employeeCount = count($this->getAllEmplyees());
            $this->showExportButton = true;
        }
    }

    private function cammelCaseToWords($string)
    {
        if (empty($string)) {
            return $string;
        }

        $string = lcfirst($string);
        $string = preg_replace("/[A-Z]/", ' ' . "$0", $string);

        return strtolower($string);
    }

    private function changeIdCharacters($id = null)
    {
        if (!$id) {
            return;
        }

        $idLength = strlen((string)$id);

        if ($idLength < 2) {
            return '000' . (string)$id;
        }

        if ($idLength > 2 && $idLength < 3) {
            return '00' . (string)$id;
        }

        return '0' . (string)$id;
    }

    /* private function customHeadingsForHalfMonth($monthYear, $halfMonthType)
    {
        $monthYear = explode('-', $monthYear);
        // return $monthYear;
        $calculatedDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthYear[1], $monthYear[0]);
        
        // dump('$calculatedDaysInMonth = ', $calculatedDaysInMonth);
        $daysInMonth = [];
        for ($i = 1; $i <= $calculatedDaysInMonth; $i++) { 
            $daysInMonth[] = $i;
        }
        
        $halfDaysInMonth = [];
        if ($halfMonthType === 'firstHalf') {
            $halfDaysInMonth = array_slice($daysInMonth, 0, 15);
        }

        if ($halfMonthType === 'secondHalf') {
            $halfDaysInMonth = array_slice($daysInMonth, 15, count($daysInMonth));
        }
        return [
            'Id',
            'Name',
            ...$halfDaysInMonth
        ];
    } */
}
