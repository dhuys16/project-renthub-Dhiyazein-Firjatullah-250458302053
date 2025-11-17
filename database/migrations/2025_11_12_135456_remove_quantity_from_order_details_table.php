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
        Schema::table('order_details', function (Blueprint $table) {
            // Hapus kolom 'quantity'
            $table->dropColumn('quantity');
            
            // Opsional: Jika Anda juga memiliki kolom 'subtotal' yang bergantung pada quantity,
            // Anda mungkin perlu meninjau ulang bagaimana 'subtotal' dihitung di Controller.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Saat rollback, tambahkan kembali kolom 'quantity'
            // Gunakan tipe data yang sama seperti sebelumnya (asumsi integer)
            $table->unsignedInteger('quantity')->default(1)->after('rent_end_date');
        });
    }
};