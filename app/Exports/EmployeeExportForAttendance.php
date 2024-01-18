<?php

namespace App\Exports;

use App\Models\EmployeeInformation;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
// use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeExportForAttendance implements WithHeadings, WithCustomStartCell, FromArray, ShouldAutoSize, WithEvents
{
    use Exportable, RegistersEventListeners;
    
    protected $request;
    protected $employeeData;
    protected $monthYear;
    protected $halfMonthType;
    protected $dtrDate;
    protected $timeRecordType;

    private static $staticMonthYearHalfMonth;
    private static $staticHalfMonthType;
    private static $staticDtrDate;
    private static $staticTimeRecordType;
    private static $staticEmployeeDataCount;

    public function __construct(array $employeeData, array $customHeadings)
    {
        $this->employeeData = $employeeData;
        self::$staticEmployeeDataCount = count($employeeData);

        if (isset($customHeadings['halfMonth'])) {
            $this->monthYear = $customHeadings['halfMonth']['selectedMonthForMonthType'];
            $this->halfMonthType = $customHeadings['halfMonth']['halfMonthType'];
            self::$staticMonthYearHalfMonth = $customHeadings['halfMonth']['selectedMonthForMonthType'];
            self::$staticHalfMonthType = $customHeadings['halfMonth']['halfMonthType'];
        }

        if (isset($customHeadings['daily'])) {
            $this->dtrDate = $customHeadings['daily']['date'];
            $this->timeRecordType = $customHeadings['daily']['timeRecordType'];
            self::$staticDtrDate = $customHeadings['daily']['date'];
            self::$staticTimeRecordType = $customHeadings['daily']['timeRecordType'];
        }
    }

    public function array(): array
    {
        return $this->employeeData;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
   /*  public function collection()
    { */
        // return $data;
        /*  return EmployeeInformation::orderby('employee_information.last_name', 'asc')
        ->select(
            'employee_information.id',
            'employee_information.first_name',
            'employee_information.last_name',
        )->get(); */
        // return EmployeeInformation::all();

        /* $allEmployees = EmployeeInformation::orderby('employee_information.last_name', 'asc')
        ->select(
            'employee_information.id',
            'employee_information.first_name',
            'employee_information.last_name',
        )->get();

        $data = [];

        foreach ($allEmployees as $value) {
            // dump($value->last_name);
            $data[] = [
                'id' => $this->changeIdCharacters($value->id),
                'name' => $value->last_name . ', ' . $value->first_name
            ];
        }

        return $data;
    } */

    public function startCell(): string
    {
        return 'A5';
    }

    public function headings(): array
    {
        if ($this->monthYear && $this->halfMonthType) {   
            $monthYear = explode('-', $this->monthYear);
            // return $monthYear;
            $calculatedDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthYear[1], $monthYear[0]);

            // dump('$calculatedDaysInMonth = ', $calculatedDaysInMonth);
            $daysInMonth = [];
            for ($i = 1; $i <= $calculatedDaysInMonth; $i++) {
                $daysInMonth[] = $i;
            }

            $halfDaysInMonth = [];
            if ($this->halfMonthType === 'firstHalf') {
                $halfDaysInMonth = array_slice($daysInMonth, 0, 15);
            }

            if ($this->halfMonthType === 'secondHalf') {
                $halfDaysInMonth = array_slice($daysInMonth, 15, count($daysInMonth));
            }
            return [
                'Id',
                'Name',
                ...$halfDaysInMonth
            ];
        }

        return [
            'Id',
            'Name',
            'In (AM)',
            'Out (AM)',
            'In (PM)',
            'Out (PM)',
            'In (OT)',
            'Out (OT)'
        ];
    }

    public static function beforeSheet(BeforeSheet $event)
    {        
        $text = "";
        $date = "";
        if (self::$staticDtrDate && self::$staticTimeRecordType) {
            // dailyTimeRecord
            /* dump('self::$staticDtrDate = ', self::$staticDtrDate);
            dump('self::$staticTimeRecordType = ', self::$staticTimeRecordType); */
            $text = self::cammelCaseToWords(self::$staticTimeRecordType) . ' for ';
            $date = Carbon::parse(self::$staticDtrDate)->format('F j, Y');
        }
        
        if (self::$staticMonthYearHalfMonth && self::$staticHalfMonthType) {     
            $text = self::cammelCaseToWords(self::$staticHalfMonthType) . ' of ';
            $date = Carbon::parse(self::$staticMonthYearHalfMonth)->format('F Y');
        }
        // dump('text = ', $text);
        // dump('date = ', $date);
        $titleHeader = $text . $date;
        $today = Carbon::now()->format('F j, Y');
        $event->sheet->appendRows([
            ['', 'Today is: ' . $today],
            ['', $titleHeader],
            ['', 'Date: ' . $date],
        ], $event);
    }

    public static function afterSheet(AfterSheet $event)
    {
        if (!self::$staticDtrDate && !self::$staticTimeRecordType) {
            $note = 'NOTE: The following legends are the only valid values for each cell.';
            $present = 'Present = P';
            $absent = 'Absent = A';
            $halfday = 'Half day = H-AM or H-PM';
            $ot = 'Overtime = OT-2:30 (hours:minutes)';
            $combinations = 'Possible combinations = P,OT-1:45 | H-PM,OT-3:00';

            // $event->sheet->getStyle('B')->getAlignment()->setWrapText(true);
            $event->sheet->appendRows([
                [''],
                [''],
                ['', $note],
                ['', $present],
                ['', $absent],
                ['', $halfday],
                ['', $ot],
                ['', $combinations],
            ], $event);
            
            $rowNumber = self::$staticEmployeeDataCount + 8;
            $cell = 'B' . $rowNumber . ':B' . $rowNumber + 5;
            // $event->sheet->getDelegate()->getStyle('B'.$rowNumber)->applyFromArray(
            $event->sheet->getDelegate()->getStyle($cell)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'EB2B02']
                    ]
                ]
            );
        }

        // $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setWrapText(true);
    }

    private static function cammelCaseToWords($string)
    {
        if (empty($string)) {
            return $string;
        }

        $string = lcfirst($string);
        $string = preg_replace("/[A-Z]/", ' ' . "$0", $string);

        return strtoupper($string);
    }
}
