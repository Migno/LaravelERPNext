<?php

namespace STREETEC\LaravelERPNext\Facades;

use Illuminate\Support\Facades\Facade;

class ERPNext extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'erpnext';
    }
}
