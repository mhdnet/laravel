<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/** @mixin  Order */
class OrderResource extends JsonResource
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
            'ai' => $this->account_id,
            'r' => $this->roster_id,
            'si' => $this->statement_id,
            'pi' => $this->payment_id,
            'g' => $this->governorate_id,
            'l' => $this->location_id,
            'n' => $this->when(!!$this->no, "" . $this->no),
            'nt' => $this->notes,
            'v' => $this->value,
            'f' => $this->fare,
            'a' => $this->address,
            's' => (string)$this->status,
            'ts' => $this->temp_status,
            'dc' => $this->when($this->delay_count > 0, $this->delay_count),
            'p' => resourceLoaded($this->whenLoaded('phones'))?->pluck('number'),
            'ca' => $this->created_at->toIso8601String(),
//            'ua' => $this->updated_at->toIso8601String(),
        ], [
            'id' => 'id',
            'account_id' => 'ai',
            'roster_id' => 'r',
            'statement_id' => 'si',
            'payment_id' => 'pi',
            'governorate_id' => 'g',
            'location_id' => 'l',
            'no' => 'n',
            'notes' => 'nt',
            'value' => 'v',
            'fare' => 'f',
            'address' => 'a',
            'status' => 's',
            'temp_status' => 'ts',
            'delay_count' => 'dc',
            'phones' => 'p',
            'created_at' => 'ca',
//            'updated_at' => 'ua',
        ], $this->resource);
    }
}
