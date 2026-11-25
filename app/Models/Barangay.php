<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /*
    | Relationships
    */

    /**
     * Users (citizens/staff) registered under this barangay.
     * users.barangay_id is nullable (admin/staff may not belong to a barangay).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Issues located in this barangay.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}