<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah tipe enum dengan menambahkan 'waiting_confirmation'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'waiting_confirmation', 'confirmed', 'cancelled') DEFAULT 'pending'");
    }

    public function down()
    {
        // Kembalikan ke enum yang lama
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'");
    }
}; 