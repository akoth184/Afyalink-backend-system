<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransportStatusToReferralsTable extends Migration
{
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->string('transport_status')->default('pending')->after('referral_status_detail');
        });
    }

    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn('transport_status');
        });
    }
}
