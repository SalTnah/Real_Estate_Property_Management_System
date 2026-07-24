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
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('search_name');
            $table->text('criteria_summary')->nullable();
            $table->enum('alert_frequency', ['Instantly', 'Daily', 'Weekly', 'Never'])->default('Never');
            $table->boolean('alerts_enabled')->default(true);
            $table->unsignedInteger('new_matches_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_searches');
    }
};
