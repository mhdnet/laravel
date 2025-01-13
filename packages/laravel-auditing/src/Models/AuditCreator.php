<?php

namespace OwenIt\Auditing\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AuditCreator extends Model
{
   public $timestamps = false;

   protected $guarded = [];
    public function user()
    {
        $morphPrefix = Config::get('audit.user.morph_prefix', 'user');

        return $this->morphTo(__FUNCTION__, $morphPrefix . '_type', $morphPrefix . '_id');
    }
}
