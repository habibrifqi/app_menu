<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET CONSTRAINTS ALL DEFERRED');
        
        Meja::truncate();
        
        $mejas = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $mejas[] = [
                'restoran_id' => 1,
                'nomor_meja' => 'A' . $i,
                'qr_code' => Str::random(32),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        Meja::insert($mejas);
    }
}
