<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        Question::query()->delete();

        // Soal untuk Unit 1: Mengenal Aksara Bagian 1 (Level 1)
        $unit1 = Unit::where('name', 'Mengenal Aksara Bagian 1: Ha, Na, Ca, Ra, Ka')->first();
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

        // Soal untuk Unit 2: Mengenal Aksara Bagian 2 (Level 1)
        $unit2 = Unit::where('name', 'Mengenal Aksara Bagian 2: Da, Ta, Sa, Wa, La')->first();
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

        // Soal untuk Unit 3: Mengenal Aksara Bagian 3 (Level 1)
        $unit3 = Unit::where('name', 'Mengenal Aksara Bagian 3: Pa, Dha, Ja, Ya, Nya')->first();
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

        // Soal untuk Unit 4: Mengenal Aksara Bagian 4 (Level 1)
        $unit4 = Unit::where('name', 'Mengenal Aksara Bagian 4: Ma, Ga, Ba, Tha, Nga')->first();
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

        // Soal untuk Unit 5: Menulis Aksara Bagian 1 (Level 2)
        $unit5 = Unit::where('name', 'Menulis Aksara Bagian 1: Ha, Na, Ca, Ra, Ka')->first();
        if ($unit5) {
            $questions_unit5 = [
                ['text' => 'Tulis aksara "ꦲ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ha'],
                ['text' => 'Tulis aksara "ꦤ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'na'],
                ['text' => 'Tulis aksara "ꦕ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ca'],
                ['text' => 'Tulis aksara "ꦫ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ra'],
                ['text' => 'Tulis aksara "ꦏ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ka'],
            ];
            $this->createQuestionsForUnit($unit5->id, $questions_unit5);
        }

        // Soal untuk Unit 6: Menulis Aksara Bagian 2 (Level 2)
        $unit6 = Unit::where('name', 'Menulis Aksara Bagian 2: Da, Ta, Sa, Wa, La')->first();
        if ($unit6) {
            $questions_unit6 = [
                ['text' => 'Tulis aksara "ꦢ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'da'],
                ['text' => 'Tulis aksara "ꦠ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ta'],
                ['text' => 'Tulis aksara "ꦱ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'sa'],
                ['text' => 'Tulis aksara "ꦮ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'wa'],
                ['text' => 'Tulis aksara "ꦭ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'la'],
            ];
            $this->createQuestionsForUnit($unit6->id, $questions_unit6);
        }

        // Soal untuk Unit 7: Menulis Aksara Bagian 3 (Level 2)
        $unit7 = Unit::where('name', 'Menulis Aksara Bagian 3: Pa, Dha, Ja, Ya, Nya')->first();
        if ($unit7) {
            $questions_unit7 = [
                ['text' => 'Tulis aksara "ꦥ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'pa'],
                ['text' => 'Tulis aksara "ꦝ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'dha'],
                ['text' => 'Tulis aksara "ꦗ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ja'],
                ['text' => 'Tulis aksara "ꦪ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ya'],
                ['text' => 'Tulis aksara "ꦚ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'nya'],
            ];
            $this->createQuestionsForUnit($unit7->id, $questions_unit7);
        }

        // Soal untuk Unit 8: Menulis Aksara Bagian 4 (Level 2)
        $unit8 = Unit::where('name', 'Menulis Aksara Bagian 4: Ma, Ga, Ba, Tha, Nga')->first();
        if ($unit8) {
            $questions_unit8 = [
                ['text' => 'Tulis aksara "ꦩ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ma'],
                ['text' => 'Tulis aksara "ꦒ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ga'],
                ['text' => 'Tulis aksara "ꦧ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'ba'],
                ['text' => 'Tulis aksara "ꦛ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'tha'],
                ['text' => 'Tulis aksara "ꦔ" pada canvas untuk prediksi.', 'type' => 'canvas', 'correct_answer' => 'nga'],
            ];
            $this->createQuestionsForUnit($unit8->id, $questions_unit8);
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
                'type' => $questionData['type'] ?? 'multiple_choice', // Default to multiple_choice if not canvas
                'order' => $index + 1,
                'content' => [
                    'text' => $questionData['text'],
                    'choices' => $questionData['choices'] ?? null, // Choices only needed for multiple choice
                    'correct_answer' => $questionData['correct_answer']
                ]
            ]);
        }
    }
}
