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
        Schema::table('bookings', function (Blueprint $table) {
            // Add payment_status column with default value
            $table->enum('payment_status', ['pending', 'partial', 'completed'])->default('pending')->nullable();
            
            // Add remaining_amount column
            $table->decimal('remaining_amount', 10, 2)->nullable();
            
            // Add dp_amount column if not exists
            if (!Schema::hasColumn('bookings', 'dp_amount')) {
                $table->decimal('dp_amount', 10, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'remaining_amount']);
        });
    }
};
