<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',     // Kolom ENUM
        'phone_number',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // -----------------------------------------------------------------
    // RELATIONS (HUBUNGAN)
    // -----------------------------------------------------------------

    /**
     * Relasi 1-ke-N: User sebagai Vendor dapat memiliki banyak Product.
     */
    public function products(): HasMany
    {
        // Kunci asing di tabel products adalah 'vendor_id'
        return $this->hasMany(Product::class, 'vendor_id'); 
    }

    /**
     * Relasi 1-ke-N: User sebagai Customer dapat membuat banyak Order.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    /**
     * Relasi 1-ke-N: User sebagai Customer dapat membuat banyak Review.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    // -----------------------------------------------------------------
    // HELPER METHODS (Untuk pengecekan Role)
    // -----------------------------------------------------------------

    public function isAdmin(): bool { 
        return $this->role === 'admin'; 
    }
    public function isVendor(): bool { 
        return $this->role === 'vendor'; 
    }
    public function isCustomer(): bool { 
        return $this->role === 'customer'; 
    }

}