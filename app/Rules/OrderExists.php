<?php

namespace App\Rules;

use Closure;
use App\Constants\OrderStatus;
use App\Models\Order;

use App\Models\Roster;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Database\Query\JoinClause;


class OrderExists implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    protected array $statuses = [];


    protected ?Roster $except;

    /**
     * @param array $statuses
     * @param Roster|null $except
     */
    public function __construct(array $statuses, ?Roster $except = null)
    {
        $this->statuses = $statuses;
        $this->except = $except;
    }

    public static function for(string $model, ?Roster $except = null): static
    {
        return new static(OrderStatus::for($model), $except);
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $isRoster = in_array(OrderStatus::Received, $this->statuses);

        $isStatement = in_array(OrderStatus::Rejected, $this->statuses);

        $foreignKey = $isRoster ? 'roster_id' : ($isStatement ? 'statement_id' : 'payment_id');

        $query = Order::whereKey($value)
            ->whereIn('status', $this->statuses);


        if (is_null($this->except))
            $query->whereNull($foreignKey);
        else
            $query->where(fn($q) => $q->whereNull($foreignKey)
                ->orWhere($foreignKey, $this->except->id));


        $account_id = $this->except?->account_id ?? $this->data['account_id'] ?? null;

        if (!$isRoster) {
            $query->where('account_id', $account_id);
        } else {
            $query->join('business_governorate as governorates', function (JoinClause $join) use ($account_id) {
                $join->on('orders.governorate_id', '=', 'governorates.governorate_id')
                    ->where('governorates.business_id', $account_id);
            });
        }


        if (!$query->exists())
            $fail('validation.exists')->translate();


    }
}
