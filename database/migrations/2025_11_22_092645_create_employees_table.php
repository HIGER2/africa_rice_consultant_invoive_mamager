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
        Schema::create('employees', function (Blueprint $table) {
            // Primary key
            // $table->id();
            // Requested columns (kept names as provided)
            $table->integer('employeeId')->unique();
            $table->string('role')->default('staff');
            $table->string('email')->nullable();
            $table->integer('supervisorId')->nullable();
            $table->string('jobTitle');
            $table->string('personalEmail')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('password');
            $table->string('category')->nullable();
            $table->string('grade')->nullable();
            $table->string('bgLevel')->nullable();
            $table->string('matricule')->nullable();
            $table->dateTime('deletedAt', 3)->nullable();
            $table->string('secretKey')->nullable();
            $table->string('organization')->nullable();
            $table->string('division')->nullable();
            $table->string('base_station')->nullable();
            $table->uuid('uuid')->nullable();
            $table->string('personal_email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('nationality_1')->nullable();
            $table->string('nationality_2')->nullable();
            $table->string('indentity_number')->nullable();
            $table->string('social_security_number')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('country_of_residence')->nullable();
            $table->string('town_of_residence')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->integer('number_of_children')->default(0);
            $table->enum('family_living_with_staff', ['yes', 'no'])->nullable();
            $table->string('family_residence_location')->nullable();
            $table->enum('spouse_works', ['yes', 'no'])->nullable();
            $table->text('spouse_workplace')->nullable();
            $table->string('unit_program')->nullable();
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
