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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Kunci Asing ke tabel products
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            // Kunci Asing ke tabel users (sebagai customer/reviewer)
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            
            $table->integer('rating'); // INT NOT NULL (Asumsi skala 1-5)
            $table->text('comment')->nullable(); // TEXT NULL
            $table->timestamps();
            
            // Menambahkan kunci unik untuk memastikan satu pengguna hanya memberi satu review per produk (opsional)
            $table->unique(['product_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
