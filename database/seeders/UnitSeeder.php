<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::query()->delete();

        // Mendapatkan Level 1 dan Level 2
        $level1 = Level::where('order', 1)->first();
        $level2 = Level::where('order', 2)->first();

        // Level 1: Mengenal Aksara (Pilihan Ganda)
        if ($level1) {
            Unit::create([
                'level_id' => $level1->id,
                'name' => 'Mengenal Aksara Bagian 1: Ha, Na, Ca, Ra, Ka',
                'description' => 'Mengenal aksara pertama dalam urutan Carakan melalui pilihan ganda.',
                'order' => 1
            ]);
            Unit::create([
                'level_id' => $level1->id,
                'name' => 'Mengenal Aksara Bagian 2: Da, Ta, Sa, Wa, La',
                'description' => 'Mengenal aksara berikutnya dalam urutan Carakan.',
                'order' => 2
            ]);
            Unit::create([
                'level_id' => $level1->id,
                'name' => 'Mengenal Aksara Bagian 3: Pa, Dha, Ja, Ya, Nya',
                'description' => 'Mengenal aksara berikutnya.',
                'order' => 3
            ]);
            Unit::create([
                'level_id' => $level1->id,
                'name' => 'Mengenal Aksara Bagian 4: Ma, Ga, Ba, Tha, Nga',
                'description' => 'Mengenal aksara berikutnya dalam urutan Carakan.',
                'order' => 4
            ]);
        }

        // Level 2: Menulis Aksara (Canvas)
        if ($level2) {
            // Unit untuk Level 2: Menulis Aksara
            Unit::create([
                'level_id' => $level2->id,
                'name' => 'Menulis Aksara Bagian 1: Ha, Na, Ca, Ra, Ka',
                'description' => 'Latih kemampuan menulis aksara Ha, Na, Ca, Ra, Ka di canvas.',
                'order' => 1
            ]);
            Unit::create([
                'level_id' => $level2->id,
                'name' => 'Menulis Aksara Bagian 2: Da, Ta, Sa, Wa, La',
                'description' => 'Latih kemampuan menulis aksara Da, Ta, Sa, Wa, La di canvas.',
                'order' => 2
            ]);
            Unit::create([
                'level_id' => $level2->id,
                'name' => 'Menulis Aksara Bagian 3: Pa, Dha, Ja, Ya, Nya',
                'description' => 'Latih kemampuan menulis aksara Pa, Dha, Ja, Ya, Nya di canvas.',
                'order' => 3
            ]);
            Unit::create([
                'level_id' => $level2->id,
                'name' => 'Menulis Aksara Bagian 4: Ma, Ga, Ba, Tha, Nga',
                'description' => 'Latih kemampuan menulis aksara Ma, Ga, Ba, Tha, Nga di canvas.',
                'order' => 4
            ]);
        }
    }
}
