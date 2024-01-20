<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\MenuController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeePayrollInfo;
use App\Http\Controllers\EmployeePayrollInfoController;

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


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('employees')->group(function () {
    Route::get('/list', [EmployeeController::class, 'index'])->name('employees.list');
    Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::get('/{empid}/show', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/export', [EmployeeController::class, 'export'])->name('export');
    Route::post('/import', [EmployeeController::class, 'import'])->name('import');

    Route::get('/{empid}/update', [EmployeeController::class, 'update'])->name('employees.update');
    Route::get('/{empid}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::post('/store/{siteId}', [EmployeeController::class, 'store'])->name('employees.store');

    Route::get('/download-template', [ExportController::class, 'downloadTemplate'])->name('download.template');;
    Route::post('/addSite', [EmployeeController::class, 'addSite'])->name('employees.addSite');
});

Route::prefix('/attendance')->group(function () {
    Route::get('/log', [AttendanceLogController::class, 'index'])->name('attendance.log.index');
    Route::get('/attendance-sheet', [AttendanceLogController::class, 'attendanceSheetIndex'])->name('attendance.sheet.index');
    /*  Route::get('/showlogs/{siteId}', [AttendanceLogController::class, 'showAttendanceLogPerSite'])->name('attendance.showlog.persite');
    Route::get('/showlogs/{siteId}', [AttendanceLogController::class, 'showAttendanceLogPerSite'])->name('attendance.showlog.persite');
    Route::get('/save-attendance', [AttendanceLogController::class, 'saveAttendanceAjax'])->name('attendance.saveAttendanceAjax'); */
});

Route::prefix('/payroll')->group(function () {
    Route::get('/employees', [EmployeePayrollInfoController::class, 'index'])->name('manage.payroll.index');
    Route::get('/generate', [EmployeePayrollInfoController::class, 'generatePayslip'])->name('generate.payslip');
    Route::get('/cash-advanced', [EmployeePayrollInfoController::class, 'cashAdvancedIndex'])->name('cash.advanced.index');
    Route::get('/cash-advanced/{id}/view', [EmployeePayrollInfoController::class, 'cashAdvancedView'])->name('view.employee.cash.advances');
    Route::get('/cash-advanced/{id}/view/date-filter', [EmployeePayrollInfoController::class, 'cashAdvancedView'])->name('date.filter.cashadvance');
    Route::get('/cash-advanced/{id}/view/{ecaid}', [EmployeePayrollInfoController::class, 'downLoadPdf'])->name('dl.pdf');
    // Route::get('/cash-advanced/{id}/view/date-filter/{ecaid}', [EmployeePayrollInfoController::class, 'downLoadPdfFiltered'])->name('dl.filter.pdf');
    // view.employee.cash.advances
});

/* Route::get('/pdfdl', function() {
    return view('employee-payroll-management.download-cash-advances.cashAdvancedDl');
}); */



