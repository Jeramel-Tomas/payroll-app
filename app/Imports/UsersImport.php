<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;
use App\Models\WorkingSite;
use Illuminate\Support\Str;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $uuid = Str::uuid()->toString();//generate uuid before inserting into the db
        $employee = new EmployeeInformation([
            'employee_uuid'     => $uuid,
            'first_name'        => $row[2],//rows are from excel (to be modified once there is a final excel template)
            'middle_name'       => $row[3], 
            'last_name'         => $row[4],
            'gender'            => $row[5],
            'job_title'         => $row[6],
            'daily_rate'        => $row[7],
            'address'           => $row[8],
            'contact_number'    => $row[9],
            'employment_date'   => $row[10],
        ]);
        $employee->save(); //save 1st so we can generate the primary key
        $generatedId = $employee->id;//generated primary key
        $ews = new EmployeeWorkingSite([
            'employee_information_id' => $generatedId,
            'working_site_id' => $row[11], 
        ]);
        $ews->save();

        return $employee;
    }
}
