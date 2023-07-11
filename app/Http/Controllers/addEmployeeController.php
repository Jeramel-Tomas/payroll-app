<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class AddEmployeeController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        dd($request->all());
        $validatedData = $request->validate([
            'firstName' => 'required',
            'middleName' => 'nullable',
            'lastName' => 'required',
            'gender' => 'required',
            'jobTitle' => 'required',
            'houseNumber' => 'required',
            'sitio' => 'required',
            'brgy' => 'required',
            'city' => 'required',
            'contactNumber' => 'required',
        ]);
        //dd($validatedData);
        // Create a new Employee instance with the validated data
        $employee = new Employee();
        $employee->first_name = $validatedData['firstName'];
        $employee->middle_name = $validatedData['middleName'];
        $employee->last_name = $validatedData['lastName'];
        $employee->gender = $validatedData['gender'];
        $employee->job_title = $validatedData['jobTitle'];
        $employee->house_number = $validatedData['houseNumber'];
        $employee->sitio = $validatedData['sitio'];
        $employee->baranggay = $validatedData['brgy'];
        $employee->municipality = $validatedData['city'];
        $employee->contactNumber = $validatedData['contactNumber'];

        // Save the employee to the "users" table
        $employee->save();

        // Redirect the user back to the form page or to a success page
        return redirect()->back()->with('success', 'Employee added successfully!');
    }
    public function editEmployeeInformation($id)
    {
        // Retrieve the employee data based on the provided ID
        $employee = Employee::find($id); // SELECT * FROM employee WERE id = {$di}

        // You can pass the employee data to the edit view or perform any other necessary logic

        // Return the edit view
        return view('editEmployeeInformation', ['employee' => $employee]);

    }
    

}
