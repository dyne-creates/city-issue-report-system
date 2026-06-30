<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * issues — the core transaction table of the system.
     * A citizen reports an issue; staff/admin process and update it through
     * defined status states.
     *
     * Status flow (enforced at application layer):
     *   reported → verified → in_progress → completed
     *   (Any status can be moved back by admin if needed)
     *
     * Referential integrity:
     *   user_id      → RESTRICT  — do not delete users who have filed issues
     *   barangay_id  → RESTRICT  — do not delete a barangay with active issues
     *   category_id  → RESTRICT  — do not delete a category that issues reference
     *
     *   RESTRICT is chosen over CASCADE here because losing an issue record
     *   is unacceptable in a civic reporting system — issues are legal/civic records.
     */
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();

            // The citizen who filed this issue
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete()
                  ->restrictOnUpdate();

            // Where the issue is geographically located
            $table->foreignId('barangay_id')
                  ->constrained('barangays')
                  ->restrictOnDelete()
                  ->restrictOnUpdate();

            // Classification of issue type
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->restrictOnDelete()
                  ->restrictOnUpdate();

            $table->string('title', 200);               // short summary
            $table->text('description');                // full detail from citizen

            // Specific location within the barangay (e.g. "Near Jollibee on Rizal St.")
            $table->string('specific_location', 255)->nullable();

            // Status enum — this is what drives the transaction workflow
            // 'reported'    — citizen submitted the issue
            // 'verified'    — staff confirmed the issue is real/valid
            // 'in_progress' — staff/department is actively working on it
            // 'completed'   — issue has been resolved
            $table->enum('status', [
                'reported',
                'verified',
                'in_progress',
                'completed',
            ])->default('reported');

            // Relative file path stored in storage/app/public
            // (e.g. "issues/photos/abc123.jpg")
            // Do NOT store binary data or full URLs in the database.
            $table->string('photo_path', 255)->nullable();

            // Only populated when status reaches 'completed'
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            // Indexes for search performance (used in search + reports)
            $table->index('status');
            $table->index('barangay_id');
            $table->index('category_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};