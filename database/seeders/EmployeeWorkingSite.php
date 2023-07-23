<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeWorkingSite extends Seeder
{
    public function run(): void
    {
        //
        $data = [
            [
                'employee_information_id' => '1',
                'working_site_id' => '1'
            ],
            [
                'employee_information_id' => '2',
                'working_site_id' => '1'
            ],
            [
                'employee_information_id' => '3',
                'working_site_id' => '1'
            ],
        ];
    
        DB::table('employee_working_sites')->insert($data);
    }
}
