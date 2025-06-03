<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $fillable = [
        'user_id',
        'cart_id',  
        'tanggal_pesanan',
        'status_pesanan',
        'total_harga'
    ];

    public function user()
    {
        return $this->belongsTo(Pelanggan::class, 'user_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(pembayaran::class, 'pembayaran_id');
    }
}
