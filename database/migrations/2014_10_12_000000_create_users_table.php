<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');

            // Role
            $table->enum('role', ['admin', 'doctor', 'nurse', 'receptionist'])
                  ->default('doctor');

            // Medical Information
            $table->string('license_number')->nullable();
            $table->string('specialization')->nullable();

            // Facility Relationship
            $table->foreignId('facility_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}