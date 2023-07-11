<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addEmployeeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MenuController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/addEmployee', function () {
    return view('add_employee');
})->name('add.employee');
Route::get('/index', function () {
    return view('index');
});



Route::post('/employees', [AddEmployeeController::class, 'store'])->name('employees.store');

//Route::get('/getEmployeeData', [Employee::class, 'getEmployeeData'])->name('employees.getEmployeeData');
Route::get('/employeeList', [EmployeeController::class, 'index'])->name('employee.list');
Route::get('/employees/{id}/edit', [AddEmployeeController::class, 'editEmployeeInformation'])->name('employees.editEmployeeInformation');

Route::post('/employees/{id}', [AddEmployeeController::class, 'update'])->name('employees.update'); */

Route::get('/homepage', [MenuController::class, 'homePage'])->name('home.page');
Route::get('/login/page', [MenuController::class, 'loginPage'])->name('login.page');
Route::get('/attendance', [MenuController::class, 'attendance'])->name('attendance.page');

Route::get('/', function () {
    return view('theme-layout.index');
});


Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employees.list');
    Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::get('/{empid}/show', [EmployeeController::class, 'show'])->name('employees.show');

    Route::patch('/{empid}/update', [EmployeeController::class, 'update'])->name('employees.update');
    Route::get('/{empid}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store');
    //Changes: new Route for adding site
    Route::post('/addSite', [EmployeeController::class, 'addSite'])->name('employees.addSite');
});
