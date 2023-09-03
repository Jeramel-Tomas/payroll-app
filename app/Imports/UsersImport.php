<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\EmployeeInformation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\EmployeeWorkingSite;
use App\Models\WorkingSite;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;


HeadingRowFormatter::default('none');

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    

    public function model(array $row)
    {
        
        $requiredColumns = ['First Name', 'Last Name', 'Gender', 'Job Title', 'Daily Rate', 'Address', 'Contact Number', 'Employment Date'];
    
        foreach ($requiredColumns as $column) {
            if (empty($row[$column])) {
                return null; // Skip this row
            }
        }
        $excelDateValue = $row['Employment Date'];
        
        $readableDate = Date::excelToDateTimeObject($excelDateValue)->format('Y-m-d');
        $uuid = Str::uuid()->toString();
        $employee = new EmployeeInformation([
            'employee_uuid'     => $uuid,
            'first_name'        => $row['First Name'],   // Use heading row key
            'middle_name'       => $row['Middle Name'],
            'last_name'         => $row['Last Name'],
            'gender'            => $row['Gender'],
            'job_title'         => $row['Job Title'],
            'daily_rate'        => $row['Daily Rate'],
            'address'           => $row['Address'],
            'contact_number'    => $row['Contact Number'],
            'employment_date'   => $readableDate,
            ]);
        dump($employee);
        $employee->save(); 
        $generatedId = $employee->id;
        $ews = new EmployeeWorkingSite([
            'employee_information_id' => $generatedId,
            'working_site_id' => intval($row['Site Id']),  
        ]);
        $ews->save();

        return $employee;
    }
    public function headingRow(): int
    {
        return 10;
    }
    public function chunk(Collection $rows)
    {
        // dd($rows);
    }
    public function chunkSize(): int
    {
        return 100;
    }
    
    
}
