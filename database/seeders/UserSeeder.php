<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           DB::statement('SET session_replication_role = replica');

        User::truncate();
        \App\Models\User::insert([
            [
                'name' => 'Waiterss',
                'email' => 'waiterss@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1
            ],
            [
                'name' => 'Chashier',
                'email' => 'chashier@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
            ],
            [
                'name' => 'Chef',
                'email' => 'chef@example.com',
                'password' => Hash::make('password'),
                'role_id' => 3,
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role_id' => 4,
            ],
        ]);

        DB::statement('SET session_replication_role = origin');

    }
}
