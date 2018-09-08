<?php

namespace App;


class Date extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function topic ()
    {
        return $this->belongsTo(Topic::class);
    }

}
