<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeePayrollInfo;
use App\Http\Controllers\WorkingSitesController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\BackupDatabaseController;
use App\Http\Controllers\EmployeeAttendanceController;
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

    // Route::get('/download-template', [ExportController::class, 'downloadTemplate'])->name('download.template');;
    Route::post('/addSite', [EmployeeController::class, 'addSite'])->name('employees.addSite');
});

Route::prefix('/attendance')->group(function () {
    Route::get('/manage', [EmployeeAttendanceController::class, 'index'])->name('attendance.log.manage');
    // Route::get('/log', [AttendanceLogController::class, 'index'])->name('attendance.log.index');
    // Route::get('/attendance-sheet', [AttendanceLogController::class, 'attendanceSheetIndex'])->name('attendance.sheet.index');
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
    Route::get('/cash-advanced/edit/{eid}', [EmployeePayrollInfoController::class, 'edit'])->name('cash.advances.edit');
    Route::post('/cash-advanced/save-cashadvance-edit', [EmployeePayrollInfoController::class, 'saveEditedData'])->name('save.cash.advance.edit');
    // Route::get('/cash-advanced/{id}/view/date-filter/edit/{ecaid}', [EmployeePayrollInfoController::class, 'edit'])->name('cash.advances.edit');
    Route::get('/cash-advanced/{id}/view/{ecaid}', [EmployeePayrollInfoController::class, 'downLoadPdf'])->name('dl.pdf');
    Route::get('/download-all-payslip', [EmployeePayrollInfoController::class, 'generatePayslipDownload'])->name('download.payslip');
    Route::get('/download-single-payslip/{id}', [EmployeePayrollInfoController::class, 'generateSinglePayslipDownload'])->name('single.download.payslip');
    // Route::get('/cash-advanced/{id}/view/date-filter/{ecaid}', [EmployeePayrollInfoController::class, 'downLoadPdfFiltered'])->name('dl.filter.pdf');
    // view.employee.cash.advances
});

Route::prefix('/working-sites')->group(function () {
    Route::get('/working-sites-index', [WorkingSitesController::class, 'index'])->name('working.sites.index');
    Route::get('/show-employees/{siteId}', [WorkingSitesController::class, 'showEmployees'])->name('working.site.assigned.employees');
    Route::get('/salary-expenses', [WorkingSitesController::class, 'salaryExpensesPersite'])->name('working.site.salary.expenses');
});


Route::prefix('/backup-db')->group(function () {
    Route::get('/backup-index', [BackupDatabaseController::class, 'backupIndex'])->name('bakcupdb.index');
    /* Route::get('/export', function () {
        shell_exec("C:/xampp/mysql/bin/mysqldump -h localhost -u root test > C:/xampp/htdocs/projects/main.sql");
    }); */
    Route::get('test-backup', function () {
        $exitCode = Artisan::call('backup:run');
        dd($exitCode);
    })->name('test.backup');
    Route::get('/backup-index/dldb', [BackupDatabaseController::class, 'downLoadDb'])->name('download.db');
});



