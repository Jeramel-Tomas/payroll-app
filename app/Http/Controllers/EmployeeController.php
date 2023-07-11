<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeInformation;
use App\Models\EmployeeWorkingSite;
use App\Models\WorkingSite;
use Illuminate\Support\Str;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = EmployeeInformation::paginate(5);
        $sites = WorkingSite::all();
        // $employees = Employee::paginate(15)->withQueryString();
        // $employees = DB::table('employee_info')->simplePaginate(1);
        //dd($site);
        return view('employee-management.employees', ['employees' => $employees, 'sites' => $sites]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee-management.createEmployee');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $uuid = Str::uuid()->toString();
        // Validate the form data
        //dd($request->all());
        $validatedData = $request->validate([
            'firstName' => 'required',
            'middleName' => 'nullable',
            'lastName' => 'required',
            'gender' => 'required',
            'jobTitle' => 'required',
            'dailyRate' => 'required',
            'address' => 'required',
            'contactNumber' => 'required',
        ]);
        //dd($validatedData);
        // Create a new Employee instance with the validated data
        $employee = new EmployeeInformation();
        $employee->employee_uuid = $uuid;
        $employee->first_name = $validatedData['firstName'];
        $employee->middle_name = $validatedData['middleName'];
        $employee->last_name = $validatedData['lastName'];
        $employee->gender = $validatedData['gender'];
        $employee->job_title = $validatedData['jobTitle'];
        $employee->daily_rate = $validatedData['dailyRate'];
        $employee->address = $validatedData['address'];
        $employee->contact_number = $validatedData['contactNumber'];

        // Save the employee to the "users" table
        $employee->save();

        // Redirect the user back to the form page or to a success page
        return redirect()->back()->with('success', 'Employee added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // dd($id);
        $employee = EmployeeInformation::find($id);
        // dd($employee->first_name);
        //dd($employee::attributes('first_name'));
        return view('employee-management.showEmployee', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // dd($id);
        $employee = EmployeeInformation::find($id);
        // dd($employee->first_name);
        //dd($employee::attributes('first_name'));
        return view('employee-management.editEmployeeInformation', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $employee = EmployeeInformation::findorfail($id);
        // Validate the form data
        $validatedData = $request->validate([
            'firstName' => 'required',
            'middleName' => 'nullable',
            'lastName' => 'required',
            // Add validation rules for other fields
        ]);
        //dd($request);
        // Update the employee data with the validated form data
        $employee->first_name = $validatedData['firstName'];
        $employee->middle_name = $validatedData['middleName'];
        $employee->last_name = $validatedData['lastName'];
        // Update other fields with the validated form data

        // Save the updated employee record
        //dd($employee);
        $employee->save();

        //Redirect the user back to the employee list or show a success message
        return redirect()->route('employees.list')->with('success', $employee->first_name . ' ' . $employee->last_name . ' information updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    //Changes: New method for adding site 
    public function addSite(Request $request)
    {
        $validatedData = $request->validate([
            'empID' => 'required',
            'working_site' => 'required',
        ]);
        $getEmployee = EmployeeInformation::join('employee_working_sites as ews', 'employee_information.id', '=', 'ews.employee_information_id')
        ->where('ews.employee_information_id', $validatedData['empID'])
        ->first();
        //dd($getEmployee);
        $duplicateSite = EmployeeWorkingSite::where('employee_information_id', $validatedData['empID'])->first();
        if ($duplicateSite) {
            return redirect()->back()->with('error', $getEmployee->first_name. ' ' .$getEmployee->last_name . ' ' .' is already asssigned to a Site!'); 
        }
        $empSite = new EmployeeWorkingSite();
        $empSite->employee_information_id = $validatedData['empID'];
        $empSite->working_site_id = $validatedData['working_site'];
        $empSite->save();

        // // Redirect the user back to the form page or to a success page
        return redirect()->back()->with('success', 'Employee Site Added successfully!');
    }
}
