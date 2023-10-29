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
        Schema::create('employee_time_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_information_id');
            $table->date('attendance_date');
            $table->time('morning_in')->nullable();
            $table->time('morning_out')->nullable();
            $table->time('afternoon_in')->nullable();
            $table->time('afternoon_out')->nullable();
            $table->time('overtime_in')->nullable();
            $table->time('overtime_out')->nullable();
            //SETTING THE PRIMARY KEYS
            // $table->primary(['employee_information_id', 'attendance_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_time_logs');
    }
};
