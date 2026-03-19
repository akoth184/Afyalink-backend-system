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
        // Create sample facilities with coordinates (Nairobi area)
        $facilities = [
            [
                'name' => 'Kenyatta National Hospital',
                'type' => 'hospital',
                'mfl_code' => 'KNH001',
                'county' => 'Nairobi',
                'sub_county' => 'Starehe',
                'ward' => 'Nairobi Central',
                'phone' => '+254-020-272-6300',
                'email' => 'info@knh.or.ke',
                'latitude' => -1.3000,
                'longitude' => 36.8065,
                'working_hours' => json_encode([
                    'Monday' => '24 Hours',
                    'Tuesday' => '24 Hours',
                    'Wednesday' => '24 Hours',
                    'Thursday' => '24 Hours',
                    'Friday' => '24 Hours',
                    'Saturday' => '24 Hours',
                    'Sunday' => '24 Hours'
                ]),
            ],
            [
                'name' => 'Nairobi Hospital',
                'type' => 'hospital',
                'mfl_code' => 'NH001',
                'county' => 'Nairobi',
                'sub_county' => 'Starehe',
                'ward' => 'Nairobi Central',
                'phone' => '+254-020-284-5000',
                'email' => 'info@nairobihospital.org',
                'latitude' => -1.2833,
                'longitude' => 36.8167,
                'working_hours' => json_encode([
                    'Monday' => '24 Hours',
                    'Tuesday' => '24 Hours',
                    'Wednesday' => '24 Hours',
                    'Thursday' => '24 Hours',
                    'Friday' => '24 Hours',
                    'Saturday' => '24 Hours',
                    'Sunday' => '24 Hours'
                ]),
            ],
            [
                'name' => 'Aga Khan University Hospital',
                'type' => 'hospital',
                'mfl_code' => 'AKUH001',
                'county' => 'Nairobi',
                'sub_county' => 'Starehe',
                'ward' => 'Nairobi Central',
                'phone' => '+254-020-366-2000',
                'email' => 'info@akuahs.org',
                'latitude' => -1.2667,
                'longitude' => 36.8000,
                'working_hours' => json_encode([
                    'Monday' => '24 Hours',
                    'Tuesday' => '24 Hours',
                    'Wednesday' => '24 Hours',
                    'Thursday' => '24 Hours',
                    'Friday' => '24 Hours',
                    'Saturday' => '24 Hours',
                    'Sunday' => '24 Hours'
                ]),
            ],
            [
                'name' => 'MP Shah Hospital',
                'type' => 'hospital',
                'mfl_code' => 'MPS001',
                'county' => 'Nairobi',
                'sub_county' => 'Dagoretti',
                'ward' => 'Kenyatta Golf',
                'phone' => '+254-020-429-1000',
                'email' => 'info@mpshahhosp.org',
                'latitude' => -1.3100,
                'longitude' => 36.7850,
                'working_hours' => json_encode([
                    'Monday' => '24 Hours',
                    'Tuesday' => '24 Hours',
                    'Wednesday' => '24 Hours',
                    'Thursday' => '24 Hours',
                    'Friday' => '24 Hours',
                    'Saturday' => '24 Hours',
                    'Sunday' => '24 Hours'
                ]),
            ],
            [
                'name' => 'Westlands Health Centre',
                'type' => 'health_center',
                'mfl_code' => 'WHC001',
                'county' => 'Nairobi',
                'sub_county' => 'Westlands',
                'ward' => 'Kitisuru',
                'phone' => '+254-020-445-1234',
                'email' => 'info@westlandshealth.org',
                'latitude' => -1.2550,
                'longitude' => 36.7900,
                'working_hours' => json_encode([
                    'Monday' => '8:00 AM - 6:00 PM',
                    'Tuesday' => '8:00 AM - 6:00 PM',
                    'Wednesday' => '8:00 AM - 6:00 PM',
                    'Thursday' => '8:00 AM - 6:00 PM',
                    'Friday' => '8:00 AM - 6:00 PM',
                    'Saturday' => '9:00 AM - 2:00 PM',
                    'Sunday' => 'Closed'
                ]),
            ],
            [
                'name' => 'Mombasa Road Clinic',
                'type' => 'clinic',
                'mfl_code' => 'MSC001',
                'county' => 'Nairobi',
                'sub_county' => 'Makadara',
                'ward' => 'Mombasa Road',
                'phone' => '+254-020-667-1234',
                'email' => 'info@mombasardclinic.org',
                'latitude' => -1.3400,
                'longitude' => 36.8700,
                'working_hours' => json_encode([
                    'Monday' => '7:00 AM - 8:00 PM',
                    'Tuesday' => '7:00 AM - 8:00 PM',
                    'Wednesday' => '7:00 AM - 8:00 PM',
                    'Thursday' => '7:00 AM - 8:00 PM',
                    'Friday' => '7:00 AM - 8:00 PM',
                    'Saturday' => '8:00 AM - 5:00 PM',
                    'Sunday' => '9:00 AM - 1:00 PM'
                ]),
            ],
        ];

        foreach ($facilities as $facilityData) {
            Facility::create($facilityData);
        }

        // Get the first facility for user associations
        $facility = Facility::first();

        // Create admin user
        User::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'admin@afyalink.ke',
            'password' => Hash::make('Password@123'),
            'role' => 'admin',
            'facility_id' => $facility->id,
        ]);

        // Create a doctor
        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Wanjiku',
            'email' => 'doctor@afyalink.ke',
            'password' => Hash::make('Password@123'),
            'role' => 'doctor',
            'facility_id' => $facility->id,
            'license_number' => 'KMB-12345',
            'specialization' => 'General Medicine',
        ]);
    }
}
