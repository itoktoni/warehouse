<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class SupplierModel extends \App\Dao\Models\Supplier
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}