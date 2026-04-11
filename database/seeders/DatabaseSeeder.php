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
                'name' => 'Nairobi West Hospital',
                'type' => 'hospital',
                'mfl_code' => 'NWH001',
                'county' => 'Nairobi',
                'sub_county' => 'Westlands',
                'ward' => 'Kitisuru',
                'phone' => '+254-020-445-1234',
                'email' => 'info@nairobiwest.org',
                'latitude' => -1.2550,
                'longitude' => 36.7900,
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
            'is_active' => true,
        ]);

        // Create doctors for each facility
        $facilities = Facility::all();
        
        $doctors = [
            ['first_name' => 'John', 'last_name' => 'Otieno', 'email' => 'johnson@doctor.ke', 'specialization' => 'General Practitioner'],
            ['first_name' => 'Sarah', 'last_name' => 'Kemunto', 'email' => 'sarah@doctor.ke', 'specialization' => 'Pediatrics'],
            ['first_name' => 'David', 'last_name' => 'Mwangi', 'email' => 'david@doctor.ke', 'specialization' => 'Internal Medicine'],
            ['first_name' => 'Grace', 'last_name' => 'Akinyi', 'email' => 'grace@doctor.ke', 'specialization' => 'Surgery'],
            ['first_name' => 'Peter', 'last_name' => 'Kariuki', 'email' => 'peter@doctor.ke', 'specialization' => 'Cardiology'],
            ['first_name' => 'Mary', 'last_name' => 'Wambui', 'email' => 'mary@doctor.ke', 'specialization' => 'Obstetrics & Gynecology'],
            ['first_name' => 'James', 'last_name' => 'Ouma', 'email' => 'james@doctor.ke', 'specialization' => 'General Practitioner'],
            ['first_name' => 'Faith', 'last_name' => 'Njoroge', 'email' => 'faith@doctor.ke', 'specialization' => 'Dermatology'],
        ];

        $licenseNumbers = ['KMB-54321', 'KMB-54322', 'KMB-54323', 'KMB-54324', 'KMB-54325', 'KMB-54326', 'KMB-54327', 'KMB-54328'];
        
        $i = 0;
        foreach ($facilities as $fac) {
            // Create 2 doctors per facility
            for ($j = 0; $j < 2; $j++) {
                if (isset($doctors[$i])) {
                    User::create([
                        'first_name' => $doctors[$i]['first_name'],
                        'last_name' => $doctors[$i]['last_name'],
                        'email' => $doctors[$i]['email'],
                        'password' => Hash::make('Password@123'),
                        'role' => 'doctor',
                        'facility_id' => $fac->id,
                        'license_number' => $licenseNumbers[$i] ?? 'KMB-' . rand(10000, 99999),
                        'specialization' => $doctors[$i]['specialization'],
                        'is_active' => true,
                    ]);
                    $i++;
                }
            }
        }
    }
}
