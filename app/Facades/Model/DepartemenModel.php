<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class DepartemenModel extends \App\Dao\Models\Departemen
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}