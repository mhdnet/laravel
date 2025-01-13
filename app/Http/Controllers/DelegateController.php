<?php

namespace App\Http\Controllers;

use App\Constants\AccountRoles;
use App\Models\Business;
use App\Models\Delegate;
use Illuminate\Http\Request;
use App\Http\Resources\DelegateResource;
use Illuminate\Http\Response;

class DelegateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DelegateResource::collection(Delegate::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'business' => 'nullable|exists:App\Models\Business,id',
            'is_owner' => 'nullable|boolean',
            'fare' => 'required|numeric|min:0',
        ]);

        $delegate = new Delegate($request->all());

        $delegate->phone_verified_at = $request->filled('phone') ? now() : null;
        $delegate->email_verified_at = $request->filled('email') ? now() : null;

        $delegate->save();

        if ($request->filled('business')) {

//            $businessHasOwner = Business::find($request->input('business'))
//                ->users()
//                ->wherePivot('role', AccountRoles::OWNER)
//                ->exists();
//
//            $isOwner = $request->input('is_owner', !$businessHasOwner);

            $delegate->attachToBusiness($request->input('fare'), $request->input('business'), $request->boolean('is_owner'));
        } else {
            $delegate->attachToBusiness($request->input('fare'));
        }

        return  Response::synchronize();
    }


    public function update(Request $request, Delegate $delegate)
    {
        $request->validate([
            'fare' => 'nullable|numeric|min:0',
        ]);

        $delegate->forceFill($request->only(['name', 'phone', 'email', 'password', 'active']))
            ->save();

        if($request->filled('fare'))
        {
            $delegate->attachToBusiness($request->input('fare'));
        }

        return  Response::synchronize();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delegate $delegate)
    {
        // TODO: add delete user logic
        return  Response::synchronize();
    }
}
