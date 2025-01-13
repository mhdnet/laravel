<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin  Client*/
class ClientResource extends JsonResource
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
            'e' => $this->email,
            'p' => $this->phone,
            'a' => $this->active,
            'ca' => $this->created_at->toIso8601String(),
        ]);
    }
}
