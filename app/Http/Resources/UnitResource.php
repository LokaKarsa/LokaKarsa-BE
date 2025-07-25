<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // $this->resource adalah instance dari model Unit
        $user = $request->user();
        $profile = $user->profile;
        $progress = null;
        $status = 'locked';
        $completionPercent = 0;
        $questionsCount = $this->whenCounted('questions', $this->questions_count, 0);

        if ($profile) {
            $progress = $profile->progress()->where('unit_id', $this->id)->first();

            // Tentukan status unit
            if ($progress && $progress->status === 'completed') {
                $status = 'completed';
                $completionPercent = 100;
            } else {
                // Asumsi: Unit pertama selalu unlocked, atau unit berikutnya unlocked jika unit sebelumnya selesai.
                $previousUnitCompleted = $profile->progress()->where('unit_id', $this->id - 1)->where('status', 'completed')->exists();
                if ($this->order == 1 || $previousUnitCompleted) {
                    $status = 'unlocked';
                    if ($progress) {
                        $status = 'in_progress';
                    }
                }
            }

            // Hitung persentase penyelesaian jika sedang dikerjakan
            if ($status === 'in_progress' && $questionsCount > 0) {
                $correctAnswers = $profile->answers()
                    ->where('is_correct', true)
                    ->whereIn('question_id', $this->questions()->pluck('id'))
                    ->distinct('question_id')
                    ->count();
                $completionPercent = round(($correctAnswers / $questionsCount) * 100);
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'order' => $this->order,
            'questions_count' => $questionsCount,
            'progress' => [
                'status' => $status,
                'completion_percent' => $completionPercent,
            ],
        ];
    }
}
