<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CustomerProfile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@invotrack.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create test customer user
        $customer = User::create([
            'name' => 'John Customer',
            'email' => 'customer@shoeshop.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Create customer profile
        CustomerProfile::create([
            'user_id' => $customer->id,
            'phone' => '+60123456789',
            'address' => '123 Jalan Bukit Bintang, Kuala Lumpur, 50200',
            'city' => 'Kuala Lumpur',
            'state' => 'Wilayah Persekutuan',
            'postal_code' => '50200',
            'country' => 'Malaysia',
        ]);

        $this->command->info('Users created successfully!');
        $this->command->info('');
        $this->command->info('ADMIN LOGIN:');
        $this->command->info('Email: admin@invotrack.com');
        $this->command->info('Password: password');
        $this->command->info('URL: /login');
        $this->command->info('');
        $this->command->info('CUSTOMER LOGIN:');
        $this->command->info('Email: customer@shoeshop.com');
        $this->command->info('Password: password');
        $this->command->info('URL: /customer/login');
    }
}
