<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();

            // The issue being rated
            $table->foreignId('issue_id')
                  ->constrained('issues')
                  ->cascadeOnDelete()
                  ->restrictOnUpdate();

            // ung citizen na nag report
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete()
                  ->restrictOnUpdate();

            // Satisfaction rating, 1 (poor) to 5 (excellent).
            $table->unsignedTinyInteger('rating');

            // Optional free-text comment explaining the rating
            $table->text('comment')->nullable();

            $table->timestamps();

            // One feedback entry per issue — a citizen rates an issue only once
            $table->unique('issue_id');

            // Index for report aggregation (e.g. average rating per department,
            // joined through issues -> categories -> departments)
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};