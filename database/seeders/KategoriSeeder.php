<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET CONSTRAINTS ALL DEFERRED');
        
        Kategori::truncate();
        
        $kategoris = [
            ['nama' => 'Makanan Berat', 'urutan' => 1],
            ['nama' => 'Makanan Ringan', 'urutan' => 2],
            ['nama' => 'Minuman Dingin', 'urutan' => 3],
            ['nama' => 'Minuman Panas', 'urutan' => 4],
            ['nama' => 'Dessert', 'urutan' => 5],
            ['nama' => 'Paket Hemat', 'urutan' => 6],
            ['nama' => 'Menu Spesial', 'urutan' => 7],
            ['nama' => 'Jus & Smoothies', 'urutan' => 8],
            ['nama' => 'Gorengan', 'urutan' => 9],
            ['nama' => 'Aneka Nasi', 'urutan' => 10],
        ];
        
        foreach ($kategoris as $kategori) {
            Kategori::create([
                'restoran_id' => 1,
                'nama' => $kategori['nama'],
                'urutan' => $kategori['urutan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
