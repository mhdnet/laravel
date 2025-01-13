<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

if (!function_exists('modelTraits')) {
    /**
     * @param string|array|null $uses
     * @return Collection
     */
    function modelTraits(string|array $uses = null): Collection
    {
        $models = collect(File::allFiles(app_path('Models')))
            ->map(function ($file) {
                return '\\App\\Models\\' . str_replace('.php', '', $file->getRelativePathName());
            });

        if (!is_null($uses)) {
            $models = $models->filter(fn($class) => Arr::hasAny(class_uses($class), Arr::wrap($uses)));
        }

        return $models->values();
    }
}

if (!function_exists('resource_filter')) {
    /**
     * @param array $arr
     * @param array|null $keys
     * @param mixed|null $resource
     * @param Request|null $request
     * @return array
     */
    function resource_filter(array $arr, array|null $keys = null, mixed $resource = null, Request $request = null): array
    {
        /** @var Illuminate\Support\Collection $attributes */
        if ($resource && method_exists($resource, 'getModifiedAttributes') &&
             request()->routeIs('*.synchronize') &&
            ($date = request()->date('updated_at')) &&
            $attributes = $resource->getModifiedAttributes($date->toDateTimeString())) {

            if($keys) {
                $attributes = $attributes->intersect(array_keys($keys))->map(fn($item) => $keys[$item]);

            } else {
                $attributes = $attributes->intersect(array_keys($arr));
            }


            $arr = $attributes->mapWithKeys(fn($key) => [$key => Arr::get($arr, $key)])->all();
        }

        return $arr;// array_filter($arr, fn($value) => !is_null($value));
    }
}


if (!function_exists('base_order_no')) {
    function base_order_no($value): ?int
    {
        if (!is_numeric($value) || +$value < 1)
            return null;

        return floor((+$value - 1) / 50) * 50 + 1;
    }
}
if (!function_exists('resourceLoaded')) {

    function resourceLoaded($resource): \Illuminate\Support\Collection|null
    {
        return $resource instanceof \Illuminate\Database\Eloquent\Collection ? $resource : null;
    }
}
