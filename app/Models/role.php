<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="role",
 *     type="object",
 *     title="role",
 *     required={"name"},
 *     @OA\Property(property="role_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="admin"),
 *     @OA\Property(property="description", type="string", example="Administrator role"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T12:30:00Z")
 * )
 */
class role extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'role_id',
        'name',
        'description'
    ];

    /**
     * Get the users that belong to this role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
