<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkingSite extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'site_name' => 'No Site Assigned'
            ],
            [
                'site_name' => 'Pico'
            ],
            [
                'site_name' => 'Irisan'
            ],
            [
                'site_name' => 'Itogon Rescue'
            ],
            [
                'site_name' => 'Guisset'
            ],
            [
                'site_name' => 'MT Data'
            ]
            
        ];

        DB::table('working_sites')->insert($data);
    }
}
