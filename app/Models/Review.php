<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Review",
 *     type="object",
 *     title="Review",
 *     description="Model Review untuk produk",
 *     required={"user_id", "produk_id", "rating"},
 *     @OA\Property(property="review_id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="produk_id", type="integer", example=3),
 *     @OA\Property(property="rating", type="integer", example=4),
 *     @OA\Property(property="review", type="string", example="Produk bagus, sangat puas!"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-23T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-23T12:30:00Z"),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="produk",
 *         ref="#/components/schemas/Produk",
 *         nullable=true
 *     )
 * )
 */

class Review extends Model
{
    use HasFactory, SoftDeletes;

    

    protected $fillable = [
        'review_id',
        'user_id',
        'produk_id',
        'rating',
        'review'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}
    