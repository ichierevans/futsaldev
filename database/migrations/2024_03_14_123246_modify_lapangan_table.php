<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (!Schema::hasColumn('lapangan', 'nama')) {
                $table->string('nama');
            }
            if (!Schema::hasColumn('lapangan', 'image')) {
                $table->string('image');
            }
            if (!Schema::hasColumn('lapangan', 'deskripsi')) {
                $table->text('deskripsi');
            }
            if (!Schema::hasColumn('lapangan', 'harga_siang')) {
                $table->decimal('harga_siang', 10, 2);
            }
            if (!Schema::hasColumn('lapangan', 'harga_malam')) {
                $table->decimal('harga_malam', 10, 2);
            }
            if (!Schema::hasColumn('lapangan', 'status')) {
                $table->enum('status', ['tersedia', 'tidak_tersedia'])->default('tersedia');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            $table->dropColumn(['nama', 'image', 'deskripsi', 'harga_siang', 'harga_malam', 'status']);
        });
    }
}; 