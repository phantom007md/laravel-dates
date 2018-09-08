<?php

namespace App;


class Payment extends Model
{
    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function date ()
    {
        return $this->hasOne(Date::class);
    }
}

