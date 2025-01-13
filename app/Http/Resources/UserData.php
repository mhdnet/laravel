<?php

namespace App\Http\Resources;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Client\AccountResource;

/**
 * @mixin User
 */
class UserData extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {




        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'last_access_token' => $this->currentAccessToken()->last_used_at?->toIso8601String(),
            $this->mergeWhen($request->isAdminRoute(), [
                'permissions' => $this->getPermissionNames(),
                'roles' => $this->getRoleNames(),
            ]),

            $this->mergeWhen($request->isClientRoute(), [
                'permissions' => [],
                'current_account' => $request->account()?->id,
            ]),
//            $this->mergeWhen($request->isDelegateRoute(), []),
        ];
    }
}
