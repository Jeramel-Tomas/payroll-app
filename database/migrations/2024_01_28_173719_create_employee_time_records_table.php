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
        Schema::create('employee_time_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('site_id');
            $table->char('days_present', 11)->nullable();
            $table->char('total_ot', 11)->nullable();
            $table->date('attendance_from')->nullable();
            $table->date('attendance_to')->nullable();
            $table->foreign('employee_id')->references('id')->on('employee_information');
            $table->foreign('site_id')->references('id')->on('working_sites');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_time_records');
    }
};
