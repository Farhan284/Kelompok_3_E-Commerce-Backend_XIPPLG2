<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory;

    protected $table = 'pengguna';
    protected $fillable = [
        'nama_pengguna',
        'email',
        'password',
        'alamat',
        'nomor_telepon',
        'role',
        'tanggal_daftar'
    ];

    protected $hidden = ['password'];
}
