<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

// Kelas Brand adalah representasi dari tabel 'brands' dalam database
class Brand extends Model
{
    use HasFactory, SoftDeletes;

    // Daftar atribut yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'name',  // Nama brand
        'slug',  // URL-friendly identifier untuk brand
        'logo',  // URL atau path ke logo brand
    ];

    // Mutator untuk atribut 'name'
    public function setNameAttribute($value)
    {
        // Menyimpan nilai 'name' yang diberikan
        $this->attributes['name'] = $value;
        // Secara otomatis membuat slug dari nama dan menyimpannya di atribut 'slug'
        $this->attributes['slug'] = Str::slug($value);
    }

    // Relasi one-to-many antara Brand dan Shoe
    public function shoes(): HasMany
    {
        // Sebuah brand memiliki banyak sepatu
        return $this->hasMany(Shoe::class);
    }
}
