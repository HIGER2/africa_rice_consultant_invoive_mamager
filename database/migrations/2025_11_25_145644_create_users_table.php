<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('uuid')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable()->unique();
            $table->enum('role', ['admin', 'finance', 'consultant', 'supervisor', 'hr'])->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->string('password')->nullable();
            $table->string('pin')->nullable();
            $table->dateTime('otp_expires_at')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('consultant_id')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->integer('employee_id')->unique()->nullable();
            // Foreign Keys
            $table->foreign('consultant_id')
                ->references('id')
                ->on('consultants')
                ->nullOnDelete();

            $table->foreign('supervisor_id')
                ->references('id')
                ->on('supervisors')
                ->nullOnDelete();
        });

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'status' => 'active',
            'pin' => '123456',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
