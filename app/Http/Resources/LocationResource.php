<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Location
 */
class LocationResource extends JsonResource
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
            'a' => $this->when(!!$this->aliases, $this->aliases),
            't' => $this->trusted,
            'g' => resourceLoaded($this->whenLoaded('governorates'))?->pluck('id'),
        ]);
    }
}
