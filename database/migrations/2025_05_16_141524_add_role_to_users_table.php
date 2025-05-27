<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Periksa tipe kolom saat ini
        $currentColumnType = $this->getCurrentColumnType('users', 'role');

        // Jika kolom sudah ada dan bukan enum, ubah tipe
        if ($currentColumnType && $currentColumnType !== 'enum') {
            // Gunakan query mentah untuk mengubah tipe kolom
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
        } elseif (!Schema::hasColumn('users', 'role')) {
            // Tambahkan kolom baru jika belum ada
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['user', 'admin'])->default('user')->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Periksa apakah kolom role ada
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }

    /**
     * Dapatkan tipe kolom saat ini
     */
    private function getCurrentColumnType($table, $column)
    {
        try {
            $result = DB::select(
                "SELECT DATA_TYPE 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = ? 
                AND COLUMN_NAME = ?", 
                [env('DB_DATABASE'), $table, $column]
            );

            return $result ? strtolower($result[0]->DATA_TYPE) : null;
        } catch (\Exception $e) {
            return null;
        }
    }
};
