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
        Schema::create('employee_cash_advances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_information_id');
            $table->char('amount', 11)->nullable();
            $table->char('purpose')->nullable();
            $table->date('cash_advanced_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_cash_advances');
    }
};
