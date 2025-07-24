<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LevelResource;
use App\Http\Resources\QuestionResource;
use App\Http\Traits\ApiResponse;
use App\Models\Level;
use App\Models\Unit;

class CurriculumController extends Controller
{
    use ApiResponse;

    /**
     * Mengambil seluruh struktur kurikulum (levels dan units).
     */
    public function index()
    {
        // Best Practice: Eager load relasi untuk menghindari N+1 problem.
        $levels = Level::with('units')->orderBy('order')->get();

        return $this->successResponse(LevelResource::collection($levels), 'Kurikulum berhasil diambil.');
    }

    /**
     * Mengambil soal-soal untuk satu unit tertentu.
     */
    public function showQuestions(Unit $unit)
    {
        // Eager load tidak begitu perlu di sini karena kita sudah punya unit
        $questions = $unit->questions()->orderBy('order')->get();

        return $this->successResponse(QuestionResource::collection($questions), 'Soal berhasil diambil.');
    }
}
