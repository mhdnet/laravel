<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanResource;
use App\Models\Account;
use App\Models\Plan;
use DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Throwable;
use Validator;

class PlanController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return PlanResource::collection(Plan::paginate());
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Plan::class);

        $request->validate([
            'governorates' => 'array',
            'governorates.*.id' => 'required|distinct|exists:governorates,id',
            'governorates.*.fare' => 'required|numeric|min:1000',
        ]);

        $plan = Plan::create($request->all());

        if ($request->filled('governorates')) {
            $governorates = $request->collect('governorates')->mapWithKeys(function ($item) {
                return [$item['id'] => Arr::only($item, 'fare')];
            })->all();

            $plan->governorates()->attach($governorates);
        }

        return  Response::synchronize();
    }


    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(Request $request, Plan $plan)
    {
        $this->authorize('update', $plan);

        $request->validate([
            'governorates' => 'nullable|array',
            'governorates.*.id' => 'required|distinct|exists:governorates,id',
            'governorates.*.fare' => 'required|numeric|min:1000',
        ]);

        $plan->update($request->all());

        $request->whenHas('governorates', function ($governorates = []) use ($plan) {

            $governorates = collect($governorates)->mapWithKeys(function ($item) {
                return [$item['id'] => Arr::only($item, 'fare')];
            })->all();

            $plan->governorates()->sync($governorates);

            $plan->touch();
        });

        return  Response::synchronize();
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(Request $request, Plan $plan)
    {
        $this->authorize('delete', $plan);

        if ($plan->accounts()->exists()) {

            if(Validator::make($request->only('replace'),
                ['replace' => 'required|exists:plans,id|not_in:' . $plan->id,]
            )->fails()) {
                abort(428);
            }

            $newId = $request->get('replace');

            DB::transaction(function () use ($plan, $newId) {
                Account::withTrashed()->where('plan_id', $plan->id)
                    ->lazy()->each(function (Account $account) use ($newId) {
                        $account->update(['plan_id' => +$newId]);
                    });

                if ($plan->is_default) {
                    tap(Plan::find($newId), fn($plan) => $plan->is_default = true)->save();
                }

                $plan->delete();
            });
        } else
            $plan->delete();

        return  Response::synchronize();
    }
}
