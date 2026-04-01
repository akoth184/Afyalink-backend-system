<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class CleanupDuplicateFacilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Find duplicate facilities (same name)
        $duplicates = DB::table('facilities as f1')
            ->join('facilities as f2', 'f1.name', '=', 'f2.name')
            ->where('f1.id', '>', 'f2.id')
            ->select('f1.id as duplicate_id', 'f2.id as keep_id', 'f1.name')
            ->get();

        // For each duplicate, update referrals to point to the kept facility, then delete duplicate
        foreach ($duplicates as $duplicate) {
            // Verify the keep_id facility exists before updating
            $keepFacility = DB::table('facilities')->where('id', $duplicate->keep_id)->first();

            if (!$keepFacility) {
                Log::warning("Keep facility ID {$duplicate->keep_id} does not exist, skipping duplicate ID {$duplicate->duplicate_id}");
                continue;
            }

            // Update referrals where this duplicate facility is the referring facility
            DB::table('referrals')
                ->where('referring_facility_id', $duplicate->duplicate_id)
                ->update(['referring_facility_id' => $duplicate->keep_id]);

            // Update referrals where this duplicate facility is the receiving facility
            DB::table('referrals')
                ->where('receiving_facility_id', $duplicate->duplicate_id)
                ->update(['receiving_facility_id' => $duplicate->keep_id]);

            // Update users to point to the kept facility
            DB::table('users')
                ->where('facility_id', $duplicate->duplicate_id)
                ->update(['facility_id' => $duplicate->keep_id]);

            // Delete the duplicate facility
            DB::table('facilities')
                ->where('id', $duplicate->duplicate_id)
                ->delete();

            // Log the cleanup
            Log::info("Cleaned up duplicate facility: ID {$duplicate->duplicate_id} ({$duplicate->name}), kept ID {$duplicate->keep_id}");
        }

        // Log total cleanup
        $totalCleaned = count($duplicates);
        if ($totalCleaned > 0) {
            Log::info("Total duplicate facilities cleaned up: {$totalCleaned}");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration cannot be reversed as we don't know which duplicates were deleted
        // The admin would need to manually re-create facilities if needed
    }
}
