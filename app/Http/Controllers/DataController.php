<?php

namespace App\Http\Controllers;


use App\Models\Client;
use App\Models\Model;
use App\Traits\HasSynchronize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OwenIt\Auditing\Models\Audit;

class DataController extends Controller
{
    protected string|null $date;

    protected int $total = 0;

    protected int $perPage = 300;

    protected int $length = 0;


    public function index(): array
    {

        $this->date = request()->lastUpdates()?->toDateTimeString();

        if ($perPage = request()->input('perPage'))
            $this->perPage = $perPage;

        $entities = $this->getEntities();

        $data = $this->fetch($entities, $start = max(LengthAwarePaginator::resolveCurrentPage() - 1, 0) * $this->perPage, false);

        return [
            'data' => $data,
            'removed' => $this->fetch($entities, $start, true),
            'meta' => [
                'total' => $this->total,
                'perPage' => $this->perPage,
                'from' => min(($start >= $this->total ? -1 : $start) + 1, $this->total),
                'to' => max(($start >= $this->total ? -1 : $start) + $this->length, 0),
            ]
        ];
    }

    protected function getEntities(): Collection
    {
        if (request()->isAdminRoute())
            return collect(config('synchronize.entities'));
        elseif (request()->isDelegateRoute())
            return collect();
        else {

            /** @var Client $user */
            $user = request()->user();
            $account = request()->account();


            return collect(config('synchronize.clients.share_cache'))->merge([
                'accounts' => $user->accounts()->with('plan.governorates'),
                'payments' => $account->payments(),
                'statements' => $account->statements(),
                'orders' => $account->orders(),
            ]);

        }
    }

    protected function getModel($query): Model
    {
        return $query instanceof HasMany ? $query->getRelated() : $query->getModel();
    }

    public function fetch($entities, int $start, bool $removed): null|Collection
    {
        $data = collect();

        if ($removed && !$this->date)
            return null;

        foreach ($entities as $entity => $query) {

            /** @var HasSynchronize $model */
            if (is_string($query)) {
                $model = $query;
                $query = $this->getQueryBuilder($query);
            } else {
                $model = $this->getModel($query);
            }

            if ($model::getCached($this->date))
                continue;

            $query = $this->query($model, $query, $removed);


            $this->total += ($count = $query->count());

            $over = $this->total - $start;

            if ($this->total > $start && $this->length < $this->perPage && $count > 0) {
                $offset = max(0, $count - $over);

                $limit = min($over, $this->perPage) - $this->length;

                $result = $query->offset($offset)->limit($limit)->get();

                $this->length += $result->count();

                if ($result->count()) {
                    $data->put($entity, $this->resolve($model, $result, $removed));
                }
            }
        }

        if ($data->isNotEmpty())
            return $data;
        return null;

    }

    protected function getQueryBuilder(Model|string $model): Builder|Model
    {
        return $model::query();
    }

    protected function query($model, $query, $removed)
    {
        if ($removed)
            return $this->queryDeleted($model, $query);

        if ($this->date) {

            $query = $query->where($this->getFieldName($model), '>=', $this->date);
        }

        return $query;
    }

    protected function getFieldName($model, $field = 'updated_at')
    {
        $model = strtolower(class_basename($model));

        if (request()->isClientRoute() && $prefix = config('synchronize.clients.fields.' . $model))
            return $prefix . $field;
        return $field;

    }

    protected function queryDeleted($model, $query)
    {

        if ($this->isSoftDeletes($model)) {
            $query = $model::onlyTrashed();
            return $query->where($this->getFieldName($model, 'deleted_at'), '>=', $this->date);

        } elseif ($this->isAuditable($model)) {

            /** @var Audit $audit */
            $audit = config('audit.implementation', Audit::class);

            $alias = (new $model)->getMorphClass();

            $query = $audit::where('event', 'deleted')
                ->where('auditable_type', $alias);

            return $query->where('updated_at', '>=', $this->date);
        }

        return $query->whereNull('id');
    }

    protected function isSoftDeletes($model): bool
    {
        return method_exists($model, 'initializeSoftDeletes');
    }

    protected function isAuditable($model): bool
    {
        return method_exists($model, 'audits');
    }

    protected function resolve($model, $result, bool $removed): AnonymousResourceCollection|Collection
    {
        if ($removed) {
            return $result->pluck($this->isSoftDeletes($model) || !$this->isAuditable($model) ? 'id' : 'auditable_id');
        }

        $model = class_basename($model);
        /** @var JsonResource|null $resource */

        if (request()->isAdminRoute()) {
            if (!($resource = config('synchronize.resources.' . strtolower(Str::plural($model))))) {
                $path = "App\\Http\\Resources\\{$model}Resource";
                if (class_exists($path))
                    $resource = $path;
            }
        } elseif (request()->isDelegateRoute()) {
            if (!($resource = config('synchronize.delegate.resources.' . strtolower(Str::plural($model))))) {
                $path = "App\\Http\\Resources\\Delegate\\{$model}Resource";
                if (class_exists($path))
                    $resource = $path;
            }
        } else {
            if (!($resource = config('synchronize.clients.resources.' . strtolower(Str::plural($model))))) {
                $path = "App\\Http\\Resources\\Client\\{$model}Resource";
                if (class_exists($path))
                    $resource = $path;
            }
        }

        if ($resource)
            return $resource::collection($result);

        return $result;
    }
}

