<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentProofColumnsToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Tambahkan payment_proof_path hanya jika belum ada
            if (!Schema::hasColumn('bookings', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable();
            }

            // Tambahkan payment_proof_filename hanya jika belum ada
            if (!Schema::hasColumn('bookings', 'payment_proof_filename')) {
                $table->string('payment_proof_filename')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Hapus kolom hanya jika ada
            if (Schema::hasColumn('bookings', 'payment_proof_path')) {
                $table->dropColumn('payment_proof_path');
            }
            if (Schema::hasColumn('bookings', 'payment_proof_filename')) {
                $table->dropColumn('payment_proof_filename');
            }
        });
    }
}
