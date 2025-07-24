<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan kolom baru ke user_profiles
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('xp_points')->default(0)->after('date_of_birth');
            $table->unsignedInteger('streak_days')->default(0)->after('xp_points');
        });

        // Hapus kolom lama dari users (jika sudah terlanjur dibuat)
        // Pastikan Anda sudah punya kolomnya sebelum menjalankan ini.
        if (Schema::hasColumn('users', 'xp_points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('xp_points');
                $table->dropColumn('streak_days');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            //
        });
    }
};
