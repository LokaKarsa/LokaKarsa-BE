<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikasi saat seeding ulang
        Level::query()->delete();

        Level::create([
            'name' => 'Pengenalan Aksara',
            'description' => 'Mulai perjalananmu dengan mengenal 20 bentuk dasar Aksara Jawa (Aksara Carakan).',
            'order' => 1
        ]);

        Level::create([
            'name' => 'Merangkai Kata',
            'description' => 'Latih kemampuanmu dengan merangkai aksara dasar menjadi kata-kata sederhana.',
            'order' => 2
        ]);

        Level::create([
            'name' => 'Menulis Kalimat',
            'description' => 'Tantang dirimu untuk menulis dan membaca kalimat pendek dalam Aksara Jawa.',
            'order' => 3
        ]);
    }
}
