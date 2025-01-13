<?php

namespace App\Http\Resources;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Business */
class BusinessResource extends JsonResource
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
            'p' => $this->phone,
            'a' => $this->active,
            'ca' => $this->created_at->toIso8601String(),
            'g' => $this->governorates->pluck('id'),
//            'delegates' => $this->users->map(fn($user) => array_filter(['id' => $user->id, 'role' => $user->pivot->role, 'fare' => $user->pivot->fare])),
        ]);
    }
}
