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
        Schema::create('badge_user_profile', function (Blueprint $table) {
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_profile_id')->constrained()->onDelete('cascade');
            $table->timestamp('unlocked_at')->useCurrent();
            $table->primary(['badge_id', 'user_profile_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_user_profile');
    }
};
