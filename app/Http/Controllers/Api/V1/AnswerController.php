<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Answer\SubmitRequest;
use App\Http\Traits\ApiResponse;
use App\Services\LearningService;
use Illuminate\Http\JsonResponse;

class AnswerController extends Controller
{
    use ApiResponse;

    protected $learningService;

    public function __construct(LearningService $learningService)
    {
        $this->learningService = $learningService;
    }

    public function submit(SubmitRequest $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return $this->errorResponse('Profil pengguna tidak ditemukan. Harap lengkapi profil terlebih dahulu.', 404);
        }
        
        $result = $this->learningService->submitAnswer(
            $profile,
            $request->validated('question_id'),
            $request->validated('answer')
        );

        return $this->successResponse($result, 'Jawaban berhasil disubmit.');
    }
}
