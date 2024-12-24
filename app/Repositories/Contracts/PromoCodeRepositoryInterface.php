<?php

namespace App\Repositories\Contracts;

// Interface untuk repositori yang berhubungan dengan PromoCode (Kode Promo)
interface PromoCodeRepositoryInterface
{
    // Fungsi untuk mengambil semua kode promo yang tersedia
    public function getAllPromoCode();

    // Fungsi untuk mencari kode promo berdasarkan kode yang diberikan
    public function findByCode(string $code);
}
