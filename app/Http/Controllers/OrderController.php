<?php

namespace App\Http\Controllers;


use App\Constants\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\{Account, Governorate, Location, Order, Phone};
use App\Rules\PhoneRule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\{Request, Resources\Json\AnonymousResourceCollection, Response};
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class OrderController extends Controller
{

    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection(Order::paginate());
    }

    /**
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $account = Account::find($request->input('account_id'));

        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'governorate_id' => 'required|exists:governorates,id',
            'location_id' => 'nullable|exists:locations,id',
            'location' => 'nullable|string',
            'no' => [
                'required',
                $this->uniqueOrderNo($account),
            ],
            'fare' => 'nullable|numeric',
            'value' => 'nullable|numeric',
            'address' => 'nullable|string',
            'phones' => 'array',
            'phones.*' => ['required', new PhoneRule],
        ]);

        if ($request->filled('location') && !$request->input('location_id')) {
            $location = Location::firstOrCreate(['name' => $request->input('location')]);

            Governorate::find($request->input('governorate_id'))->locations()->sync($location, false);

            $request->merge(['location_id' => $location->id]);

        }

        $order = Order::forceCreate($request->only(['account_id', 'location_id', 'governorate_id', 'no', 'fare', 'value', 'address']));

        if ($request->filled('phones')) {
            $order->syncPhones($request->input('phones'));
        }

        return  Response::synchronize();
    }


    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);


        $account = $order->account;

        $request->validate([
            'account_id' => 'sometimes|exists:accounts,id',
            'governorate_id' => 'sometimes|required|exists:governorates,id',
            'location_id' => 'sometimes|required|exists:locations,id',
            'no' => [
                'sometimes',
                'required',
                $this->uniqueOrderNo($account, $order),
            ],
            'status' => [
                'sometimes',
                'required',
                Rule::in(OrderStatus::forChange),
            ],
            'fare' => 'nullable|numeric',
            'value' => 'sometimes|required|numeric',
            'address' => 'nullable|string',
            'phones' => 'array',
            'phones.*' => ['required', new PhoneRule],
        ]);

        if ($request->filled('status')) {
            $order->status = $request->input('status');

        } else {

            $order->forceFill($request->only(['account_id', 'location_id', 'governorate_id', 'no', 'fare', 'value', 'address']));

            if ($request->filled('phones')) {

                $order->syncPhones($request->input('phones'));
            }
        }

        $order->save();

        return  Response::synchronize();
    }

    protected function uniqueOrderNo(Account $account, ?Order $order = null)
    {
        $rule = Rule::unique('orders', 'no');

        if (!is_null($order)) {
            $rule = $rule->ignoreModel($order);
        }


        return $rule->where(function (Builder $query) use ($account) {
            if ($account->different_ledger) {
                return $query->where('account_id', $account->id);
            }

            $accounts = \DB::table('accounts')->select('id')
                ->where('different_ledger', 1);

            return $query->whereNotIn('account_id', $accounts);
        });
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        // TODO: add delete logic
        return  Response::synchronize();
    }
}
