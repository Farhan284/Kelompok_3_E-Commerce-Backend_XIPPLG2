<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Produk",
 *     type="object",
 *     title="Produk",
 *     required={"produk_id", "nama_produk", "harga", "stok"},
 *     @OA\Property(property="produk_id", type="integer", example=1),
 *     @OA\Property(property="nama_produk", type="string", example="Laptop Asus ROG"),
 *     @OA\Property(property="deskripsi", type="string", example="Laptop gaming dengan spesifikasi tinggi"),
 *     @OA\Property(property="harga", type="number", format="float", example=15000000),
 *     @OA\Property(property="stok", type="integer", example=10),
 *     @OA\Property(property="id_kategori", type="integer", example=2),
 *     @OA\Property(property="store_id", type="integer", example=3),
 *     @OA\Property(property="tanggal_ditambahkan", type="string", format="date", example="2024-05-01")
 * )
 */

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = [
        'produk_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'id_kategori',
        'store_id',
        'tanggal_ditambahkan'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'produk_id', 'produk_id');
    }
}
