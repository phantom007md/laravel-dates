<?php

namespace App;


class Discount extends Model
{
    public function getRouteKeyName ()
    {
        return 'code';
    }

    public function use ()
    {
        $this->active = false;
        return $this->save();
    }

    public function calc ($price)
    {
        if ($this->active) {
            return floor($price - ($price * $this->discount / 100));
        }
        return 0;
    }

    public function payment ()
    {
        return $this->belongsTo(Payment::class);
    }
}
