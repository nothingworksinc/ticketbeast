<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\OrderConfirmationNumberGenerator;

class OrderConfirmationNumber extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OrderConfirmationNumberGenerator::class;
    }
}
