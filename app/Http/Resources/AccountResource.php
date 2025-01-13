<?php

namespace App\Http\Resources;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @mixin Account
 */
class AccountResource extends JsonResource
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
            'gi' => $this->governorate_id,
            'a' => $this->active,
            'dl' => $this->different_ledger,
            'pi' => $this->plan_id,
            'r' => $this->receipt_in,
            'ca' => $this->created_at->toIso8601String(),
//            'ua' => $this->updated_at->toIso8601String(),
            'c' => resourceLoaded($this->whenLoaded('users'))?->map(fn($user) => ['id' => $user->id, 'r' => $user->pivot->role]),
            'l' => resourceLoaded($this->whenLoaded('ledgers'))?->pluck('start'),
        ],  [
            'id' => 'id',
            'name' => 'n',
            'phone' => 'p',
            'governorate_id' => 'gi',
            'active' => 'a',
            'different_ledger' => 'dl',
            'plan_id' => 'pi',
            'receipt_in' => 'r',
            'created_at' => 'ca',
//            'updated_at' => 'ua',
            'users' => 'c',
            'ledgers' => 'l',
        ], $this->resource);
    }

}
