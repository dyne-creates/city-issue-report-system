<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Role constants
    |--------------------------------------------------------------------------
    | Mirrors the `role` enum on the users table. Used instead of raw
    | strings throughout the app to avoid typos in role checks.
    */
    public const ROLE_CITIZEN = 'citizen';
    public const ROLE_STAFF   = 'staff';
    public const ROLE_ADMIN   = 'admin';

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'barangay_id',
        'name',
        'email',
        'password',
        'role',
        'contact_number',
    ];

    /**
     * Attributes hidden from array/JSON serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Barangay this user belongs to (nullable for staff/admin).
     */
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Issues filed by this user (as the reporting citizen).
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Status log entries this user authored, i.e. status changes
     * this user made on issues (status_logs.changed_by).
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(Status_Log::class, 'changed_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Role helpers
    |--------------------------------------------------------------------------
    */

    public function isCitizen(): bool
    {
        return $this->role === self::ROLE_CITIZEN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
}