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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            // Relasi one-to-one ke tabel users
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('sex', ['male', 'female']); // Enum untuk pilihan yang terbatas
            $table->string('region')->nullable();
            $table->date('date_of_birth');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
