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
        $currentColumnType = $this->getCurrentColumnType('lapangan', 'image');

        // Jika kolom sudah ada dan bukan varchar, ubah tipe
        if ($currentColumnType && $currentColumnType !== 'varchar') {
            // Gunakan query mentah untuk mengubah tipe kolom
            DB::statement('ALTER TABLE lapangan MODIFY COLUMN image VARCHAR(255) NULL');
        } elseif (!Schema::hasColumn('lapangan', 'image')) {
            // Tambahkan kolom baru jika belum ada
            Schema::table('lapangan', function (Blueprint $table) {
                $table->string('image')->nullable()->after('nama');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Periksa tipe kolom saat ini
        $currentColumnType = $this->getCurrentColumnType('lapangan', 'image');

        // Jika kolom ada, kembalikan ke tipe blob
        if ($currentColumnType && $currentColumnType !== 'blob') {
            // Gunakan query mentah untuk mengubah tipe kolom
            DB::statement('ALTER TABLE lapangan MODIFY COLUMN image LONGBLOB NULL');
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
