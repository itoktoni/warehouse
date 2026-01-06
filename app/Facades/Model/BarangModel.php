<?php

namespace App\Facades\Model;

use Illuminate\Support\Facades\Facade;

class BarangModel extends \App\Dao\Models\Barang
{
    protected static function getFacadeAccessor()
    {
        return getClass(__CLASS__);
    }
}