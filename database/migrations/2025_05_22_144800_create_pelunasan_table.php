<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelunasanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelunasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->decimal('payment_amount', 10, 2);
            $table->string('payment_method', 50);
            $table->string('payment_proof', 255)->nullable();
            $table->boolean('is_manual_input')->default(0);
            $table->dateTime('paid_at')->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'completed'])->default('pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelunasan');
    }
}
