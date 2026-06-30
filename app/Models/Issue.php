<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Status constants
    |--------------------------------------------------------------------------
    | Mirrors the `status` enum on the issues table (and the enums on
    | status_logs). Application-layer flow:
    |   reported -> verified -> in_progress -> completed
    | (admin may move an issue backward if needed)
    */
    public const STATUS_REPORTED    = 'reported';
    public const STATUS_VERIFIED    = 'verified';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED   = 'completed';

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'barangay_id',
        'category_id',
        'title',
        'description',
        'specific_location',
        'status',
        'photo_path',
        'resolved_at',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The citizen who filed this issue.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The barangay where this issue is located.
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * The category this issue is classified under.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Full audit trail of status changes for this issue, oldest first.
     * status_logs.issue_id cascades on delete, so this relation is
     * automatically cleaned up if the issue itself is removed.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(Status_Log::class)->orderBy('created_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Convenience accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Whether this issue has reached the completed state.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}