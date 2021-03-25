<?php

namespace Paksuco\Statics\Facades;

use Illuminate\Support\Facades\Facade;

class StaticsFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'paksuco-statics';
    }
}
