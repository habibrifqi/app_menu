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
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        User::truncate();

        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role_id' => 1],
            ['name' => 'Kasir 1', 'email' => 'kasir1@example.com', 'role_id' => 2],
            ['name' => 'Kasir 2', 'email' => 'kasir2@example.com', 'role_id' => 2],
            ['name' => 'Waiter 1', 'email' => 'waiter1@example.com', 'role_id' => 3],
            ['name' => 'Waiter 2', 'email' => 'waiter2@example.com', 'role_id' => 3],
            ['name' => 'Waiter 3', 'email' => 'waiter3@example.com', 'role_id' => 3],
            ['name' => 'Chef 1', 'email' => 'chef1@example.com', 'role_id' => 4],
            ['name' => 'Chef 2', 'email' => 'chef2@example.com', 'role_id' => 4],
            ['name' => 'Chef 3', 'email' => 'chef3@example.com', 'role_id' => 4],
            ['name' => 'Manager', 'email' => 'manager@example.com', 'role_id' => 1],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role_id' => $user['role_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
