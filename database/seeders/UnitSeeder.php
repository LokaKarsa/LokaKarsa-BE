<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        Unit::query()->delete();

        // Ambil ID dari setiap level
        $level1 = Level::where('order', 1)->first();
        $level2 = Level::where('order', 2)->first();
        $level3 = Level::where('order', 3)->first();

        // --- Unit untuk Level 1: Pengenalan Aksara ---
        if ($level1) {
            Unit::create([
                'level_id' => $level1->id,
                'name' => 'Baris 1: Ha, Na, Ca, Ra, Ka',
                'description' => 'Lima aksara pertama yang akan kamu kuasai.',
                'order' => 1
            ]);
            Unit::create([
                'level_id' => $level1->id,
                'name' => 'Baris 2: Da, Ta, Sa, Wa, La',
                'description' => 'Lima aksara berikutnya dalam urutan Carakan.',
                'order' => 2
            ]);
            Unit::create([
                'level_id' => $level1->id,
                'name' => 'Baris 3: Pa, Dha, Ja, Ya, Nya',
                'description' => 'Kuasai lima aksara di baris ketiga.',
                'order' => 3
            ]);
            Unit::create([
                'level_id' => $level1->id,
                'name' => 'Baris 4: Ma, Ga, Ba, Tha, Nga',
                'description' => 'Selesaikan 20 aksara dasar Carakan.',
                'order' => 4
            ]);
        }

        // --- Unit untuk Level 2: Merangkai Kata (Contoh) ---
        if ($level2) {
            Unit::create([
                'level_id' => $level2->id,
                'name' => 'Kata Sederhana 1',
                'description' => 'Membaca kata dengan 2-3 suku kata.',
                'order' => 1
            ]);
        }

        // --- Unit untuk Level 3: Menulis Kalimat (Contoh) ---
        if ($level3) {
            Unit::create([
                'level_id' => $level3->id,
                'name' => 'Kalimat Pendek 1',
                'description' => 'Membaca kalimat sederhana.',
                'order' => 1
            ]);
        }
    }
}
