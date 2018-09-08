<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public function dates ()
    {
        return $this->hasMany(Date::class);
    }
}
