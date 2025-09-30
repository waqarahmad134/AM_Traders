<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'amtraders@gmail.com',
            'password' => Hash::make('9152'),
            'status' => 'active',
            'contact' => '03001234567',
            'address' => 'Lahore, Pakistan',
        ]);

        // $customers = [
        //     [
        //         'name' => 'Chaudhry Muhammad Akram Teaching Hospital',
        //         'customer_id' => 'CMA001',
        //         'address' => 'Kot Araian Raiwand Road, Lahore',
        //         'contact' => '0302-4509647',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Bahria International Hospital',
        //         'customer_id' => 'BIH002',
        //         'address' => 'Takbeer Block Sector B Bahria Town, Lahore',
        //         'contact' => '0321-4117805',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'National Hospital & Medical Center',
        //         'customer_id' => 'NH003',
        //         'address' => 'Sector L DHA Phase-1, Lahore',
        //         'contact' => '0307-8464310',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Saleem Memorial Hospital',
        //         'customer_id' => 'SM001',
        //         'address' => 'Canal Rd opposite Green Forts 2, Satluj Block, Lahore',
        //         'contact' => '0300-4201975',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Green+Pharmacy (Bahria Town)',
        //         'customer_id' => 'GP001',
        //         'address' => 'Inside Bahria Town Hospital Sector B Bahria Town, Lahore',
        //         'contact' => '0300-9663767',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Bahria Orchard Hospital',
        //         'customer_id' => 'BOH001',
        //         'address' => 'Bahria Orchard Central District Bahria Orchard, Lahore',
        //         'contact' => '0333-7377498',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Green+Pharmacy (Bahria Orchard)',
        //         'customer_id' => 'GP002',
        //         'address' => 'Inside Bahria Orchard Hospital Bahria Orchard, Lahore',
        //         'contact' => '0333-7377498',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Hayyat Pharmacy',
        //         'customer_id' => 'CMA002',
        //         'address' => 'Inside Chaudhry Muhammad Akram Teaching Hospital',
        //         'contact' => '0344-7352158',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Ocean Traders',
        //         'customer_id' => 'OT001',
        //         'address' => 'New Town Near Baber Shadi Hall Multan Road, Lahore',
        //         'contact' => '0305-1514777',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'AM Traders',
        //         'customer_id' => 'AM001',
        //         'address' => '66 Kausar Block Awan Town Multan Road , Lahore',
        //         'contact' => '0334-3538725',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Bajwa Hospital',
        //         'customer_id' => 'BJH001',
        //         'address' => '356 Hafeez Road, Kashmir Block Allama Iqbal Town, Lahore',
        //         'contact' => '0334-9776073',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Dr. Talat Shah',
        //         'customer_id' => 'TS001',
        //         'address' => 'Doctor Hospital Block G1 Phase 1 Johar Town, Lahore',
        //         'contact' => '0321-8434108',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Mr. Muhammad Ramzan',
        //         'customer_id' => 'RAM001',
        //         'address' => 'Doctor Hospital Block G1 Phase 1 Johar Town, Lahore',
        //         'contact' => '0345-1211321',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        //     [
        //         'name' => 'Masood Hospital',
        //         'customer_id' => 'MH001',
        //         'address' => '99, Garden Block Garden Town, Lahore',
        //         'contact' => '0304-9042912',
        //         'ntn_strn' => '',
        //         'license_no' => '-',
        //     ],
        // ];
        
        // // Insert users
        // foreach ($customers as $index => $data) {
        //     User::create([
        //         'name' => $data['name'],
        //         'customer_id' => $data['customer_id'],
        //         'address' => $data['address'],
        //         'contact' => $data['contact'],
        //         'ntn_strn' => $data['ntn_strn'],
        //         'license_no' => $data['license_no'],
        //         'email' => strtolower(str_replace(' ', '_', $data['customer_id'])) . '@demo.com',
        //         'usertype' => 'customer',
        //         'password' => Hash::make('password'), 
        //     ]);
        // }
        
        // 10 Suppliers
        // User::factory(10)->create([
        //     'usertype' => 'supplier',
        // ]);
    }
}
