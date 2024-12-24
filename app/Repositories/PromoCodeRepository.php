<?php

namespace App\Repositories;

use App\Models\PromoCode;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;

// Kelas PromoCodeRepository mengimplementasikan antarmuka PromoCodeRepositoryInterface
class PromoCodeRepository implements PromoCodeRepositoryInterface
{
    // Fungsi untuk mendapatkan semua kode promo, diurutkan dari yang terbaru
    public function getAllPromoCode()
    {
        // Mengambil semua data kode promo, diurutkan berdasarkan waktu pembuatan terbaru menggunakan metode `latest()`
        return PromoCode::latest()->get();
    }

    // Fungsi untuk menemukan kode promo berdasarkan nilai kode tertentu
    public function findByCode(string $code)
    {
        // Melakukan pencarian kode promo dengan nilai kode yang sesuai
        return PromoCode::where('code', $code)->first(); // Mengambil hasil pertama yang ditemukan
    }
}
