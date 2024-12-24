<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderController;

// Route untuk halaman utama
Route::get('/', [FrontController::class, 'index'])->name('front.index');

// Route untuk kategori berdasarkan slug
Route::get('/browse/{category:slug}', [FrontController::class, 'category'])->name('front.category');

// Route untuk detail produk berdasarkan slug
Route::get('/details/{shoe:slug}', [FrontController::class, 'details'])->name('front.details');

// Route untuk memulai proses pemesanan berdasarkan slug produk
Route::post('/order/begin/{shoe:slug}', [OrderController::class, 'saveOrder'])->name('front.save_order');

// Route untuk halaman pemesanan
Route::get('/order/booking/', [OrderController::class, 'booking'])->name('front.booking');

// Route untuk data pelanggan
Route::get('/order/booking/customer-data', [OrderController::class, 'customerData'])->name('front.customer_data');

// Route untuk menyimpan data pelanggan
Route::post('/order/booking/customer-data/save', [OrderController::class, 'saveCustomerData'])->name('front.save_customer_data');

// Route untuk halaman pembayaran
Route::get('/order/payment', [OrderController::class, 'payment'])->name('front.payment');

// Route untuk konfirmasi pembayaran
Route::post('/order/payment/confirm', [OrderController::class, 'paymentConfirm'])->name('front.payment_confirm');

// Route untuk halaman selesai pemesanan berdasarkan ID transaksi produk
Route::get('/order/finished/{productTransaction:id}', [OrderController::class, 'orderFinished'])->name('front.order_finished');
