<?php

namespace App\Services;

use App\Models\ProductTransaction;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;
use App\Repositories\Contracts\ShoeRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Kelas OrderService menangani logika bisnis yang berhubungan dengan pemesanan
class OrderService
{
    // Mendeklarasikan properti repositori yang dibutuhkan
    protected $categoryRepository;
    protected $promoCodeRepository;
    protected $orderRepository;
    protected $shoeRepository;

    // Konstruktor untuk menginisialisasi repositori-repositori yang dibutuhkan
    public function __construct(
        PromoCodeRepositoryInterface $promoCodeRepository,
        CategoryRepositoryInterface $categoryRepository,
        OrderRepositoryInterface $orderRepository,
        ShoeRepositoryInterface $shoeRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->promoCodeRepository = $promoCodeRepository;
        $this->orderRepository = $orderRepository;
        $this->shoeRepository = $shoeRepository;
    }

    // Fungsi untuk memulai pemesanan, menyimpan data pemesanan sementara dalam sesi
    public function beginOrder(array $data)
    {
        $orderData = [
            'shoe_size' => $data['shoe_size'],
            'size_id' => $data['size_id'],
            'shoe_id' => $data['shoe_id'],
        ];

        // Menyimpan data pesanan ke sesi
        $this->orderRepository->saveToSession($orderData);
    }

    public function getMyOrderDetails(array $validated){
        return $this->orderRepository->findByTrxIdAndPhoneNumber($validated['booking_trx_id'],
        $validated['phone']);
    }

    // Fungsi untuk mendapatkan detail pesanan yang sudah disimpan di sesi
    public function getOrderDetails()
    {
        // Mengambil data pesanan dari sesi
        $orderData = $this->orderRepository->getOrderDataFromSession();
        $shoe = $this->shoeRepository->find($orderData['shoe_id']);

        // Menghitung subtotal, pajak, dan total harga
        $quantity = isset($orderData['quantity']) ? $orderData['quantity'] : 1;
        $subTotalAmount = $shoe->price * $quantity;

        $taxRate = 0.11; // Pajak 11%
        $totalTax = $subTotalAmount * $taxRate;

        $grandTotalAmount = $subTotalAmount + $totalTax;

        // Menyimpan data yang dihitung ke dalam array orderData
        $orderData['sub_total_amount'] = $subTotalAmount;
        $orderData['total_tax'] = $totalTax;
        $orderData['grand_total_amount'] = $grandTotalAmount;

        // Mengembalikan data pesanan dan sepatu
        return compact('orderData', 'shoe');
    }

    // Fungsi untuk menerapkan kode promo pada subtotal dan menghitung diskon
    public function applyPromoCode(string $code, int $subTotalAmount)
    {
        // Mencari kode promo berdasarkan kode yang diberikan
        $promo = $this->promoCodeRepository->findByCode($code);

        if ($promo) {
            // Jika kode promo valid, menghitung diskon dan total setelah diskon
            $discount = $promo->discount_amount;
            $grandTotalAmount = $subTotalAmount - $discount;
            $promoCodeId = $promo->id;

            // Mengembalikan informasi diskon dan total setelah diskon
            return [
                'discount' => $discount,
                'grandTotalAmount' => $grandTotalAmount,
                'promoCodeId' => $promoCodeId,
            ];
        }

        // Jika kode promo tidak ditemukan, mengembalikan pesan error
        return ['error' => 'Kode promo tidak tersedia!'];
    }

    // Fungsi untuk menyimpan transaksi pemesanan ke sesi
    public function saveBookingTransaction(array $data): void
    {
        // Menyimpan data transaksi ke sesi
        $this->orderRepository->saveToSession($data);
    }

    // Fungsi untuk memperbarui data pelanggan di sesi
    public function updateCustomerData(array $data): void
    {
        // Memperbarui data pesanan yang ada di sesi
        $this->orderRepository->updateSessionData($data);
    }

    public function paymentConfirm(array $validated)
    {
        $productTransactionId = null;
        $orderData = $this->orderRepository->getOrderDataFromSession();

        try {
            // Menggunakan transaksi database untuk memastikan konsistensi data
            DB::transaction(function () use ($validated, &$productTransactionId, $orderData) {
                // Menyimpan bukti pembayaran jika ada
                if (isset($validated['proof'])) {
                    $proofPath = $validated['proof']->store('proofs', 'public');
                    //buktiTransfer.png
                    $validated['proof'] = $proofPath;
                }

                // Menyusun data transaksi lengkap dengan informasi pelanggan dan pesanan
                $validated['name'] = $orderData['name'];
                $validated['email'] = $orderData['email'];
                $validated['phone'] = $orderData['phone'];
                $validated['address'] = $orderData['address'];
                $validated['post_code'] = $orderData['post_code'];
                $validated['city'] = $orderData['city'];
                $validated['quantity'] = $orderData['quantity'];
                $validated['sub_total_amount'] = $orderData['sub_total_amount'];
                $validated['grand_total_amount'] = $orderData['grand_total_amount'];
                $validated['discount_amount'] = $orderData['total_discount_amount'];
                $validated['promo_code_id'] = $orderData['promo_code_id'];
                $validated['shoe_id'] = $orderData['shoe_id'];
                $validated['shoe_size'] = $orderData['size_id'];

                $validated['is_paid'] = false; // Status pembayaran belum dilakukan

                $validated['booking_trx_id'] = ProductTransaction::generateUniqueTrxId(); // Menghasilkan ID transaksi unik

                // Membuat transaksi baru dan mendapatkan ID transaksi
                $newTransaction = $this->orderRepository->createTransaction($validated);


                $productTransactionId = $newTransaction->id;

                $this->orderRepository->clearSession(); // Membersihkan data pesanan di sesi
            });
        } catch (\Exception $e) {
            // Menangani error dan mencatatnya ke log
            Log::error('Error in payment confirmation: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return null;
        }

        // Mengembalikan ID transaksi yang berhasil dibuat
        return $productTransactionId;
    }


}
