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
            // Add dp_amount column if not exists
            if (!Schema::hasColumn('bookings', 'dp_amount')) {
                $table->decimal('dp_amount', 10, 2)->nullable()->after('total_harga');
            }

            // Add DP payment-related columns
            if (!Schema::hasColumn('bookings', 'dp_payment_method')) {
                $table->string('dp_payment_method')->nullable()->after('dp_amount');
            }

            if (!Schema::hasColumn('bookings', 'dp_payment_proof')) {
                $table->string('dp_payment_proof')->nullable()->after('dp_payment_method');
            }

            if (!Schema::hasColumn('bookings', 'dp_payment_date')) {
                $table->timestamp('dp_payment_date')->nullable()->after('dp_payment_proof');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop columns if they exist
            $table->dropColumnIfExists([
                'dp_amount', 
                'dp_payment_method', 
                'dp_payment_proof', 
                'dp_payment_date'
            ]);
        });
    }
};
