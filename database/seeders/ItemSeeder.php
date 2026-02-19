<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET CONSTRAINTS ALL DEFERRED');
        
        Item::truncate();
        
        $items = [
            ['name' => 'Nasi Goreng Spesial', 'price' => 25000, 'kategori_id' => 10, 'image' => 'items/nasi-goreng.jpg'],
            ['name' => 'Mie Goreng Jawa', 'price' => 20000, 'kategori_id' => 1, 'image' => 'items/mie-goreng.jpg'],
            ['name' => 'Ayam Bakar Madu', 'price' => 35000, 'kategori_id' => 1, 'image' => 'items/ayam-bakar.jpg'],
            ['name' => 'Sate Ayam (10 tusuk)', 'price' => 30000, 'kategori_id' => 1, 'image' => 'items/sate-ayam.jpg'],
            ['name' => 'Gado-Gado Jakarta', 'price' => 18000, 'kategori_id' => 2, 'image' => 'items/gado-gado.jpg'],
            ['name' => 'Es Teh Manis', 'price' => 5000, 'kategori_id' => 3, 'image' => 'items/es-teh.jpg'],
            ['name' => 'Es Jeruk Peras', 'price' => 7000, 'kategori_id' => 3, 'image' => 'items/es-jeruk.jpg'],
            ['name' => 'Jus Alpukat', 'price' => 12000, 'kategori_id' => 8, 'image' => 'items/jus-alpukat.jpg'],
            ['name' => 'Es Campur Spesial', 'price' => 15000, 'kategori_id' => 5, 'image' => 'items/es-campur.jpg'],
            ['name' => 'Pisang Goreng Keju', 'price' => 10000, 'kategori_id' => 9, 'image' => 'items/pisang-goreng.jpg'],
        ];
        
        foreach ($items as $item) {
            Item::create([
                'name' => $item['name'],
                'price' => $item['price'],
                'kategori_id' => $item['kategori_id'],
                'image' => $item['image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
