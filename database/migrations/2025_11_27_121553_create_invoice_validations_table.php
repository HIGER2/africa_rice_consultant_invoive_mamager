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
        Schema::create('invoice_validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->boolean('supervisor_period_validated')->default(false);
            $table->boolean('supervisor_report_validated')->default(false);
            $table->boolean('supervisor_budget_validated')->default(false);

            $table->boolean('hr_period_validated')->default(false);
            $table->boolean('hr_report_validated')->default(false);
            $table->boolean('hr_clearance_validated')->default(false);

            $table->boolean('finance_period_validated')->default(false);
            $table->boolean('finance_report_validated')->default(false);
            // $table->boolean('finance_budget_validated')->default(false);
            $table->boolean('finance_clearance_validated')->default(false);
            $table->boolean('finance_contract_validated')->default(false);
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_validations');
    }
};
