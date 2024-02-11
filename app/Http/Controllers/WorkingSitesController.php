<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkingSitesController extends Controller
{

    public function index()
    {
        return view('working-sites-management.workingSitesHome');
    }

    public function showEmployees(Request $request)
    {
        $employeesInWorkingSite = DB::table('employee_information')
            ->join(
                'employee_working_sites', 'employee_information.id', 
                '=', 
                'employee_working_sites.employee_information_id')
            ->join('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
            ->select(
                'employee_information.last_name', 
                'employee_information.first_name',
                'working_sites.site_name'
                )
            ->orderBy('employee_information.last_name')
            ->get();

        $workingSiteName = DB::table('working_sites')->where('id', '=', $request->siteId)->first();

        return view('working-sites-management.workingSiteEmployees', ['workingSite' => $workingSiteName]);
    }

    public function salaryExpensesPersite()
    {
        return view('working-sites-management.salaryExpensesPerSites');
    }

}
