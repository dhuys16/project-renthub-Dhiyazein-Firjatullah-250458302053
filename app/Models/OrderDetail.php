<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'rent_start_date',
        'rent_end_date',
        'subtotal',
    ];

    protected $casts = [
        'rent_start_date' => 'date',
        'rent_end_date' => 'date',
        'subtotal' => 'decimal:0',
    ];

    // Relasi Many-to-One: Detail Order merujuk ke Order utama.
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi Many-to-One: Detail Order merujuk ke Produk yang disewa.
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}