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
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kolom status produk
            // Defaultnya kita set ke 'available' agar produk lama tetap tampil
            $table->enum('status', ['available', 'maintenance', 'draft'])
                  ->default('available')
                  ->after('category'); // Pastikan posisi kolom setelah 'category'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Saat rollback, hapus kembali kolom status
            $table->dropColumn('status');
        });
    }
};