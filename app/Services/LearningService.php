<?php

namespace App\Services;

use App\Http\Resources\BadgeResource;
use App\Models\Question;
use App\Models\Unit;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LearningService
{
  const XP_PER_CORRECT_ANSWER = 10;
  const AVG_SECONDS_PER_QUESTION = 45;

  /**
   * The badge service instance.
   *
   * @var \App\Services\BadgeService
   */
  protected BadgeService $badgeService;

  /**
   * Create a new service instance.
   *
   * @param \App\Services\BadgeService $badgeService
   * @return void
   */
  public function __construct(BadgeService $badgeService)
  {
    $this->badgeService = $badgeService;
  }

  /**
   * Process a user's answer, award XP, and check for unit completion.
   *
   * @param UserProfile $profile
   * @param int $questionId
   * @param string $userAnswer
   * @return array
   */
  public function submitAnswer(UserProfile $profile, int $questionId, string $userAnswer): array
  {
    $question = Question::findOrFail($questionId);
    $isCorrect = ($userAnswer == $question->content['correct_answer']);
    $newlyUnlockedBadges = collect();

    $profile->answers()->create([
      'question_id' => $question->id,
      'is_correct' => $isCorrect,
    ]);

    if ($isCorrect) {
      $profile->xp_points += self::XP_PER_CORRECT_ANSWER;
      $profile->save();

      // Directly call the badge service to check for XP-based badges
      $newlyUnlockedBadges = $this->badgeService->checkAndAwardBadges($profile, 'XP');
    }

    $unitCompletionSummary = $this->checkForUnitCompletion($profile, $question->unit);

    // Merge badges that might have been unlocked from completing the unit (e.g., streak badges)
    if ($unitCompletionSummary && isset($unitCompletionSummary['newly_unlocked_badges'])) {
      $newlyUnlockedBadges = $newlyUnlockedBadges->merge($unitCompletionSummary['newly_unlocked_badges']);
    }

    return [
      'is_correct' => $isCorrect,
      'correct_answer' => $question->content['correct_answer'],
      'unit_completion_summary' => $unitCompletionSummary,
      'newly_unlocked_badges' => BadgeResource::collection($newlyUnlockedBadges->unique('id')),
    ];
  }

  /**
   * Check if a unit is completed after a correct answer.
   *
   * @param UserProfile $profile
   * @param Unit $unit
   * @return array|null
   */
  private function checkForUnitCompletion(UserProfile $profile, Unit $unit): ?array
  {
    $totalQuestionsInUnit = $unit->questions()->count();
    if ($totalQuestionsInUnit == 0) {
      return null;
    }

    $correctAnswersCount = $profile->answers()
      ->where('is_correct', true)
      ->whereIn('question_id', $unit->questions()->pluck('id'))
      ->distinct('question_id')
      ->count();

    if ($correctAnswersCount < $totalQuestionsInUnit) {
      return null;
    }

    return $this->handleUnitCompletion($profile, $unit);
  }

  /**
   * Handle the logic for when a unit is completed.
   *
   * @param UserProfile $profile
   * @param Unit $unit
   * @return array
   */
  private function handleUnitCompletion(UserProfile $profile, Unit $unit): array
  {
    $progress = $profile->progress()->firstOrCreate(['unit_id' => $unit->id]);
    $newlyUnlockedBadges = collect();

    if ($progress->status !== 'completed') {
      $progress->status = 'completed';
      $progress->save();

      $this->updateStreak($profile);
      $profile->save();

      // Directly call the badge service to check for STREAK-based badges
      $newlyUnlockedBadges = $this->badgeService->checkAndAwardBadges($profile, 'STREAK');
    }

    // Calculate summary stats for this session
    $answersInThisUnit = $profile->answers()->whereIn('question_id', $unit->questions()->pluck('id'));
    $totalAnswers = $answersInThisUnit->count();
    $correctAnswers = $answersInThisUnit->where('is_correct', true)->count();
    $accuracy = ($totalAnswers > 0) ? round(($correctAnswers / $totalAnswers) * 100) : 0;
    $xpGained = $correctAnswers * self::XP_PER_CORRECT_ANSWER;
    $timeSpentMinutes = round(($totalAnswers * self::AVG_SECONDS_PER_QUESTION) / 60);

    $summaryData = [
      'unit_name' => $unit->name,
      'time_spent_minutes' => $timeSpentMinutes,
      'accuracy_percent' => $accuracy,
      'xp_gained' => $xpGained,
      'newly_unlocked_badges' => $newlyUnlockedBadges, // Pass the raw collection
    ];

    Cache::put('profile:' . $profile->id . ':last_activity_summary', $summaryData, now()->addMinutes(15));

    // Transform for the immediate response
    $summaryData['newly_unlocked_badges'] = BadgeResource::collection($newlyUnlockedBadges);

    return $summaryData;
  }

  /**
   * Update the user's daily streak.
   *
   * @param UserProfile $profile
   * @return void
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
