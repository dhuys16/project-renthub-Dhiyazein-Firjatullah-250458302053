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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            // Kunci Asing ke tabel products
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            $table->date('rent_start_date'); // DATE NOT NULL
            $table->date('rent_end_date'); // DATE NOT NULL
            $table->integer('quantity'); // dihapus
            $table->decimal('subtotal', 10, 2); // DECIMAL(10, 2) NOT NULL
            $table->timestamps();
            
            // Menambahkan kunci unik untuk mencegah duplikasi item dalam satu order (opsional)
            $table->unique(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
