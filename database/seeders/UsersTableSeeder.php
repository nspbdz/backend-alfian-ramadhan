<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Merchant 1',
            'email' => 'merchant1@example.com',
            'password' => Hash::make('password'),
            'role' => 'merchant',
        ]);

        User::create([
            'name' => 'Merchant 2',
            'email' => 'merchant2@example.com',
            'password' => Hash::make('password'),
            'role' => 'merchant',
        ]);

        // Customer
        User::create([
            'name' => 'Customer 1',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Customer 2',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}