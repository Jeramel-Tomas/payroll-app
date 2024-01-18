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
        Schema::create('employee_job_information', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_information_id');
            // $table->char('job_title', 120);
            $table->char('employment_status', 80)->nullable();
            // $table->char('daily_rate', 80)->nullable();
            // $table->char('monthly_rate', 80)->nullable();
            $table->char('daily_time_schedule_am', 80)->nullable();
            $table->char('daily_time_schedule_pm', 80)->nullable();
            // M = monthly, D = daily, S = Semi monthly (15 and end of the month)
            $table->char('payday', 4)->nullable();
            $table->char('day_off', 80)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_job_information');
    }
};
