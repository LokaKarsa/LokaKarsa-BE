<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->whenNotNull($this->resource['token']),
            'token_type'   => 'Bearer',
            'user'         => new UserResource($this->resource['user']),
        ];
    }
}
