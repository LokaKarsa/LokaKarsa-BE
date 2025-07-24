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
        // Cari dulu level yang ingin kita tambahkan unitnya
        $level = Level::where('name', 'Pengenalan Aksara')->first();

        // Pastikan levelnya ada sebelum membuat unit
        if ($level) {
            Unit::create([
                'level_id' => $level->id, // <-- Gunakan ID dari level yang ditemukan
                'name' => 'Baris 1: Ha, Na, Ca, Ra, Ka',
                'description' => 'Lima aksara pertama yang akan kamu kuasai.',
                'order' => 1
            ]);
        }
    }
}
