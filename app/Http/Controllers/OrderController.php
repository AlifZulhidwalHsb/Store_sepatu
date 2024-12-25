<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\ProductTransaction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\StoreCheckBookingRequest;
use App\Http\Requests\StoreCustomerDataRequest;

class OrderController extends Controller
{
    // Menyimpan instance OrderService
    protected $orderService;

    // Konstruktor untuk menyuntikkan dependensi OrderService
    public function __construct(OrderService $orderService){
        $this->orderService = $orderService;
    }

    // Menyimpan pesanan ke session
    public function saveOrder(StoreOrderRequest $request, Shoe $shoe){
        // Validasi data yang dikirimkan melalui request
        $validated = $request->validated();

        // Menambahkan ID sepatu ke data yang telah divalidasi
        $validated['shoe_id'] = $shoe->id;

        // Memulai proses pesanan menggunakan layanan OrderService
        $this->orderService->beginOrder($validated);

        // Mengarahkan pengguna ke halaman pemesanan berdasarkan slug sepatu
        return redirect()->route('front.booking', $shoe->slug);
    }

    // Menampilkan halaman pemesanan
    public function booking()
    {
        // Mendapatkan detail pesanan dari OrderService
        $data = $this->orderService->getOrderDetails();
        //dd($data); // Debugging untuk melihat data
        return view('order.order', $data); // Menampilkan view 'order.order' dengan data pesanan
    }

    // Menampilkan halaman data pelanggan
    public function customerData()
    {
        // Mendapatkan detail pesanan dari OrderService
        $data = $this->orderService->getOrderDetails();
        //dd($data); // Debugging untuk melihat data
        return view('order.customer_data', $data); // Menampilkan view 'order.customer_data' dengan data
    }

    // Menyimpan data pelanggan
    public function saveCustomerData(StoreCustomerDataRequest $request)
    {
        // Validasi data pelanggan
        $validated = $request->validated();
        // Memperbarui data pelanggan menggunakan OrderService
        $this->orderService->updateCustomerData($validated);

        // Mengarahkan ke halaman pembayaran
        return redirect()->route('front.payment');
    }

    // Menampilkan halaman pembayaran
    public function payment()
    {
        // Mendapatkan detail pesanan dari OrderService
        $data = $this->orderService->getOrderDetails();
        //dd($data); // Debugging untuk melihat data
        // Menampilkan view 'order.payment' dengan data pesanan
        return view('order.payment', $data);
    }

    public function paymentConfirm(StorePaymentRequest $request)
    {
        try {
            // Validasi data pembayaran
            $validated = $request->validated();

            // Memproses konfirmasi pembayaran dan mendapatkan ID transaksi produk
            $productTransactionId = $this->orderService->paymentConfirm($validated);


            // Jika berhasil, arahkan ke halaman pesanan selesai
            return redirect()->route('front.order_finished', $productTransactionId);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, arahkan kembali ke halaman pembayaran
            return redirect()->route('front.payment')->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }
    }

    // Menampilkan informasi transaksi setelah pesanan selesai
    public function orderFinished(ProductTransaction $productTransaction)
    {
        // Debugging untuk melihat data transaksi produk
        //dd($productTransaction);

        return view('order.order_finished', compact('productTransaction'));
    }

    public function checkBooking()
    {
        return view('order.my_order');
    }

    public function checkBookingDetails(StoreCheckBookingRequest $request)
    {
        $validated = $request->validated();

        $orderDetails = $this->orderService->getMyOrderDetails($validated);

        if($orderDetails){
            return view('order.my_order_details', compact('orderDetails'));
        }

        return redirect()->route('front.check_booking')->withErrors('error', 'Transaksi tidak ditemukan');
    }
}
