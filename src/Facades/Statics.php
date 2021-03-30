<?php

namespace Paksuco\Statics\Facades;

use Illuminate\Support\Facades\Facade;

class Statics extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'statics';
    }
}
