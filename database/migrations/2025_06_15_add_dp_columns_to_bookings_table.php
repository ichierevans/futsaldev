<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDpColumnsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add DP-related columns if they don't exist
            if (!Schema::hasColumn('bookings', 'dp_amount')) {
                $table->decimal('dp_amount', 10, 2)->nullable()->after('total_harga');
            }
            
            if (!Schema::hasColumn('bookings', 'remaining_amount')) {
                $table->decimal('remaining_amount', 10, 2)->nullable()->after('dp_amount');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_bank')) {
                $table->string('payment_bank')->nullable()->after('remaining_amount');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_date')) {
                $table->date('payment_date')->nullable()->after('payment_bank');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'partial', 'completed'])->default('pending')->after('payment_date');
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
            $columns = ['dp_amount', 'remaining_amount', 'payment_bank', 'payment_date', 'payment_status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
} 