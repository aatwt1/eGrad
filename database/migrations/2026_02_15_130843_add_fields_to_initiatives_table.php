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
        Schema::table('initiatives', function (Blueprint $table) {
            // Dodaj nova polja
            $table->text('goal')->after('description');
            $table->string('category')->after('local_community_id');
            $table->decimal('estimated_budget', 10, 2)->nullable()->after('category');
            $table->text('rejection_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            // Ukloni polja ako vratimo migraciju
            $table->dropColumn(['goal', 'category', 'estimated_budget', 'rejection_reason']);
        });
    }
};