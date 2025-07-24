<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        Question::query()->delete();

        // --- Soal untuk Unit 1: Ha, Na, Ca, Ra, Ka ---
        $unit1 = Unit::where('name', 'Baris 1: Ha, Na, Ca, Ra, Ka')->first();
        if ($unit1) {
            $questions_unit1 = [
                ['text' => 'Aksara "ꦲ" dibaca...', 'choices' => ['ha', 'na', 'ca', 'ra'], 'correct_answer' => 'ha'],
                ['text' => 'Aksara "ꦤ" dibaca...', 'choices' => ['ha', 'na', 'ca', 'ra'], 'correct_answer' => 'na'],
                ['text' => 'Aksara "ꦕ" dibaca...', 'choices' => ['na', 'ca', 'ra', 'ka'], 'correct_answer' => 'ca'],
                ['text' => 'Aksara "ꦫ" dibaca...', 'choices' => ['ca', 'ra', 'ka', 'da'], 'correct_answer' => 'ra'],
                ['text' => 'Aksara "ꦏ" dibaca...', 'choices' => ['ra', 'ka', 'da', 'ta'], 'correct_answer' => 'ka'],
            ];
            $this->createQuestionsForUnit($unit1->id, $questions_unit1);
        }

        // --- Soal untuk Unit 2: Da, Ta, Sa, Wa, La ---
        $unit2 = Unit::where('name', 'Baris 2: Da, Ta, Sa, Wa, La')->first();
        if ($unit2) {
            $questions_unit2 = [
                ['text' => 'Aksara "ꦢ" dibaca...', 'choices' => ['da', 'ta', 'sa', 'wa'], 'correct_answer' => 'da'],
                ['text' => 'Aksara "ꦠ" dibaca...', 'choices' => ['da', 'ta', 'sa', 'wa'], 'correct_answer' => 'ta'],
                ['text' => 'Aksara "ꦱ" dibaca...', 'choices' => ['ta', 'sa', 'wa', 'la'], 'correct_answer' => 'sa'],
                ['text' => 'Aksara "ꦮ" dibaca...', 'choices' => ['sa', 'wa', 'la', 'pa'], 'correct_answer' => 'wa'],
                ['text' => 'Aksara "ꦭ" dibaca...', 'choices' => ['wa', 'la', 'pa', 'dha'], 'correct_answer' => 'la'],
            ];
            $this->createQuestionsForUnit($unit2->id, $questions_unit2);
        }

        // --- Soal untuk Unit 3: Pa, Dha, Ja, Ya, Nya ---
        $unit3 = Unit::where('name', 'Baris 3: Pa, Dha, Ja, Ya, Nya')->first();
        if ($unit3) {
            $questions_unit3 = [
                ['text' => 'Aksara "ꦥ" dibaca...', 'choices' => ['pa', 'dha', 'ja', 'ya'], 'correct_answer' => 'pa'],
                ['text' => 'Aksara "ꦝ" dibaca...', 'choices' => ['pa', 'dha', 'ja', 'ya'], 'correct_answer' => 'dha'],
                ['text' => 'Aksara "ꦗ" dibaca...', 'choices' => ['dha', 'ja', 'ya', 'nya'], 'correct_answer' => 'ja'],
                ['text' => 'Aksara "ꦪ" dibaca...', 'choices' => ['ja', 'ya', 'nya', 'ma'], 'correct_answer' => 'ya'],
                ['text' => 'Aksara "ꦚ" dibaca...', 'choices' => ['ya', 'nya', 'ma', 'ga'], 'correct_answer' => 'nya'],
            ];
            $this->createQuestionsForUnit($unit3->id, $questions_unit3);
        }

        // --- Soal untuk Unit 4: Ma, Ga, Ba, Tha, Nga ---
        $unit4 = Unit::where('name', 'Baris 4: Ma, Ga, Ba, Tha, Nga')->first();
        if ($unit4) {
            $questions_unit4 = [
                ['text' => 'Aksara "ꦩ" dibaca...', 'choices' => ['ma', 'ga', 'ba', 'tha'], 'correct_answer' => 'ma'],
                ['text' => 'Aksara "ꦒ" dibaca...', 'choices' => ['ma', 'ga', 'ba', 'tha'], 'correct_answer' => 'ga'],
                ['text' => 'Aksara "ꦧ" dibaca...', 'choices' => ['ga', 'ba', 'tha', 'nga'], 'correct_answer' => 'ba'],
                ['text' => 'Aksara "ꦛ" dibaca...', 'choices' => ['ba', 'tha', 'nga', 'ha'], 'correct_answer' => 'tha'],
                ['text' => 'Aksara "ꦔ" dibaca...', 'choices' => ['tha', 'nga', 'ha', 'na'], 'correct_answer' => 'nga'],
            ];
            $this->createQuestionsForUnit($unit4->id, $questions_unit4);
        }
    }

    /**
     * Helper function to create questions for a specific unit.
     */
    private function createQuestionsForUnit(int $unitId, array $questions): void
    {
        foreach ($questions as $index => $questionData) {
            Question::create([
                'unit_id' => $unitId,
                'type' => 'multiple_choice',
                'order' => $index + 1,
                'content' => [
                    'text' => $questionData['text'],
                    'choices' => $questionData['choices'],
                    'correct_answer' => $questionData['correct_answer']
                ]
            ]);
        }
    }
}
