<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     required={"id", "name", "email", "alamat", "nomor_telepon", "role"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="alamat", type="string", example="Jl. Merdeka No.1"),
 *     @OA\Property(property="nomor_telepon", type="string", example="08123456789"),
 *     @OA\Property(property="role", type="string", example="pembeli")
 * )
 */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'alamat',
        'nomor_telepon',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'tanggal_daftar' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'role' => 'pembeli',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'user_id');
    }

    /**
     * Get the roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopePembeli($query)
    {
        return $query->where('role', 'pembeli');
    }

    public function scopePenjual($query)
    {
        return $query->where('role', 'penjual');
    }

    /**
     * Accessor untuk mendapatkan nama lengkap
     */
    public function getNamaLengkapAttribute()
    {
        return $this->nama_pengguna ?: $this->name;
    }

    /**
     * Check if user is pembeli
     */
    public function isPembeli()
    {
        return $this->role === 'pembeli';
    }

    /**
     * Check if user is penjual
     */
    public function isPenjual()
    {
        return $this->role === 'penjual';
    }
}