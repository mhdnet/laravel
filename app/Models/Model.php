<?php

namespace App\Models;

use App\Traits\HasSynchronize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
class Model extends BaseModel
{
    use HasFactory, HasSynchronize;

    public function getPerPage(): int
    {
        return 50;
    }
}
