<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        Role::truncate();

        Role::insert([
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kasir', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'waiter', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'dapur', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
