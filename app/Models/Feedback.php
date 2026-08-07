<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /**
     * This project uses DB:: query builder in controllers for reads/writes.
     * This model exists for route-model binding and validation rule
     * references (e.g. 'exists:feedbacks,issue_id') rather than for
     * driving application queries directly.
     */
    protected $fillable = [
        'issue_id',
        'user_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * The issue this feedback belongs to.
     */
    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * The citizen who submitted this feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
