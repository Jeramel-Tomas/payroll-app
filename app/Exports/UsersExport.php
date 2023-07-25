<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;
use App\Models\WorkingSite;



class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //testing export only
            $getEmployee = DB::table('employee_information')
        ->leftJoin('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id')
        ->leftJoin('working_sites', 'working_sites.id', '=', 'employee_working_sites.working_site_id')
        ->select( 'employee_information.*', 'working_sites.*','employee_working_sites.*')
        ->get();
        $columnNames = $getEmployee->count() > 0 ? array_keys((array) $getEmployee->first()) : [];
        return($getEmployee);
    }
}
