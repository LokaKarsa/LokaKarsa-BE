<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama sebelum melakukan seeding ulang
        Level::query()->delete();

        // Level 1: Mengenal Aksara (Pilihan Ganda)
        Level::create([
            'name' => 'Mengenal Aksara',
            'description' => 'Tantang dirimu untuk mengenali aksara-aksara dasar dengan memilih jawaban yang benar.',
            'order' => 1
        ]);

        // Level 2: Menulis Aksara (Canvas)
        Level::create([
            'name' => 'Menulis Aksara',
            'description' => 'Latih kemampuan menulismu dengan menulis aksara di atas canvas, dan biarkan aplikasi memprediksi aksara yang kamu tulis.',
            'order' => 2
        ]);
    }
}
