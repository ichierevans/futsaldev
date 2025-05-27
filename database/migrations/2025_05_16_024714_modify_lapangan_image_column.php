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
            // Modify the image column to be a string path
            $table->string('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            // Revert changes if needed
            $table->longBlob('image')->nullable()->change();
        });
    }
};
