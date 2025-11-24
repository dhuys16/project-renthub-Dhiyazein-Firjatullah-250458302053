<?php

namespace App\Models;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang dapat diisi secara massal (mass assignable).
     * Pastikan semua kolom non-otomatis ada di sini.
     */
    protected $fillable = [
        'name',
        'description',
        'picture',
        'price_per_day',
        'category', // Kolom ENUM yang telah disepakati
        'vendor_id', // Kunci asing ke tabel users
    ];

    /**
     * Konversi tipe data untuk kolom tertentu (opsional namun baik).
     */
    protected $casts = [
        'price_per_day' => 'decimal:0', // Menyimpan harga sebagai desimal
        'vendor_id' => 'integer',
    ];

    // -----------------------------------------------------------------
    // RELATIONS (HUBUNGAN)
    // -----------------------------------------------------------------

    /**
     * Relasi One-to-Many terbalik (Satu produk dimiliki oleh satu Vendor).
     */
    public function vendor(): BelongsTo
    {
        // Terhubung ke Model User, menggunakan vendor_id sebagai kunci asing
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Relasi One-to-Many (Satu produk dapat muncul di banyak order_details).
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
    
    /**
     * Relasi One-to-Many (Satu produk memiliki banyak review).
     */
   public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    
    // [TAMBAH BAGIAN INI: Accessor untuk Rata-rata Rating]
    /**
     * Mendapatkan rata-rata rating dari ulasan terkait (Attribute Accessor).
     * Mengutamakan atribut 'reviews_avg_rating' yang dimuat oleh Controller.
     */
    public function getAverageRatingAttribute(): float
    {
        // Cek apakah nilai agregat sudah dimuat (untuk menghindari N+1)
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            // Gunakan nilai yang sudah dimuat
            return (float) $this->attributes['reviews_avg_rating'];
        }

        // Jika belum dimuat (misal dipanggil di tempat lain), hitung secara langsung
        // Tapi disarankan selalu memuatnya di controller untuk daftar produk
        return $this->reviews()->avg('rating') ?? 0.0;
    }
    // -----------------------------------------------------------------
    // HELPER METHOD (Opsional, untuk tampilan yang lebih bersih)
    // -----------------------------------------------------------------

    /**
     * Mengembalikan harga dengan format mata uang (misal: Rp 150.000).
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_day, 0, ',', '.');
    }
}