<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use App\Http\Resources\QuestionResource;
use App\Http\Traits\ApiResponse;
use App\Models\Level;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    use ApiResponse;

    /**
     * Mengambil seluruh struktur kurikulum (levels dan units).
     */
    public function index(Request $request): JsonResponse
    {
        // Eager load relasi secara efisien untuk menghindari N+1 query problem
        $levels = Level::with([
            'units' => function ($query) {
                $query->withCount('questions')->orderBy('order');
            }
        ])->orderBy('order')->get();

        return $this->successResponse(LevelResource::collection($levels), 'Kurikulum berhasil diambil.');
    }

    /**
     * Mengambil soal-soal untuk satu unit tertentu.
     */

    public function showByUnit(Unit $unit): JsonResponse
    {
        $questions = $unit->questions()->orderBy('order')->get();

        $data = [
            'unit_info' => [
                'id' => $unit->id,
                'name' => $unit->name,
                'description' => $unit->description,
            ],
            'total_questions' => $questions->count(),
            'questions' => QuestionResource::collection($questions),
        ];

        return $this->successResponse($data, 'Soal berhasil diambil.');
    }
}
