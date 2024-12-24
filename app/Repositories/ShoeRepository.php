<?php

namespace App\Repositories;

use App\Models\Shoe;
use App\Repositories\Contracts\ShoeRepositoryInterface;

// Kelas ShoeRepository mengimplementasikan antarmuka ShoeRepositoryInterface
class ShoeRepository implements ShoeRepositoryInterface
{
    // Fungsi untuk mendapatkan daftar sepatu populer, dibatasi jumlahnya = 4 default
    public function getPopularShoes($limit = 4)
    {
        // Mengambil sepatu yang memiliki atribut 'is_popular' bernilai true, dibatasi oleh parameter $limit
        return Shoe::where('is_popular', true)->take($limit)->get();
    }

    // Fungsi untuk mencari sepatu berdasarkan nama (keyword)
    public function searchByname(string $keyword)
    {
        // Melakukan pencarian sepatu dengan nama yang mengandung kata kunci tertentu (menggunakan 'like')
        return Shoe::where('name', 'like', '%' . $keyword . '%')->get();
    }


    // Fungsi untuk mendapatkan semua sepatu baru (diurutkan berdasarkan waktu pembuatan terbaru)
    public function getAllNewShoes()
    {
        // Mengambil semua data sepatu dengan urutan terbaru menggunakan metode `latest()`
        return Shoe::latest()->get();
    }

    // Fungsi untuk menemukan sepatu berdasarkan ID
    public function find($id)
    {
        // Menggunakan metode find() pada model untuk menemukan data sepatu berdasarkan ID
        return Shoe::find($id);
    }

    // Fungsi untuk mendapatkan harga sepatu berdasarkan ID
    public function getPrice($shoeId)
    {
        // Menggunakan fungsi `find` untuk mencari data sepatu berdasarkan ID
        $shoe = $this->find($shoeId);
        // Jika sepatu ditemukan, kembalikan harga; jika tidak, kembalikan 0
        return $shoe ? $shoe->price : 0;
    }
}
