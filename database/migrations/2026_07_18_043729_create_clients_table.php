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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['Buyer', 'Seller', 'Both', 'Renter'])->default('Buyer');
            $table->string('lead_source')->nullable();
            $table->enum('lead_status', ['New', 'Contacted', 'Qualified', 'Nurturing', 'Client', 'Closed', 'Lost'])->default('New');
            $table->timestamp('last_activity')->nullable();
            $table->text('notes')->nullable();
            $table->date('client_since')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
