<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;
use App\Models\WorkingSite;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function downloadTemplate()
    {
        $filePath = 'downloads/EmployeeUploadTemplate.xlsx';
        $fileName = 'EmployeeUploadTemplate.xlsx';
        $mimeType = Storage::mimeType($filePath);
        $headers = [['Content-Type' => $mimeType]];
        return Storage::download($filePath, $fileName, $headers);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'EmployeeUploadTemplate.xlsx');
    }
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'importedUsers' => [
                'required',
                'file',
                'mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'regex:/^EmployeeUploadTemplate\.xlsx$/i', // Validates the filename
            ],
        ], [
            'importedUsers.mimetypes' => 'The uploaded file must be an Excel spreadsheet (XLSX).',
            'importedUsers.regex' => 'The uploaded file must be named "EmployeeUploadTemplate.xlsx".',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Excel::import(new UsersImport, $request->file('importedUsers'));

        return redirect()->back()->with([
            'success' => 'Import success!',
            'success_expires_at' => now()->addSeconds(5)
        ]);
    }


    public function index()
    {
        $employees = EmployeeInformation::all();
        $sites = WorkingSite::all();

        $getEmployee = DB::table('employee_information')
            ->leftJoin('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id')
            ->leftJoin('working_sites', 'working_sites.id', '=', 'employee_working_sites.working_site_id')
            ->select('employee_information.id AS employee_id', 'employee_information.*', 'employee_working_sites.*', 'working_sites.*')
            ->whereNull('employee_working_sites.employee_information_id')
            ->orWhereNotNull('employee_working_sites.employee_information_id')
            ->paginate(4);
        return view('employee-management.employees', ['getEmployee' => $getEmployee, 'sites' => $sites, 'employees' => $employees]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sites = WorkingSite::all();

        return view('employee-management.createEmployee', compact('sites'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $siteId)
    {
        $uuid = Str::uuid()->toString(); //generate uuid
        $validatedData = $request->validate([
            'firstName' => 'required|min:2|max:24',
            'middleName' => 'nullable',
            'lastName' => 'required|min:2|max:24',
            'gender' => 'required',
            'jobTitle' => 'required|min:2|max:100',
            'dailyRate' => 'required|min:2|max:6',
            'address' => 'nullable',
            'contactNumber' => 'nullable|min:11|max:11',
            'DOE' => 'nullable',
            'site_loc' => 'nullable'
        ]);
        $employee = new EmployeeInformation();
        $emp_working_site = new EmployeeWorkingSite();
        $working_site = new WorkingSite();
        $employee->employee_uuid = $uuid;
        $employee->first_name = $validatedData['firstName'];
        $employee->middle_name = $validatedData['middleName'];
        $employee->last_name = $validatedData['lastName'];
        $employee->gender = $validatedData['gender'];
        $employee->job_title = $validatedData['jobTitle'];
        $employee->daily_rate = $validatedData['dailyRate'];
        $employee->address = $validatedData['address'];
        $employee->contact_number = $validatedData['contactNumber'];
        $employee->employment_date = $validatedData['DOE'];

        //save data to employee_information table so that we can 
        //generate the primary key to be inserted into the next table
        $employee->save();

        $generatedId = $employee->id; //generated primary key
        //saving the generated key to as a foreign key in the employee_working_sites table
        $emp_working_site->employee_information_id = $generatedId;
        $emp_working_site->working_site_id = $siteId;
        $emp_working_site->save();

        return redirect()->back()->with(
            [
                'success' => 'Employee added successfully!',
                'success_expires_at' => now()->addSeconds(5)
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sites = WorkingSite::all();
        $getEmployee = DB::table('employee_information')
            ->leftJoin('employee_working_sites', 'employee_working_sites.employee_information_id', '=', 'employee_information.id')
            ->leftJoin('working_sites', 'working_sites.id', '=', 'employee_working_sites.working_site_id')
            ->where('employee_information.id', $id)
            ->select('*', 'employee_information.id AS empID')
            ->first();
        if ($getEmployee->employee_information_id === null || empty($getEmployee)) {
            return back()->with([
                'danger' => 'You must add a site before VIEWING employee data',
                'danger_expires_at' => now()->addSeconds(5)
            ]);
        } else {
            return view('employee-management.viewEmployee', compact('getEmployee', 'sites'));
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = EmployeeInformation::find($id);
        $sites = WorkingSite::all();
        $checkSite = DB::table('employee_working_sites')
            ->leftJoin('working_sites', 'employee_working_sites.working_site_id', '=', 'working_sites.id')
            ->where('employee_working_sites.employee_information_id', $id)
            ->select('*', 'working_site_id AS wsID')
            ->first();
        if (empty($checkSite->wsID) || is_null($checkSite->wsID)) {
            return back()->with([
                'danger' => 'You must add a site before EDITING employee data',
                'danger_expires_at' => now()->addSeconds(5)
            ]);
        }
        $findSiteID = WorkingSite::find($checkSite->id);
        $findSite = WorkingSite::find($id);
        //some parts of this block unnecesary , not gonna remove it for now
        if (($checkSite === null || $checkSite->employee_information_id === null)) {
            return back()->with([
                'danger' => 'You must add a site before EDITING employee data',
                'danger_expires_at' => now()->addSeconds(5)
            ]);
        } else {
            return view('employee-management.editEmployeeInformation', compact('employee', 'sites', 'findSite', 'findSiteID'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|min:2|max:24',
            'middleName' => 'nullable',
            'lastName' => 'required|min:2|max:24',
            'gender' => 'required',
            'working_site' => 'nullable',
            'jobTitle' => 'required|min:2|max:100',
            'dailyRate' => 'required|numeric|min:2',
            'address' => 'nullable',
            'contactNumber' => 'nullable|min:11|max:11',
            'editDOE' => 'nullable',
        ]);
        $upEmp = DB::table('employee_information')
            ->where('id', $id) //$id = employee_information table primary key
            ->update([
                'first_name' => $validatedData['firstName'],
                'middle_name' => $validatedData['middleName'],
                'last_name' => $validatedData['lastName'],
                'gender' => $validatedData['gender'],
                'job_title' => $validatedData['jobTitle'],
                'daily_rate' => $validatedData['dailyRate'],
                'address' => $validatedData['address'],
                'contact_number' => $validatedData['contactNumber'],
                'employment_date' => $validatedData['editDOE']
            ]);

        $upSite = DB::table('employee_working_sites')
            ->where('employee_information_id', $id) //$id = employee_information table primary key
            ->update(['working_site_id' => $validatedData['working_site']]);
        if ($upSite > 0 || $upEmp > 0) { //check if there and runs the if-body if there are changes on the table rows
            return redirect()->route('employees.list')->with(
                [
                    'success' => 'Employee information updated successfully!',
                    'success_expires_at' => now()->addSeconds(5)
                ]
            );
        } else { //runs this instead if there are no changes, just some messages for user
            return redirect()->route('employees.list')->with(
                [
                    'danger' => 'There were no changes in the employee information',
                    'danger_expires_at' => now()->addSeconds(5)
                ]
            );
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    //Scrapped function
    public function addSite(Request $request)
    {
        $validatedData = $request->validate([
            'empID' => 'required',
            'working_site' => 'required',
        ]);
        $getEmployee = EmployeeInformation::join('employee_working_sites AS ews', 'employee_information.id', '=', 'ews.employee_information_id')
            ->where('ews.employee_information_id', $validatedData['empID'])
            ->get();
        $duplicateSite = EmployeeWorkingSite::where('employee_information_id', $validatedData['empID'])->first();
        if ($duplicateSite) {
            return redirect()->back()->with('error', $getEmployee->first()->first_name . ' ' . $getEmployee->first()->last_name . ' ' . ' is already assigned to a Site!');
        }
        $empSite = new EmployeeWorkingSite();
        $empSite->employee_information_id = $validatedData['empID'];
        $empSite->working_site_id = $validatedData['working_site'];
        $empSite->save();

        return redirect()->back()->with('success', 'Employee Site Added successfully!');
    }
}
