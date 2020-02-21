<?php

namespace Laravelha\Auth\Facades;

use Illuminate\Support\Facades\Facade;

class Abilities extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ha.abilities';
    }
}
