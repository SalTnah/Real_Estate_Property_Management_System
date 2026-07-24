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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('appt_type', ['Viewing', 'Meeting', 'Call', 'Listing', 'Personal']);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['Scheduled', 'Completed', 'Cancelled', 'No-show'])->default('Scheduled');
            $table->enum('outcome', ['Showed', 'No-show', 'Offer Made', 'Not Interested', 'Rescheduled'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
