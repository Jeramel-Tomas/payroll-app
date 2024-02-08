<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class BackupDatabaseController extends Controller
{
    public function backupIndex()
    {
        return view('backup-database.backupIndex');
    }

    public function downLoadDb()
    {
        $db_name = env('DB_DATABASE');
        $db_user = env('DB_USERNAME');
        $db_password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $today = Carbon::now()->format('Y-m-d');

        /* this is store in laravel app storage */
        $backupPath = storage_path("app/backup-db/");
        $fileName = $backupPath.$db_name.'-'.$today.'.sql';

        $command = "C:/xampp/mysql/bin/mysqldump --opt -h$host -u$db_user --password=$db_password $db_name > $fileName";
        shell_exec($command);

        return response()->download($fileName);
    }
}
