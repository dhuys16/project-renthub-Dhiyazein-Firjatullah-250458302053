<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'total_price',
        'status',
        'payment_method',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_price' => 'decimal:0',
    ];

    // Relasi One-to-Many terbalik: Order dibuat oleh Customer (User).
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Relasi One-to-Many: Order memiliki banyak detail item sewa.
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}