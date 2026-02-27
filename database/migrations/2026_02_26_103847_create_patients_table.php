<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->string('patient_number')->unique();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();

            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);

            $table->string('national_id')->nullable()->unique();
            $table->string('nhif_number')->nullable();

            $table->string('phone');
            $table->string('email')->nullable();

            $table->text('address')->nullable();
            $table->string('county')->nullable();

            $table->string('next_of_kin_name')->nullable();
            $table->string('next_of_kin_phone')->nullable();
            $table->string('next_of_kin_relationship')->nullable();

            $table->enum('blood_group', [
                'A+','A-','B+','B-','AB+','AB-','O+','O-','unknown'
            ])->default('unknown');

            $table->text('allergies')->nullable();
            $table->text('chronic_conditions')->nullable();

            // ✅ Registered by (REQUIRED)
            $table->foreignId('registered_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // ✅ Facility (REQUIRED but safer handling)
            $table->foreignId('facility_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
}