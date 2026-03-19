<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPatientRoleToUsersTable extends Migration
{
    public function up()
    {
        // MySQL requires altering enum columns through raw SQL
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'doctor', 'nurse', 'receptionist', 'patient', 'hospital', 'facility') DEFAULT 'patient'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'doctor', 'nurse', 'receptionist') DEFAULT 'doctor'");
    }
}
