<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\TicketCodeGenerator;

class TicketCode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TicketCodeGenerator::class;
    }

    protected static function getMockableClass()
    {
        return static::getFacadeAccessor();
    }
}
