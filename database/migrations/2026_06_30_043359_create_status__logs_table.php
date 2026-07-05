<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * status_logs — immutable audit trail of every status change on an issue.
     * Every time an issue's status is updated, a new row is inserted here.
     * This table is NEVER updated — only appended to.
     *
     * This table powers:
     *   - Transaction history (visible to both citizen and staff)
     *   - Report generation (resolution times, status distribution)
     *   - Accountability (who changed what, when, with what remarks)
     *
     * Referential integrity:
     *   issue_id   → CASCADE delete
     *     If an issue is deleted (rare, admin action), its logs go with it.
     *     Logs without an issue are meaningless orphans.
     *
     *   changed_by → RESTRICT delete
     *     Do not allow deleting a user who has changed issue statuses.
     *     Their accountability record must be preserved.
     *     If needed, use soft deletes on users instead.
     */
    public function up(): void
    {
        Schema::create('status_logs', function (Blueprint $table) {
            $table->id();

            // The issue this log entry belongs to
            $table->foreignId('issue_id')
                  ->constrained('issues')
                  ->cascadeOnDelete()   // log is meaningless without the issue
                  ->restrictOnUpdate();

            // The staff/admin user who made the change
            $table->foreignId('changed_by')
                  ->constrained('users')
                  ->restrictOnDelete()  // preserve accountability trail
                  ->restrictOnUpdate();

            // old_status is nullable: the first log entry (when issue is created)
            // has no previous status — null represents the "initial" state.
            $table->enum('old_status', [
                'reported', 'verified', 'in_progress', 'completed',
            ])->nullable();

            // The status that was set during this change
            $table->enum('new_status', [
                'reported', 'verified', 'in_progress', 'completed',
            ]);

            // Optional notes from staff explaining the status change
            // (e.g., "Dispatched repair crew on Monday", "Duplicate issue, see #42")
            $table->text('remarks')->nullable();

            $table->timestamps(); // created_at is the exact timestamp of the change

            // Index for quick timeline retrieval per issue
            $table->index(['issue_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_logs');
    }
};