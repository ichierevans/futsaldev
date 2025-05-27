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
        Schema::table('lapangan', function (Blueprint $table) {
            // Drop the existing blob column
            $table->dropColumn('image');
            
            // Add a new varchar column for image path
            $table->string('image')->nullable()->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            // Revert changes if needed
            $table->dropColumn('image');
            $table->longBlob('image')->nullable();
        });
    }
};
