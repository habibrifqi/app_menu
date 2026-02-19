<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder harus dijalankan dengan urutan yang benar karena foreign key dependencies

        $this->call([
            RoleSeeder::class,          // 1. Roles dulu (untuk users)
            RestaurantSeeder::class,    // 2. Restaurant (untuk meja & kategori)
            MejaSeeder::class,          // 3. Meja (untuk orders)
            KategoriSeeder::class,      // 4. Kategori (untuk items)
            ItemSeeder::class,          // 5. Items (untuk order_details)
            UserSeeder::class,          // 6. Users (untuk orders)
        ]);

        $this->command->info('✅ Semua data berhasil di-seed!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - 4 Roles');
        $this->command->info('   - 1 Restaurant');
        $this->command->info('   - 10 Meja');
        $this->command->info('   - 10 Kategori');
        $this->command->info('   - 10 Items');
        $this->command->info('   - 10 Users');
    }
}
