<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends Eloquent
{
    protected $guarded = [];

    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
