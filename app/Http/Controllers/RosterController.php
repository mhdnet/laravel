<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatementResource;
use App\Models\Account;
use App\Models\Business;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Roster;
use App\Models\Statement;
use App\Rules\OrderExists;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class RosterController extends Controller

{
    protected string|Roster $type;

    public function __construct()
    {
        if (\request()->routeIs('*.statements.*')) {
            $this->type = Statement::class;
        } elseif (request()->routeIs('*.payments.*')) {
            $this->type = Payment::class;
        } else {
            $this->type = Roster::class;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return StatementResource::collection($this->type::with('orders')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->type);


        $request->validate([

            'account_id' => [
                'required',
                'numeric',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (($this->type == Roster::class && !Business::whereKey($value)->exists()) || !Account::whereKey($value)->exists()) {
                        $fail("validation.exists")->translate();
                    }
                },
            ],
            'orders' => 'required|array',
            'orders.*' => [
                'required',
                OrderExists::for($this->type),
            ],
        ]);

        /** @var Statement $statement */

        $statement = new $this->type;

        $statement->account()->associate($request->input('account_id'));

        $statement->addOrders($request->input('orders'));


        return Response::synchronize();

    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     * @throws \Throwable
     */
    public function update(Request $request, Roster $roster)
    {
        $this->authorize('update', $roster);

        $request->validate([
            'export' => 'sometimes|boolean',
            'delegate' => 'required_if:export,true|exists:App\Models\Delegate,id',
            'orders' => 'sometimes|required|array',
            'orders.*' => [
                'required',
                OrderExists::for($this->type, $roster),
            ],
        ]);

        if ($request->filled('export')) {
            $roster->export($request->input('delegate'));
        } elseif ($request->filled('orders')) {
            $roster->syncOrders($request->input('orders'));
        }

        return Response::synchronize();
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Roster $roster)
    {
        $this->authorize('delete', $roster);

        $roster->delete();

        return Response::synchronize();
    }


    /**
     * Receive Roster by admin from delegate.
     * @throws AuthorizationException
     */
    public function receive(Roster $roster)
    {
        $this->authorize('receive', $roster);
        return Response::synchronize();
    }
}
