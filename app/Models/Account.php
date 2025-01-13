<?php

namespace App\Models;

use App\Rules\PhoneRule;
use App\Traits\Auditable;
use App\Traits\HasGovernorate;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Exceptions\AuditingException;
use Watson\Validating\ValidatingTrait;

class Account extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use SoftDeletes, ValidatingTrait, HasGovernorate, Auditable;

    protected $fillable = [
        'name',
        'phone',
    ];

    protected $with = ['audits', 'ledgers', 'users'];

    protected $casts = [
        'active' => 'boolean',
        'different_ledger' => 'boolean',
        'receipt_in' => AsArrayObject::class,
    ];

    protected bool $throwValidationExceptions = true;

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (self $account) {
            if (!$account->plan_id) {
                $account->plan_id = Plan::default()?->id;
            }

            if (!$account->governorate_id) {
                $account->governorate_id = Governorate::default()?->id;
            }
        });


    }

    public function getRules(): array
    {
        return [
            'name' => 'required|string|min:3|max:50|unique',
            'plan_id' => 'nullable|exists:plans,id',
            'phone' => ['sometimes', 'required', new PhoneRule],
            'governorate_id' => 'nullable|exists:governorates,id',
            'active' => 'sometimes|boolean',
            'different_ledger' => 'sometimes|boolean',
            'receipt_in' => 'nullable|numeric|between:1,7',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)
            ->withPivot(['role', 'subscribe'])
            ->withTimestamps();
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

    public function statements(): HasMany {
        return $this->hasMany(Statement::class);
    }

    public function ledgers(): HasMany
    {
        return $this->hasMany(Ledger::class);
    }

    /**
     * @throws AuditingException
     */
    public function addUser($user, $role): void
    {
        $this->auditAttach('users', $user, ['role' => $role], true, ['id']);
    }

    public function syncLedgers($no , $oldNo): void
    {
        $this->updateRelationAudit('ledgers', function () use ($no , $oldNo){
            $needUpdate = false;

            if(($old = base_order_no($oldNo)) && Order::whereBetween('no', [$old, $old + 49])->count() == 1) {
                $this->ledgers()->firstWhere(['start' => $old])->delete();
                $needUpdate = true;
            }

            if(($new = base_order_no($no)) && !$this->ledgers()->where(['start' => $new])->exists()) {

                $this->ledgers()->create(['start' => $new]);

                $needUpdate = true;
            }

            return $needUpdate;
        });
    }
}
