<?php



namespace Illuminate\Http {
    /**
     * @method bool isAdminRoute
     * @method bool isDelegateRoute
     * @method bool isClientRoute
     * @method \App\Models\Account|\App\Models\Business|null account
     * @method \Carbon\Carbon|null lastUpdates
     */

    class Request
    {

    }

    /**
     * @method JsonResponse success
     * @method JsonResponse failure
     * @method JsonResponse synchronize
     */
    class Response {}
}


namespace OwenIt\Auditing\Models {
    /**
     * @method static \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
     */
    class Audit {}
}

