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

        // Lencana untuk tipe 'LEVEL'
        Badge::create([
            'name' => 'Melewati Level 1',
            'description' => 'Menyelesaikan level pertama.',
            'type' => 'LEVEL',
            'condition_value' => 1, // Syarat: level >= 1
            'icon_url' => 'lucide:star',
        ]);

        // Lencana untuk tipe 'UNIT'
        Badge::create([
            'name' => 'Penghargaan Unit',
            'description' => 'Menyelesaikan 5 unit pertama.',
            'type' => 'UNIT',
            'condition_value' => 5, // Syarat: unit_completed >= 5
            'icon_url' => 'lucide:award',
        ]);

        // Lencana untuk tipe 'XP'
        Badge::create([
            'name' => 'Pencapaian Tinggi',
            'description' => 'Mendapatkan 100 XP.',
            'type' => 'XP',
            'condition_value' => 100, // Syarat: XP >= 100
            'icon_url' => 'lucide:trophy',
        ]);

        // Lencana untuk tipe 'STREAK'
        Badge::create([
            'name' => 'Rajin Sebulan',
            'description' => 'Berhasil menjaga streak selama 30 hari.',
            'type' => 'STREAK',
            'condition_value' => 30, // Syarat: streak_days >= 30
            'icon_url' => 'lucide:fire',
        ]);
    }
}
