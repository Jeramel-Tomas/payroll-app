<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_working_sites', function (Blueprint $table) {
            $table->dropUnique('employee_working_sites_employee_information_id_unique');
            $table->unsignedBigInteger('employee_information_id')->change();
            $table->char('job_title', 120)->nullable();
            $table->foreign('employee_information_id')->references('id')->on('employee_information');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_working_sites', function (Blueprint $table) {
            //
        });
    }
};
