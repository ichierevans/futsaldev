<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create lapangan table first
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('image');
            $table->text('deskripsi');
            $table->decimal('harga_siang', 10, 2);
            $table->decimal('harga_malam', 10, 2);
            $table->enum('status', ['tersedia', 'tidak_tersedia'])->default('tersedia');
            $table->timestamps();
        });

        // Then create bookings table with foreign key
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lapangan_id')->constrained('lapangan')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', ['pending', 'waiting_confirmation', 'confirmed', 'cancelled'])->default('pending');
            $table->enum('jenis_booking', ['reguler', 'membership', 'event'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('lapangan');
    }
}; 