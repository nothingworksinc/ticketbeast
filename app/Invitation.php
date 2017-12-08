<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public static function findByCode($code)
    {
        return self::where('code', $code)->firstOrFail();
    }

    public function hasBeenUsed()
    {
        return $this->user_id !== null;
    }
}
