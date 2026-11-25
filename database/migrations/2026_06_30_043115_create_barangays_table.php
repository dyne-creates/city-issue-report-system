<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * barangays - for citizens and issue locations
     * No foreign keys - this is a root/anchor table
     */
    public function up(): void
    {
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();                           
            $table->string('name', 100)->unique();  // barangay must not have a duplicate
            $table->timestamps();                   // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangays');
    }
};