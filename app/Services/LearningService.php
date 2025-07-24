<?php

namespace App\Services;

use App\Events\UnitCompleted;
use App\Models\Question;
use App\Models\Unit;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LearningService
{
  const XP_PER_CORRECT_ANSWER = 10;
  const AVG_SECONDS_PER_QUESTION = 45;

  /**
   * Memproses jawaban yang dikirim oleh pengguna.
   */
  public function submitAnswer(UserProfile $profile, int $questionId, string $userAnswer): array
  {
    $question = Question::findOrFail($questionId);
    $isCorrect = ($userAnswer == $question->content['correct_answer']);

    // 1. Selalu catat setiap jawaban
    $profile->answers()->create([
      'question_id' => $question->id,
      'is_correct' => $isCorrect,
    ]);

    // 2. Jika jawaban benar, lakukan proses selanjutnya
    if ($isCorrect) {
      // Beri poin XP
      $profile->xp_points += self::XP_PER_CORRECT_ANSWER;
      $profile->save();

      // Panggil method baru untuk memeriksa apakah unit sudah selesai
      $this->checkForUnitCompletion($profile, $question->unit);
    }

    return [
      'is_correct' => $isCorrect,
      'correct_answer' => $question->content['correct_answer'],
    ];
  }

  /**
   * Memeriksa apakah pengguna telah menyelesaikan semua soal di satu unit.
   * Ini adalah method baru yang menjadi inti dari perubahan logika.
   */
  private function checkForUnitCompletion(UserProfile $profile, Unit $unit): void
  {
    // Hitung jumlah total soal di unit ini
    $totalQuestionsInUnit = $unit->questions()->count();

    // Jika tidak ada soal, hentikan proses
    if ($totalQuestionsInUnit == 0) {
      return;
    }

    // Hitung jumlah jawaban benar yang unik dari pengguna untuk unit ini
    $correctAnswersCount = $profile->answers()
      ->where('is_correct', true)
      ->whereIn('question_id', $unit->questions()->pluck('id'))
      ->distinct('question_id')
      ->count();

    // Jika jumlah jawaban benar sudah sama dengan total soal, unit selesai!
    if ($correctAnswersCount >= $totalQuestionsInUnit) {
      $this->handleUnitCompletion($profile, $unit);
    }
  }

  /**
   * Menangani semua logika setelah sebuah unit berhasil diselesaikan.
   */
  private function handleUnitCompletion(UserProfile $profile, Unit $unit): array
  {
    // 1. Cari atau buat catatan progress untuk unit ini
    $progress = $profile->progress()->firstOrCreate(
      ['unit_id' => $unit->id]
    );

    // 2. Hanya jalankan logika streak JIKA unit ini SEBELUMNYA belum selesai.
    // Ini mencegah streak bertambah jika pengguna mengulang unit yang sudah selesai.
    if ($progress->status != 'completed') {
      $progress->status = 'completed';
      $progress->save();

      $this->updateStreak($profile);
      $profile->save();
      event(new UnitCompleted($profile));
    }

    $answersInThisUnit = $profile->answers()->whereIn('question_id', $unit->questions()->pluck('id'));
    $totalAnswers = $answersInThisUnit->count();
    $correctAnswers = $answersInThisUnit->where('is_correct', true)->count();
    $accuracy = ($totalAnswers > 0) ? round(($correctAnswers / $totalAnswers) * 100) : 0;
    $xpGained = $correctAnswers * self::XP_PER_CORRECT_ANSWER;
    $timeSpentMinutes = round(($totalAnswers * self::AVG_SECONDS_PER_QUESTION) / 60);

    // Cek lencana...
    $newlyUnlockedBadge = null; // Logika lencana di sini...

    // [BARU] Rakit data ringkasan
    $summaryData = [
      'unit_name' => $unit->name,
      'time_spent_minutes' => $timeSpentMinutes,
      'accuracy_percent' => $accuracy,
      'xp_gained' => $xpGained,
      'new_badge_unlocked' => $newlyUnlockedBadge ? [
        'name' => $newlyUnlockedBadge->name,
        'description' => $newlyUnlockedBadge->description,
      ] : null,
    ];

    Cache::put('profile:' . $profile->id . ':last_activity_summary', $summaryData, now()->addMinutes(15));


    return $summaryData;
  }

  /**
   * Logika untuk memperbarui streak harian pengguna.
   * Method ini tidak berubah, hanya dipanggil dari tempat yang berbeda.
   */
  protected function updateStreak(UserProfile $profile): void
  {
    $today = Carbon::today();
    $lastActivity = $profile->last_activity_at ? Carbon::parse($profile->last_activity_at)->today() : null;

    if (is_null($lastActivity) || $lastActivity->lt($today->copy()->subDay())) {
      $profile->streak_days = 1;
    } else if ($lastActivity->eq($today->copy()->subDay())) {
      $profile->streak_days += 1;
    }

    if ($profile->streak_days > $profile->highest_streak) {
      $profile->highest_streak = $profile->streak_days;
    }

    $profile->last_activity_at = now();
  }
}
