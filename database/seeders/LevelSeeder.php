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
        Level::create([
            'name' => 'Pengenalan Aksara',
            'description' => 'Mulai perjalananmu dengan mengenal bentuk dasar Aksara Jawa.',
            'order' => 1
        ]);
    }
}
