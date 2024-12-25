<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// Kelas ProductTransaction adalah representasi dari tabel 'product_transactions' dalam database
class ProductTransaction extends Model
{
    use HasFactory, SoftDeletes;

    // Daftar atribut yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'name',             // Nama pelanggan
        'phone',            // Nomor telepon pelanggan
        'email',            // Email pelanggan
        'booking_trx_id',   // ID transaksi pemesanan
        'city',             // Kota pelanggan
        'post_code',        // Kode pos pelanggan
        'address',          // Alamat pelanggan
        'quantity',         // Jumlah produk yang dibeli
        'sub_total_amount', // Jumlah sebelum diskon
        'grand_total_amount', // Jumlah total setelah diskon
        'discount_amount',  // Jumlah diskon yang diterima
        'is_paid',          // Status pembayaran (apakah sudah dibayar)
        'shoe_id',          // ID sepatu yang dibeli
        'shoe_size',        // Ukuran sepatu yang dibeli
        'promo_code_id',    // ID kode promo yang digunakan
        'proof',            // Bukti pembayaran
    ];

    // Fungsi untuk menghasilkan ID transaksi yang unik
    public static function generateUniqueTrxId()
    {
        $prefix = 'SS'; // Awalan untuk ID transaksi
        do{
            // Membuat string ID transaksi acak dengan format 'SSxxxx'
            $randomString = $prefix . mt_rand(1000,9999);
        // Looping hingga ID transaksi yang dihasilkan belum ada di database
        }while (self::where('booking_trx_id',$randomString)->exists());

        return $randomString; // Mengembalikan ID transaksi unik
    }

    // Relasi many-to-one dengan model Shoe
    public function shoe(): BelongsTo
    {
        // Sebuah transaksi memiliki satu sepatu
        return $this->belongsTo(Shoe::class, 'shoe_id');
    }

    public function shoeSize(): BelongsTo
    {
        // Sebuah transaksi memiliki satu ukuran sepatu
        return $this->belongsTo(ShoeSize::class, 'shoe_size');
    }

    // Relasi many-to-one dengan model PromoCode
    public function promoCode(): BelongsTo
    {
        // Sebuah transaksi memiliki satu kode promo
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }
}
