<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReasonToReferralsTable extends Migration
{
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->text('reason')->nullable()->after('receiving_facility_id');
            $table->text('notes')->nullable()->after('reason');
            $table->unsignedBigInteger('referred_by')->nullable()->after('notes');
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['reason', 'notes', 'referred_by']);
        });
    }
}
