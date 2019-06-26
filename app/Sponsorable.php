<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsorable extends Model
{

    public function slots()
    {
        return $this->hasMany(SponsorableSlot::class);
    }


    public static function findOrFailBySlug($slug)
    {
        return self::whereSlug($slug)->firstOrFail();
    }
}
