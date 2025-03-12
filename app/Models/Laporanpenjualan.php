<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    use HasFactory;

    protected $table = 'laporan_penjualan';
    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'id_pesanan',
        'id_pembayaran',
        'periode',
        'total_penjualan',
        'total_pendapatan',
        'tanggal_laporan'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran');
    }
}
