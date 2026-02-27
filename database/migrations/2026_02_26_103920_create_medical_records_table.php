<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->date('visit_date');
            $table->text('chief_complaint');
            $table->text('history_of_present_illness')->nullable();
            $table->json('vital_signs')->nullable(); // BP, temp, pulse, weight, height
            $table->text('examination_findings')->nullable();
            $table->text('diagnosis');
            $table->text('treatment_plan');
            $table->text('medications')->nullable();
            $table->text('lab_results')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'finalized'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
}