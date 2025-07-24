<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ManagesActivityData
{
    const AVG_SECONDS_PER_QUESTION = 45;
    /**
     * Mengambil dan memformat data aktivitas harian untuk profil tertentu.
     */
    private function getActivityData(int $userProfileId)
    {
        $rawActivity = DB::table('user_answers')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as intensity')
            )
            ->where('user_profile_id', $userProfileId)
            ->where('is_correct', true)
            ->where('created_at', '>=', now()->subDays(180))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return $rawActivity->map(function ($item) {
            $date = Carbon::parse($item->date);
            return [
                'date' => $date->format('Y-m-d'),
                'intensity' => (int) $item->intensity,
                // Menggunakan const dari class yang menggunakan trait ini
                'minutes' => round(((int) $item->intensity * static::AVG_SECONDS_PER_QUESTION) / 60),
                'displayDate' => $date->format('F j, Y'),
            ];
        });
    }
}
