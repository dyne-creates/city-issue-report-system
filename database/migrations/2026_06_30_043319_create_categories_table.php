<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * categories - types of city issues, grouped under a department.
     * Examples: "Maingay na kapitbahay" under BCPO, "Napundi nga sila" under BENECO.
     *
     * Referential integrity:
     *   department_id → RESTRICT on delete/update
     *     A department cannot be deleted if it still owns categories.
     *     This prevents orphaned categories and forces admin to reassign first.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                  ->constrained('departments')
                  ->restrictOnDelete()   // cannot delete a department that has categories
                  ->restrictOnUpdate();  // cannot change PK of departments if referenced

            $table->string('name', 100);
            $table->text('description')->nullable();
            
            $table->timestamps();

            // It allows different departments to use the same category name (e.g., Department A can have "umiiyak ng pusa" and Department B can also have "umiiyak na pusa"). 
            // It also prevents a single department from having duplicate category names, so bawal ang dalawang "umiiyak na pusa" sa iisang department.
            $table->unique(['department_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};