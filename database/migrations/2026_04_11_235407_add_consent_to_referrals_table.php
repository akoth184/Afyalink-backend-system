<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConsentToReferralsTable extends Migration
{
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->boolean('patient_consented')->default(false)->after('status');
            $table->timestamp('consented_at')->nullable()->after('patient_consented');
            $table->string('referral_status_detail')->default('pending_consent')->after('consented_at');
        });
    }

    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn(['patient_consented','consented_at','referral_status_detail']);
        });
    }
}
