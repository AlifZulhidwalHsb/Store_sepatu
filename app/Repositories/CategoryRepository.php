<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

// Kelas CategoryRepository mengimplementasikan interface CategoryRepositoryInterface
class CategoryRepository implements CategoryRepositoryInterface
{
    // Fungsi untuk mengambil semua kategori, diurutkan berdasarkan yang terbaru
    public function getAllCategories()
    {
        // Mengambil semua kategori dari tabel 'categories' dengan urutan terbaru (berdasarkan waktu pembuatan)
        return Category::latest()->get();
    }
}
