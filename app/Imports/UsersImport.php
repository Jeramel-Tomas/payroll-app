<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\EmployeeInformation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\EmployeeWorkingSite;
use App\Models\WorkingSite;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;


HeadingRowFormatter::default('none');

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public $insertedRowCount = 0;
    public $notInsertedRowCount = 0;
    public $blankRow = 0;
    public $duplicateEmployee = 0;

    public function getDuplicateEmployee(): int
    {
        return $this->duplicateEmployee;
    }
    public function getBlankRowCount(): int
    {
        return $this->blankRow;
    }
    public function getInsertedRowCount(): int
    {
        return $this->insertedRowCount;
    }
    public function getNotInsertedRowCount(): int
    {
        return $this->notInsertedRowCount;
    }
    public function model(array $row)
    {
        // Check if the row is empty
        // if (empty(array_filter($row))) {
        //     $this->blankRow++;
        //     return null; 
        // }

        // Check if any of the required columns are missing
        $requiredColumns = ['First Name', 'Last Name', 'Gender'];
        $missingColumn = false;
        foreach ($requiredColumns as $column) {
            if (empty($row[$column])) {
                $missingColumn = true;
            }
        }

        if ($missingColumn) {
            // If any of the required fields is empty, increment notInsertedRowCount
            $this->notInsertedRowCount++;
            return null; // Skip processing this row
        }

        // All required columns are present, process the row
        $excelDateValue = $row['Employment Date'];
        $readableDate = Date::excelToDateTimeObject($excelDateValue)->format('Y-m-d');
        // Check for duplicate entry
        $duplicateEmployee = EmployeeInformation::where([
            'first_name' => $row['First Name'],
            'last_name' => $row['Last Name'],
            'gender' => $row['Gender'],
            // 'employment_date' => $readableDate,
        ])->first();

        if ($duplicateEmployee) {
            $this->duplicateEmployee++;
            $this->notInsertedRowCount++;
            return null; // Skip processing this row
        }
        $uuid = Str::uuid()->toString();
        $employee = new EmployeeInformation([
            'employee_uuid'     => $uuid,
            'first_name'        => $row['First Name'],
            'middle_name'       => $row['Middle Name'],
            'last_name'         => $row['Last Name'],
            'gender'            => $row['Gender'],
            'address'           => $row['Address'],
            'contact_number'    => $row['Contact Number'],
            'employment_date'   => $readableDate,
        ]);

        $employee->save();
        $this->insertedRowCount++; // Increment inserted row count
        return $employee;
    }



    public function headingRow(): int
    {
        return 10;
    }
    public function chunk(Collection $rows)
    {
        // dd($rows);
    }
    public function chunkSize(): int
    {
        return 100;
    }
}
