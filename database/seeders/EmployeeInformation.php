<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeInformation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'employee_uuid' => Str::uuid()->toString(),
                'first_name' => 'BRETCHER',
                'last_name' => 'FANGOLLAO',
                'gender' => 'female',
                'job_title' => 'Checker',
                'daily_rate' => '365',
                'address' => 'LTB',
                'contact_number' => '111111111111',
            ],
            [
                'employee_uuid' => Str::uuid()->toString(),
                'first_name' => 'DANIEL',
                'last_name' => 'DALINGAY',
                'gender' => 'male',
                'job_title' => 'Driver',
                'daily_rate' => '450',
                'address' => 'LTB',
                'contact_number' => '222222222222',
            ],
            [
                'employee_uuid' => Str::uuid()->toString(),
                'first_name' => 'JUNIFER',
                'last_name' => 'JUNIFER',
                'gender' => 'female',
                'job_title' => 'Checker',
                'daily_rate' => '365',
                'address' => 'LTB',
                'contact_number' => '333333333333',
            ]
        ];
        DB::table('employee_information')->insert($data);
    }
}
