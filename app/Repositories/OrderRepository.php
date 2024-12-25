<?php

namespace App\Repositories;

use App\Models\ProductTransaction;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Session;

// Kelas OrderRepository mengimplementasikan antarmuka OrderRepositoryInterface
class OrderRepository implements OrderRepositoryInterface
{
    // Fungsi untuk membuat transaksi baru dalam tabel ProductTransaction
    public function createTransaction(array $data)
    {
        // Menggunakan metode create() pada model untuk menyimpan data transaksi pada ProductTransaction
        return ProductTransaction::create($data);
    }

    // Fungsi untuk menemukan transaksi berdasarkan ID transaksi (booking_trx_id) dan nomor telepon
    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber)
    {
        // Melakukan pencarian pada tabel ProductTransaction dengan kondisi ID transaksi dan nomor telepon
        return ProductTransaction::where('booking_trx_id', $bookingTrxId)
                                    ->where('phone', $phoneNumber)
                                    ->first(); // Mengambil hasil pertama
    }

    // Fungsi untuk menyimpan data pesanan ke dalam sesi (session)
    public function saveToSession(array $data)
    {
        // Menggunakan facade Session untuk menyimpan data pesanan dengan kunci 'orderData'
        Session::put('orderData', $data);
    }

    // Fungsi untuk mendapatkan data pesanan dari sesi
    public function getOrderDataFromSession()
    {
        // Mengambil data dari sesi dengan kunci 'orderData', jika tidak ada data, akan mengembalikan array kosong
        return session('orderData', []);
    }

    // Fungsi untuk memperbarui data pesanan dalam sesi
    public function updateSessionData(array $data)
    {
        // Mengambil data pesanan yang sudah ada di sesi
        $orderData = session('orderData', []);
        // Menggabungkan data lama dengan data baru
        $orderData = array_merge($orderData, $data);
        // Menyimpan kembali data pesanan yang sudah diperbarui ke dalam sesi
        session(['orderData' => $orderData]);
    }

    // Fungsi untuk menghapus data pesanan dalam sesi
    public function clearSession()
    {
        // Menghapus data pesanan dari sesi
        Session::forget('orderData');
    }
}
