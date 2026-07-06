<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_id',
        'name',
        'description',
    ];

    /*
    | Relationships
    */

    /**
     * The department responsible for this category of issue.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Issues classified under this category.
     * issues.category_id is RESTRICT on delete, so a category in use
     * cannot be removed.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}