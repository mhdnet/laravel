<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BusinessController extends Controller
{

    public function index(): AnonymousResourceCollection
    {
        return  BusinessResource::collection(Business::paginate()) ;
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Business::class);

        $request->validate([
            'governorates' => 'required|array',
            'governorates.*' => 'required|exists:governorates,id',
        ]);

        $business = Business::create($request->all());

        $business->governorates()->attach($request->input('governorates'));

        return  Response::synchronize();
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(Request $request, Business $business)
    {
        $this->authorize('update', $business);

        $request->validate([
            'governorates' => 'sometimes|array',
            'governorates.*' => 'required|exists:governorates,id',
        ]);

        $business->forceFill($request->only('name', 'phone', 'active'))->save();

        if($request->filled('governorates')) {
            $business->governorates()->sync($request->input('governorates'));
        }

        return  Response::synchronize();
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Business $business)
    {
        $this->authorize('delete', $business);

        // TODO: add logic for delete Business.
        return  Response::synchronize();
    }
}
