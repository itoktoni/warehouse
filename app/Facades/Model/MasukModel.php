<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class MasukModel extends \App\Dao\Models\Masuk
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}