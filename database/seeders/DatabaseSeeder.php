<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil Seeder yang sudah kita buat sebelumnya
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
        ]);
    }
}