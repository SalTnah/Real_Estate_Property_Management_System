<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE client_preferences MODIFY pref_property_type ENUM('Condo', 'House', 'Townhome', 'Multi-family', 'Single Family') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE client_preferences MODIFY pref_property_type ENUM('Condo', 'House', 'Townhome', 'Multi-family') NULL");
    }
};