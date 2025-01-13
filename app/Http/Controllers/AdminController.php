<?php

namespace App\Http\Controllers;

use App\Constants\RolesName;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Models\Business;
use App\Models\Delegate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:' . RolesName::SUPER);
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {

        return AdminResource::collection(Admin::paginate());
    }

    public function store(Request $request)
    {
        $request->validate([
            'is_super' => 'nullable|boolean',
            'is_delegate' => 'nullable|boolean',
            'fare' => 'required_if:is_delegate,true|numeric|min:0',
            'permissions' => 'nullable|array',
            'permissions.*' => 'required|exists:permissions,name',
        ]);

        $admin = new Admin($request->all());

        $admin->phone_verified_at = $request->filled('phone') ? now() : null;
        $admin->email_verified_at = $request->filled('email') ? now() : null;

        $admin->save();

        $isSuper = $request->input('is_super', false);

        if ($isSuper) {
            $admin->assignRole(RolesName::SUPER);
        }

        if ($request->filled('permissions') && !$isSuper) {
            $admin->givePermissionTo($request->input('permissions'));
        }


        if ($request->input('is_delegate', false)) {

            $delegate = $admin->asDelegate();

            $delegate->assignRole(RolesName::DELEGATE);

            $delegate->attachToBusiness($request->input('fare'));

        }

        return Response::synchronize();
    }


    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'is_super' => 'sometimes|boolean',
            'is_delegate' => 'sometimes|boolean',
            'fare' => 'sometimes|required_if:is_delegate,true|numeric|min:0',
            'permissions' => 'array',
            'permissions.*' => 'required|exists:permissions,name',
        ]);


        $isSuper = $request->input('is_super', false);

        if ($request->filled('permissions') && !$isSuper) {
            $admin->syncPermissions($request->input('permissions'));
        }

        if ($isSuper) {
            $admin->assignRole(RolesName::SUPER);
            $admin->permissions()->detach();
        } elseif ($admin->hasRole(RolesName::SUPER)) {
            $admin->removeRole(RolesName::SUPER);
        }


        $delegateChanged = false;

        if ($request->filled('is_delegate')) {

            $delegate = $admin->asDelegate();


            if ($request->input('is_delegate', false)) {
                $delegate->assignRole(RolesName::DELEGATE);
                $delegate->attachToBusiness($request->input('fare'));
                $delegateChanged = true;
            } elseif ($delegate->hasRole(RolesName::DELEGATE)) {

                $delegate->removeRole(RolesName::DELEGATE);

                Business::default()->users()->detach($delegate);
                $delegateChanged = true;
            }
        }

        $admin->forceFill($request->only(['name', 'phone', 'email', 'password', 'active']))
            ->save(['touch' => false]);

        $admin->touch();

        if($delegateChanged) {
            $admin->asDelegate()->touch();
        }
        return Response::synchronize();
    }


    public function destroy(Admin $admin)
    {
        // TODO: add delete user logic
        return Response::synchronize();
    }
}
