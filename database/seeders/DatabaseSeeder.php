<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    
    {
        // 🏢 ১. মূল বেকারি কোম্পানি অনবোর্ড করা হলো (First Company Node)
        $company = Company::create([
            'company_name' => 'Royel Bakery',
            'company_slug' => 'royel-bakery',
            'mobile'       => '+919876543210',
            'address'      => 'Bankura, West Bengal',
            'status'       => 'active',
        ]);

        // 👥 ২. এই একই কোম্পানির আন্ডারে মাল্টিপল ওনার (Multiple Owners) যোগ করা হচ্ছে
        
        // প্রথম ওনার (Primary Partner)
        Owner::create([
            'company_id' => $company->id,
            'name'       => 'Subrata Das',
            'email'      => 'owner1@bakery.com',
            'mobile'     => '+919876543210',
            'password'   => Hash::make('SUBR@1998'), // Formula: [NAME_FIRST_4_CAPS] + @ + [DOB_YEAR]
            'status'     => 'active',
        ]);

        // দ্বিতীয় ওনার (Co-owner / Business Partner)
        Owner::create([
            'company_id' => $company->id,
            'name'       => 'Amit Dev',
            'email'      => 'owner2@bakery.com',
            'mobile'     => '+919876543215',
            'password'   => Hash::make('AMIT@1996'),
            'status'     => 'active',
        ]);

        // 👔 ৩. টেস্ট করার জন্য এই কোম্পানির আন্ডারে একজন জেনারেল ম্যানেজার (User টেবিলে) অ্যাড করা হলো
        User::create([
            'company_id'   => $company->id,
            'company_name' => $company->company_name,
            'company_slug' => $company->company_slug,
            'name'         => 'Rahul Kisku',
            'email'        => 'manager@bakery.com',
            'mobile'       => '+919876543211',
            'dob'          => '2001-08-20',
            'role'         => 'manager', // System level role
            'department'   => 'Management & Accounts',
            'category'     => 'Manager', // Job Category
            'password'     => Hash::make('RAHU@2001'),
        ]);

        $this->call([
            ProductSeeder::class,
        ]);
    }
}