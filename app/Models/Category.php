<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

// Kelas Category adalah representasi dari tabel 'categories' dalam database
class Category extends Model
{
    use HasFactory, SoftDeletes;

    // Daftar atribut yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'name',  // Nama kategori
        'slug',  // URL-friendly name
        'icon',  // Ikon untuk kategori
    ];

    // Relasi one-to-many antara Category dan Shoe
    public function shoes(): HasMany
    {
        // Sebuah kategori memiliki banyak sepatu
        return $this->hasMany(Shoe::class);
    }

    // Mutator untuk atribut 'name'
    public function setNameAttribute($value)
    {
        // Menyimpan nilai 'name' yang diberikan
        $this->attributes['name'] = $value;
        // Secara otomatis membuat slug dari nama dan menyimpannya di atribut 'slug'
        $this->attributes['slug'] = Str::slug($value);
    }
}
