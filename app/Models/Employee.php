<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    private $employees;
    protected $table = 'employee_info';
    protected $primaryKey = 'emp_info_id';

    public function index()
    {
        // Retrieve all employee records from the database
        $this->employees = Employee::all();
        // Pass the employee data to the view
        return view('employee.index', ['employees' => $this->employees]);
    }
    public function getEmployeeData()
    {
        if (!$this->employees) {
            $this->employees = Employee::all();
        }
        return view('view_employee_data', ['employees' => $this->employees]);
    }
}
