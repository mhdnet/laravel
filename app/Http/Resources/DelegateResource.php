<?php

namespace App\Http\Resources;

use App\Models\Delegate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Delegate */
class DelegateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $account = resourceLoaded($this->accounts)?->first();

        return resource_filter([
            'id' => $this->id,
            'n' => $this->name,
            'e' => $this->email,
            'p' => $this->phone,
            'a' => $this->active,
            'bi' => $account?->id,
            'f' => $account?->pivot->fare,
            'ca' => $this->created_at?->toIso8601String(),
        ]);
    }
}
