<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ShoeRepositoryInterface;

// Kelas FrontService menyediakan layanan untuk kebutuhan logika halaman depan
class FrontService
{
    // Properti untuk menyimpan instance repository
    protected $categoryRepository;
    protected $shoeRepository;

    // Konstruktor untuk menyuntikkan dependensi (Dependency Injection)
    public function __construct(
        ShoeRepositoryInterface $shoeRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        // Menyimpan instance repository yang disuntikkan ke dalam properti
        $this->categoryRepository = $categoryRepository;
        $this->shoeRepository = $shoeRepository;
    }

    // Fungsi untuk mencari sepatu berdasarkan kata kunci
    public function searchShoes(string $keyword)
    {
        // Menggunakan metode `searchByName` pada ShoeRepository untuk mencari sepatu
        return $this->shoeRepository->searchByName($keyword);
    }

    // Fungsi untuk mendapatkan data yang diperlukan untuk halaman depan
    public function getFrontPageData()
    {
        // Mendapatkan semua kategori dari CategoryRepository
        $categories = $this->categoryRepository->getAllCategories();
        // Mendapatkan sepatu populer (dibatasi 4 item) dari ShoeRepository
        $popularShoes = $this->shoeRepository->getPopularShoes(4);
        // Mendapatkan semua sepatu terbaru dari ShoeRepository
        $newShoes = $this->shoeRepository->getAllNewShoes();

        // Mengembalikan data dalam bentuk array asosiatif menggunakan fungsi compact()
        return compact('categories', 'popularShoes', 'newShoes');
    }
}
