<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat satu akun tunggal untuk pemilik aplikasi
        User::create([
            'name'     => 'Aditya',
            'email'    => 'aditya@dompetku.com',
            'password' => Hash::make('rahasia123'), // Anda bisa mengganti 'rahasia123' dengan password yang Anda inginkan
        ]);
    }
}
