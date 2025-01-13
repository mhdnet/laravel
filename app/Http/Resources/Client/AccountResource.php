<?php

namespace App\Http\Resources\Client;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Account */
class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        $account = $request->account();
//
//        $isCurrent = $account?->id == $this->id;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'role' => $this->pivot?->role,
//            'is_current' => $isCurrent,
            'plan' => new PlanResource($this->whenLoaded('plan'))
        ];
    }
}
