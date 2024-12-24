<?php

namespace App\Repositories\Contracts;

// Interface untuk repositori yang berhubungan dengan Order (Pesanan)
interface OrderRepositoryInterface
{
    // Fungsi untuk membuat transaksi baru dengan data yang diberikan dalam array
    public function createTransaction(array $data);

    // Fungsi untuk mencari transaksi berdasarkan ID transaksi dan nomor telepon pelanggan
    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber);

    // Fungsi untuk menyimpan data pesanan ke dalam sesi (session)
    public function saveToSession(array $data);

    // Fungsi untuk memperbarui data pesanan yang ada di dalam sesi
    public function updateSessionData(array $data);

    // Fungsi untuk mengambil data pesanan yang ada di dalam sesi
    public function getOrderDataFromSession();
}
