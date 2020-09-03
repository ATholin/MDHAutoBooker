<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Kronox extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'kronox';
    }
}
