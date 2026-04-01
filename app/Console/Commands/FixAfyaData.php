<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixAfyaData extends Command
{
    protected $signature = 'afya:fix';
    protected $description = 'Fix AfyaLink facilities and referrals data';

    public function handle()
    {
        // Step 1 - Link hospital users to their facilities
        DB::table('users')->where('id',7)->update(['facility_id'=>10,'is_active'=>1]);
        DB::table('users')->where('id',8)->update(['facility_id'=>11,'is_active'=>1]);
        DB::table('users')->where('id',13)->update(['facility_id'=>12,'is_active'=>1]);
        $this->info('Hospital users linked to facilities');

        // Step 2 - Add more facilities if they don't exist
        $facilities = [
            ['hospital_id'=>4,'name'=>'Westlands Health Centre','type'=>'clinic','county'=>'Nairobi','phone'=>'0201234567','email'=>'westlands@health.ke','latitude'=>-1.2673,'longitude'=>36.8082,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['hospital_id'=>5,'name'=>'Mombasa Road Clinic','type'=>'clinic','county'=>'Nairobi','phone'=>'0207654321','email'=>'mombasa@clinic.ke','latitude'=>-1.3192,'longitude'=>36.8400,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['hospital_id'=>6,'name'=>'Aga Khan University Hospital','type'=>'hospital','county'=>'Nairobi','phone'=>'0203662000','email'=>'agakhan@hospital.ke','latitude'=>-1.2667,'longitude'=>36.8167,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['hospital_id'=>7,'name'=>'Nairobi Hospital','type'=>'hospital','county'=>'Nairobi','phone'=>'0203845600','email'=>'nairobihospital2@gmail.ke','latitude'=>-1.2921,'longitude'=>36.8219,'is_active'=>1,'created_at'=>now(),'updated_at'=>now()],
        ];
        foreach($facilities as $f) {
            if(!DB::table('facilities')->where('name',$f['name'])->exists()) {
                DB::table('facilities')->insert($f);
            }
        }
        $this->info('Additional facilities added');

        // Step 3 - Get facility IDs for referral update
        $knh = DB::table('facilities')->where('name','Kenyatta National Hospital')->first();
        $naiwest = DB::table('facilities')->where('name','Nairobi West Hospital')->first();
        $nairobi = DB::table('facilities')->where('name','Nairobi Hospital')->first();
        $westlands = DB::table('facilities')->where('name','Westlands Health Centre')->first();
        $mombasa = DB::table('facilities')->where('name','Mombasa Road Clinic')->first();
        $agakhan = DB::table('facilities')->where('name','Aga Khan University Hospital')->first();

        if(!$knh || !$naiwest || !$nairobi || !$westlands || !$mombasa || !$agakhan) {
            $this->error('Some facilities not found. Check database.');
            return;
        }

        // Step 4 - Update referrals with correct facility IDs
        DB::table('referrals')->where('id',1)->update([
            'referring_facility_id'=>$mombasa->id,
            'receiving_facility_id'=>$knh->id
        ]);
        DB::table('referrals')->where('id',2)->update([
            'referring_facility_id'=>$westlands->id,
            'receiving_facility_id'=>$knh->id
        ]);
        DB::table('referrals')->where('id',3)->update([
            'referring_facility_id'=>$knh->id,
            'receiving_facility_id'=>$nairobi->id
        ]);
        DB::table('referrals')->where('id',4)->update([
            'referring_facility_id'=>$knh->id,
            'receiving_facility_id'=>$agakhan->id
        ]);
        DB::table('referrals')->where('id',5)->update([
            'referring_facility_id'=>$knh->id,
            'receiving_facility_id'=>$naiwest->id
        ]);
        DB::table('referrals')->where('id',6)->update([
            'referring_facility_id'=>$knh->id,
            'receiving_facility_id'=>$nairobi->id
        ]);
        DB::table('referrals')->where('id',7)->update([
            'referring_facility_id'=>$knh->id,
            'receiving_facility_id'=>$naiwest->id
        ]);
        $this->info('Referrals updated with correct facility IDs');

        // Step 5 - Activate all hospital users
        DB::table('users')->where('role','hospital')->update(['is_active'=>1]);
        $this->info('All hospital users activated');

        // Step 6 - Verify
        $facilityCount = DB::table('facilities')->count();
        $referralCount = DB::table('referrals')->whereNotNull('receiving_facility_id')->count();
        $hospitalUsers = DB::table('users')->where('role','hospital')->whereNotNull('facility_id')->count();

        $this->info('=== VERIFICATION ===');
        $this->info('Facilities: ' . $facilityCount);
        $this->info('Referrals with facility IDs: ' . $referralCount);
        $this->info('Hospital users linked: ' . $hospitalUsers);
        $this->info('=== DONE ===');
    }
}
