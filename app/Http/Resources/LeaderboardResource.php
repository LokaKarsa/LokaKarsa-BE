<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'rank' akan kita tambahkan secara manual di controller
            'rank' => $this->when(isset($this->rank), $this->rank),
            'user' => [
                // Combine the names from the profile
                'name' => $this->firstname . ' ' . $this->lastname,
            ],
            'xp_points' => $this->xp_points,
        ];
    }
}
