<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Periksa apakah kolom role sudah ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->nullable();  // Menambahkan kolom role dengan default 'user'
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom role hanya jika ada
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');  // Menghapus kolom role jika migrasi dibatalkan
            }
        });
    }

};
