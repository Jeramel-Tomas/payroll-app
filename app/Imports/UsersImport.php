<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\EmployeeInformation;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employee = new EmployeeInformation([
            'first_name'        => $row[2],
            'middle_name'       => $row[3], 
            'last_name'         => $row[4],
            'gender'            => $row[5],
            'job_title'         => $row[6],
            'daily_rate'        => $row[7],
            'address'           => $row[8],
            'contact_number'    => $row[9],
            'employment_date'   => $row[10],
        ]);

        // Save the model to the database
        $employee->save();

        return $employee;
    }
}
