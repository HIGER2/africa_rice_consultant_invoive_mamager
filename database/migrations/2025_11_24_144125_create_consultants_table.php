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
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->string('resno')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('email_cgiar')->unique()->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->string('town_city')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('position')->nullable();
            $table->string('resource_type')->nullable();
            $table->string('job_level')->nullable();
            $table->string('costc')->nullable();
            $table->string('department')->nullable();
            $table->string('dutypost')->nullable();
            $table->date('original_hire_date')->nullable();
            $table->integer('seniority')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('nationality_at_birth')->nullable();
            $table->string('marital_status')->nullable();
            $table->boolean('seconded_personnel')->default(false);
            $table->boolean('shared_working_arrangement')->default(false);
            $table->string('root')->nullable();
            $table->string('division')->nullable();
            $table->string('group')->nullable();
            $table->string('cg_unit')->nullable();
            $table->string('sub_unit')->nullable();
            $table->string('bg_level')->nullable();
            $table->string('cgiar_workforce_group')->nullable();
            $table->string('dutypost_classification')->nullable();
            $table->string('education_level')->nullable();
            $table->string('host_center')->nullable();
            $table->boolean('hosted_personnel')->default(false);
            $table->boolean('hosted_seconded_personnel')->default(false);
            $table->string('secondary_nationality')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('supervisors')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultants');
    }
};
