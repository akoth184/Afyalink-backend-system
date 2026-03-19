<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceivingFacilityIdToReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Add missing referring_facility_id column
            $table->unsignedBigInteger('referring_facility_id')->nullable();
            $table->foreign('referring_facility_id')
                  ->references('id')
                  ->on('facilities')
                  ->onDelete('set null');

            // Add missing receiving_facility_id column
            $table->unsignedBigInteger('receiving_facility_id')->nullable();
            $table->foreign('receiving_facility_id')
                  ->references('id')
                  ->on('facilities')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropForeign(['referring_facility_id']);
            $table->dropForeign(['receiving_facility_id']);
            $table->dropColumn(['referring_facility_id', 'receiving_facility_id']);
        });
    }
}
