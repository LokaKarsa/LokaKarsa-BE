<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $profile = $request->user()->profile;
        $status = 'unanswered';
        $attempts = 0;

        if ($profile) {
            $history = $profile->answers()
                ->where('question_id', $this->id)
                ->latest('created_at')
                ->get();

            $attempts = $history->count();

            if ($attempts > 0) {
                $status = $history->first()->is_correct ? 'correct' : 'incorrect';
            }
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'order' => $this->order,
            'content' => $this->content,
            'user_answer_history' => [
                'status' => $status,
                'attempts' => $attempts,
            ],
        ];
    }
}
