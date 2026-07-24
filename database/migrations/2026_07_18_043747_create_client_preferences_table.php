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
        Schema::create('client_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->enum('pref_property_type', ['Condo', 'House', 'Townhome', 'Multi-family'])->nullable();
            $table->unsignedTinyInteger('pref_bedrooms')->nullable();
            $table->unsignedTinyInteger('pref_bathrooms')->nullable();
            $table->json('pref_areas')->nullable();
            $table->json('must_haves')->nullable();
            $table->text('additional_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_preferences');
    }
};
