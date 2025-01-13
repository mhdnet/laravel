<?php

namespace App\Http\Resources;

use App\Constants\RolesName;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Delegate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin  Admin
 */
class AdminResource extends JsonResource
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
            'pr' => $this->getPermissionNames(),
            'r' => array_filter($this->getRoleNames()->all(), fn($item) => $item != RolesName::ADMIN),
            'ca' => $this->created_at->toIso8601String(),
        ]);
    }
}
