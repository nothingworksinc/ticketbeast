<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\InvitationCodeGenerator;

class InvitationCode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InvitationCodeGenerator::class;
    }
}
