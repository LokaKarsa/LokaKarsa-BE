<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { {
            Question::create([
                'unit_id' => 1,
                'type' => 'multiple_choice',
                'order' => 1,
                'content' => [
                    'text' => 'Aksara "ꦲ" dibaca...',
                    'choices' => ['ha', 'na', 'ca', 'ra'],
                    'correct_answer' => 'ha'
                ]
            ]);

            Question::create([
                'unit_id' => 1,
                'type' => 'multiple_choice',
                'order' => 2,
                'content' => [
                    'text' => 'Aksara "ꦤ" dibaca...',
                    'choices' => ['ha', 'na', 'ca', 'ra'],
                    'correct_answer' => 'na'
                ]
            ]);
        }
    }
}
