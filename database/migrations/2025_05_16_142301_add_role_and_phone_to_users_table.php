<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRoleAndPhoneToUsersTable extends Migration
{
    public function up()
    {
        // Periksa kolom role
        $roleColumnType = $this->getCurrentColumnType('users', 'role');
        if ($roleColumnType && $roleColumnType !== 'enum') {
            // Ubah tipe kolom role jika sudah ada
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
        } elseif (!Schema::hasColumn('users', 'role')) {
            // Tambahkan kolom role jika belum ada
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('email');
            });
        }

        // Periksa kolom phone
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('role');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom hanya jika ada
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
        });
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
}
