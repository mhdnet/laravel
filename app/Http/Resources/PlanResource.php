<?php

namespace App\Http\Resources;

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
        return resource_filter([
            'id' => $this->id,
            'n' => $this->name,
            'd' => $this->description,
            'f' => $this->fare,
            'isd' => $this->is_default,
            'gi' => $this->governorate_id,
            'ca' => $this->created_at->toIso8601String(),
            'ex' => resourceLoaded($this->whenLoaded('governorates'))?->map(fn($governorate) => ['id' => $governorate->id, 'f' => $governorate->pivot->fare])
        ]);
    }
}
