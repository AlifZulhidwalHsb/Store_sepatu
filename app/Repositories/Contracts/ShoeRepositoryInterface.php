<?php

namespace App\Repositories\Contracts;

// Interface untuk repositori yang berhubungan dengan Shoe (Sepatu)
interface ShoeRepositoryInterface
{
    // Fungsi untuk mengambil sepatu populer, dengan batasan jumlah yang ditentukan
    public function getPopularShoes($limit);

    // Fungsi untuk mengambil semua sepatu terbaru
    public function getAllNewShoes();

    // Fungsi untuk mencari sepatu berdasarkan ID
    public function find($id);

    // Fungsi untuk mengambil harga sepatu berdasarkan ID sepatu
    public function getPrice($shoeId);
}
