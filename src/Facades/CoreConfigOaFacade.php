<?php

namespace Bulbalara\CoreConfigOa\Facades;

use Illuminate\Support\Facades\Facade;

class CoreConfigOaFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bl.config.config_oa';
    }
}
