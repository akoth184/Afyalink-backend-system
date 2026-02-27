<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create a sample facility
        $facility = Facility::create([
            'name' => 'Kenyatta National Hospital',
            'type' => 'hospital',
            'mfl_code' => 'KNH001',
            'county' => 'Nairobi',
            'sub_county' => 'Starehe',
            'phone' => '+254-020-272-6300',
            'email' => 'info@knh.or.ke',
        ]);

        // Create admin user
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@afyalink.ke',
            'password' => Hash::make('Password@123'),
            'role' => 'admin',
            'facility_id' => $facility->id,
        ]);

        // Create a doctor
        User::create([
            'name' => 'Dr. Jane Wanjiku',
            'email' => 'doctor@afyalink.ke',
            'password' => Hash::make('Password@123'),
            'role' => 'doctor',
            'facility_id' => $facility->id,
            'license_number' => 'KMB-12345',
            'specialization' => 'General Medicine',
        ]);
    }
}