<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BadgeResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'name' => $this->name,
      'description' => $this->description,
      'icon_url' => $this->icon_url,
      // Best Practice: Menggunakan whenPivotLoaded untuk mencegah error
      // dan hanya menyertakan 'unlocked_at' ketika data berasal dari relasi pivot.
      'unlocked_at' => $this->whenPivotLoaded('badge_user_profile', function () {
        return $this->pivot->unlocked_at;
      }),
    ];
  }
}
