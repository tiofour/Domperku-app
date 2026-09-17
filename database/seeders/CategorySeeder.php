<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategori Pemasukan Awal
        Category::create(['name' => 'Gaji Utama', 'type' => 'income']);
        Category::create(['name' => 'Bonus/Freelance', 'type' => 'income']);

        // Kategori Pengeluaran Awal
        Category::create(['name' => 'Makanan & Minuman', 'type' => 'expense']);
        Category::create(['name' => 'Transportasi', 'type' => 'expense']);
        Category::create(['name' => 'Belanja & Hiburan', 'type' => 'expense']);
        Category::create(['name' => 'Tagihan & Bulanan', 'type' => 'expense']);
    }
}
