<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // ID unik kategori
            $table->string('name'); // Nama kategori, misal: "Makanan" atau "Gaji"
            $table->enum('type', ['income', 'expense']); // Jenis: 'income' (pemasukan) / 'expense' (pengeluaran)
            $table->timestamps(); // Mencatat tanggal dibuat & diubah
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
