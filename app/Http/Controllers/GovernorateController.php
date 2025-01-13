<?php

namespace App\Http\Controllers;


use App\Http\Resources\GovernorateResource;
use App\Models\Governorate;
use App\Traits\HasGovernorate;
use App\Traits\HasManyGovernorate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Throwable;
use Validator;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return GovernorateResource::collection(Governorate::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Governorate::class);

        $location = Governorate::create($request->all());

        return  Response::synchronize();
    }


    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(Request $request, Governorate $governorate)
    {
        $this->authorize('update', $governorate);

        $governorate->update($request->all());

        return  Response::synchronize();
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(Request $request, Governorate $governorate)
    {

        if(Validator::make($request->only('replace'),
            ['replace' => 'required|exists:governorates,id|not_in:' . $governorate->id,]
        )->fails()) {
            abort(428);
        }

        $newId = +$request->input('replace');

        \DB::transaction(function () use ($governorate, $newId) {

            modelTraits(HasGovernorate::class)
                ->each(function ($class) use ($governorate, $newId) {
                    if (method_exists($class, 'forceDelete'))
                        $query = $class::withTrashed();
                    else
                        $query = $class::query();

                    $query->where('governorate_id', $governorate->id)->lazy()
                        ->each(function (Model $model) use ($newId) {
                            $model->update(['governorate_id' => $newId]);
                        });
                });

            modelTraits(HasManyGovernorate::class)
                ->each(function ($class) use ($governorate, $newId) {
                    if (method_exists($class, 'forceDelete'))
                        $query = $class::withTrashed();
                    else
                        $query = $class::query();

                    $query->whereHas('governorates', function (Builder $query) use ($governorate) {
                        $query->whereKey($governorate->id);
                    })->lazy()->each(function ($model) use ($governorate, $newId) {
                            $model->governorates()->detach($governorate);
                            $model->governorates()->sync($newId, false);
                        });
                });

            $governorate->delete();
        });

        return  Response::synchronize();
    }
}
