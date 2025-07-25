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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description');
            $table->string('icon_url')->nullable(); // Untuk menyimpan nama ikon Lucide
            $table->enum('type', ['XP', 'STREAK', 'COMPLETION', 'LEVEL', 'UNIT'])
                ->default('XP'); // Tipe lencana, bisa XP, STREAK, COMPLETION, LEVEL, atau UNIT
            $table->unsignedInteger('condition_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
