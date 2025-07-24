<?php

namespace App\Services;

use App\Models\Question;
use App\Models\UserProfile;

class LearningService
{
  const XP_PER_CORRECT_ANSWER = 10;

  public function submitAnswer(UserProfile $userProfile, int $questionId, string $userAnswer): array
  {
    $question = Question::findOrFail($questionId);

    // Best Practice: Gunakan '==' karena tipe data bisa berbeda, atau lakukan casting.
    $isCorrect = ($userAnswer == $question->content['correct_answer']);

    // 1. Catat setiap jawaban
    $userProfile->answers()->create([
      'question_id' => $question->id,
      'is_correct' => $isCorrect,
    ]);

    // 2. Jika benar, beri poin XP
    if ($isCorrect) {
      $userProfile->xp_points += self::XP_PER_CORRECT_ANSWER;
      $userProfile->save();
    }

    // 3. TODO: Tambahkan logika untuk update user_progress di sini nanti.
    // Misalnya, cek jika semua soal di unit ini sudah dijawab benar,
    // maka status 'user_progress' untuk unit ini menjadi 'completed'.

    return [
      'is_correct' => $isCorrect,
      'correct_answer' => $question->content['correct_answer'],
    ];
  }
}
