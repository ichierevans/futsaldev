<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if payment_status column exists
        if (!Schema::hasColumn('bookings', 'payment_status')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->enum('payment_status', ['pending', 'partial', 'completed'])
                    ->default('pending')
                    ->after('status')
                    ->nullable();
            });
        }

        // Update existing records to have a default payment status
        DB::table('bookings')
            ->whereNull('payment_status')
            ->update(['payment_status' => 'pending']);

        // Add DP (Down Payment) amount column if not exists
        if (!Schema::hasColumn('bookings', 'dp_amount')) {
            $table->decimal('dp_amount', 10, 2)
                ->nullable()
                ->default(0);
        }
        
        // Add remaining amount column if not exists
        if (!Schema::hasColumn('bookings', 'remaining_amount')) {
            $table->decimal('remaining_amount', 10, 2)
                ->nullable()
                ->default(0);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'payment_status')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
        }
        if (Schema::hasColumn('bookings', 'dp_amount')) {
            $table->dropColumn('dp_amount');
        }
        if (Schema::hasColumn('bookings', 'remaining_amount')) {
            $table->dropColumn('remaining_amount');
        }
    }
};
