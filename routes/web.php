<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\MenuController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\DashboardController;
//use App\Http\Controllers\ExcelCSVController;

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
});*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('employees')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employees.list');
    Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::get('/{empid}/show', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/export', [EmployeeController::class, 'export'])->name('export');
    Route::post('/import', [EmployeeController::class, 'import'])->name('import');

    Route::get('/{empid}/update', [EmployeeController::class, 'update'])->name('employees.update');
    Route::get('/{empid}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::post('/store/{siteId}', [EmployeeController::class, 'store'])->name('employees.store');

    Route::post('/addSite', [EmployeeController::class, 'addSite'])->name('employees.addSite');
});

Route::prefix('/attendance')->group(function () {
    Route::get('/', [AttendanceLogController::class, 'index'])->name('attendance.log.index');
    Route::get('/showlogs/{siteId}', [AttendanceLogController::class, 'showAttendanceLogPerSite'])->name('attendance.showlog.persite');
    Route::get('/showlogs/{siteId}', [AttendanceLogController::class, 'showAttendanceLogPerSite'])->name('attendance.showlog.persite');
    Route::get('/save-attendance', [AttendanceLogController::class, 'saveAttendanceAjax'])->name('attendance.saveAttendanceAjax');
});
