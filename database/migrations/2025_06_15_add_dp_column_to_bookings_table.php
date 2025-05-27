<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDpColumnToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add dp column if it doesn't exist
            if (!Schema::hasColumn('bookings', 'dp')) {
                $table->decimal('dp', 10, 2)->nullable()->default(0.00)->after('total_harga');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop dp column if it exists
            if (Schema::hasColumn('bookings', 'dp')) {
                $table->dropColumn('dp');
            }
        });
    }
} 