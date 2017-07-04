<?php

use App\Concert;

class ConcertFactory
{
    public static function createPublished($overrides = [])
    {
        $concert = factory(Concert::class)->create($overrides);
        $concert->publish();
        return $concert;
    }
}
