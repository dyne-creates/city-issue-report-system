<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
 
            // Nullable: admin/staff do not belong to a barangay
            $table->foreignId('barangay_id')
                  ->nullable()
                  ->constrained('barangays')
                  ->restrictOnDelete()
                  ->restrictOnUpdate();
 
            $table->string('name', 150);
 
            $table->string('email', 150)->unique();     // used for login
            $table->timestamp('email_verified_at')->nullable(); // Laravel default
 
            $table->string('password');                 // bcrypt hashed
 
            // Role enum: controls access rights throughout the application
            //   citizen — can report and track own issues
            //   staff   — can update issue statuses and add remarks
            //   admin   — full access including file maintenance and reports
            $table->enum('role', ['citizen', 'staff', 'admin'])->default('citizen');
 
            $table->string('contact_number', 20)->nullable();
 
            $table->rememberToken();    // for "remember me" sessions (Laravel standard)
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
