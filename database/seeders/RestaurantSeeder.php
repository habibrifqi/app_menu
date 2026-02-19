<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        Restaurant::truncate();

        Restaurant::create([
            'nama' => 'Warung Makan Sederhana',
            'alamat' => 'Jl. Malioboro No. 123, Yogyakarta, DIY 55271',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
