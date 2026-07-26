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
        // 1. Remove the global 'is_favorited' column from properties
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('is_favorited');
        });

        // 2. Create the new pivot table for individual client favorites
        Schema::create('client_property_favorites', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys linking to clients and properties tables
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            
            $table->timestamps();

            // Prevent duplicate favorites (a client can't favorite the same property twice)
            $table->unique(['client_id', 'property_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop the pivot table
        Schema::dropIfExists('client_property_favorites');

        // 2. Restore the original 'is_favorited' column if the migration is rolled back
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('is_favorited')->default(false);
        });
    }
};