<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

// Kelas Shoe adalah representasi dari tabel 'shoes' dalam database
class Shoe extends Model
{
    use HasFactory, SoftDeletes;

    // Daftar atribut yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'name',         // Nama sepatu
        'slug',         // URL-friendly identifier
        'thumbnail',    // Gambar thumbnail sepatu
        'about',        // Deskripsi sepatu
        'price',        // Harga sepatu
        'stock',        // Stok sepatu
        'is_popular',   // Status apakah sepatu populer atau tidak (boolean)
        'category_id',  // ID kategori sepatu
        'brand_id',     // ID merek sepatu
    ];

    // Mutator untuk atribut 'name'
    public function setNameAttribute($value)
    {
        // Menyimpan nilai 'name' yang diberikan
        $this->attributes['name'] = $value;
        // Secara otomatis membuat slug dari nama dan menyimpannya di atribut 'slug'
        $this->attributes['slug'] = Str::slug($value);
    }

    // Relasi many-to-one dengan model Brand
    public function brand(): BelongsTo
    {
        // Sebuah sepatu dimiliki oleh satu brand
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    // Relasi many-to-one dengan model Category
    public function category(): BelongsTo
    {
        // Sebuah sepatu termasuk dalam satu kategori
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relasi one-to-many dengan model ShoePhoto
    public function photos(): HasMany
    {
        // Sebuah sepatu memiliki banyak foto tambahan
        return $this->hasMany(ShoePhoto::class);
    }

    // Relasi one-to-many dengan model ShoeSize
    public function sizes(): HasMany
    {
        // Sebuah sepatu memiliki banyak ukuran
        return $this->hasMany(ShoeSize::class);
    }
}
