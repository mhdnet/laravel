<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Models\Governorate;
use App\Models\Location;

use App\Traits\HasLocation;

use DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Throwable;
use Validator;

class LocationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return LocationResource::collection(Location::paginate());
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Location::class);

        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name' => 'required|string|min:3',
            'aliases' => 'nullable|array',
            'trusted' => 'sometimes|boolean',
        ]);

        if (!$request->filled('trusted')) {
            $request->merge(['trusted' => true]);
        }

        $location = Location::create($request->all());

        $location->governorates()->attach($request->input('governorate_id'));

        return  Response::synchronize();
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function update(Request $request, Location $location)
    {
        $this->authorize('update', $location);

        $location->forceFill($request->all())->save();

        return  Response::synchronize();
    }

    /**
     * Remove (and replace) the location.
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(Request $request, Location $location)
    {
        $this->authorize('delete', $location);

        if (Validator::make($request->only('replace'),
            ['replace' => 'required|exists:locations,id|not_in:' . $location->id,]
        )->fails()) {
            abort(428);
        }


        $newId = $request->input('replace');


        DB::transaction(function () use ($location, $newId) {

            // update all model hav location_id.
            modelTraits(HasLocation::class)
                ->each(function ($class) use ($location, $newId) {
                    if (method_exists($class, 'forceDelete'))
                        $query = $class::withTrashed();
                    else
                        $query = $class::query();

                    $query->where('location_id', $location->id)
                        ->lazy()
                        ->each(function (Model $model) use ($newId) {
                            $model->update(['location_id' => $newId]);
                        });

                });

            Governorate::withTrashed()->whereHas('locations', function (Builder $query) use ($location) {
                $query->whereKey($location->id);
            })->lazy()->each(function (Governorate $governorate) use ($location, $newId) {
                $governorate->locations()->detach($location);
                $governorate->locations()->sync($newId, false);
            });


            $location->delete();
            Location::find($newId)->touch();
        });

        return  Response::synchronize();
    }
}
