<?php

namespace App\Models;

use App\Constants\AccountRoles;
use App\Rules\PhoneRule;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory, HasUlids, ValidatingTrait;

    protected $fillable = [
        'role', 'email', 'phone',
    ];

    protected bool $throwValidationExceptions = true;

    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    public function getRules(): array
    {
        return [
            'email' => 'required_without:phone|email',
            'phone' => ['required_without:email', new PhoneRule, ],
            'role' => 'nullable|in:'. join(',', AccountRoles::ALL),
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function inviter(): BelongsTo {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function toRoute(): string
    {
       return route('invite', ['invite' => $this->ulid]);
    }
}
