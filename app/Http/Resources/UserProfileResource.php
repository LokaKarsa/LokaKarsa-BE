<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'bio' => $this->bio,
            'sex' => $this->sex,
            'region' => $this->region,
            'date_of_birth' => $this->date_of_birth,
        ];
    }
}
