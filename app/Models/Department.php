<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /*
    | Relationships
    */

    /**
     * Categories owned by this department.
     * categories.department_id is RESTRICT on delete, so a department
     * with categories cannot be removed until they are reassigned.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}