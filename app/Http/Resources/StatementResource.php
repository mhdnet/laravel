<?php

namespace App\Http\Resources;

use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Statement */
class StatementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $exportedAt = $this->exported_at?->toIso8601String();
        $achievedAt = $this->achieved_at?->toIso8601String();

        return resource_filter([
            'id' => $this->id,
            'ai' => $this->account_id,
            'v' => $this->value,
            'f' => $this->fare,
            'ca' => $this->created_at->toIso8601String(),
            'ea' => $this->when(!!$exportedAt, $exportedAt),
            'aa' => $this->when(!!$achievedAt, $achievedAt),
            'di' => $this->when(!!$this->delegate_id, $this->delegate_id),
            'ci' => new CreatorResource($this->whenLoaded('audits.creator.user')),
            'ri' => $this->when(!!$this->receiver_id, $this->receiver_id),
            'o' => resourceLoaded($this->whenLoaded('orders'))?->pluck('id')
        ]);
    }
}
