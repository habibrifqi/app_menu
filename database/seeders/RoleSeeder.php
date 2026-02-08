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
        DB::statement('SET session_replication_role = replica');

        Role::truncate();
        \App\Models\Role::insert([
            ['name' => 'waiterss'],
            ['name' => 'chashier'],
            ['name' => 'chef'],
            ['name' => 'manager'],
        ]);

        DB::statement('SET session_replication_role = origin');

    }
}
