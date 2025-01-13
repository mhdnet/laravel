<?php

namespace App\Http\Resources\Client;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Plan
 */
class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fare' => $this->fare,
            'exclusions' => resourceLoaded($this->whenLoaded('governorates'))?->map(fn($governorate) => ['id' => $governorate->id, 'fare' => $governorate->pivot->fare])
        ];
    }
}
