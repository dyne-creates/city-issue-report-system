<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * departments - goverment departments responsible for handling issue categories.
     *             - like BENECO/ BCPO
     * No foreign keys — root table.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            
            $table->string('name', 100)->unique();  // department name, enforced unique
            $table->text('description')->nullable(); // optional detail about the dept

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};