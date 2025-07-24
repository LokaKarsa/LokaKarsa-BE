<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        // Lencana untuk tipe 'XP'
        Badge::create([
            'name' => 'Langkah Pertama',
            'description' => 'Menyelesaikan soal pertamamu.',
            'type' => 'XP',
            'condition_value' => 1, // Syarat: XP >= 1
            'icon_url' => 'lucide:check-circle',
        ]);

        // Lencana untuk tipe 'STREAK'
        Badge::create([
            'name' => 'Rajin Seminggu',
            'description' => 'Berhasil menjaga streak selama 7 hari.',
            'type' => 'STREAK',
            'condition_value' => 7, // Syarat: streak_days >= 7
            'icon_url' => 'lucide:check-circle',
        ]);
    }
}
