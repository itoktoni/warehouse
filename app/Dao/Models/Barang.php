<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class Barang
 *
 * @property $barang_code
 * @property $barang_nama
 * @property $barang_id_category
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Barang extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'barang';
    protected $primaryKey = 'barang_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['barang_code', 'barang_nama', 'barang_id_category', 'barang_qty'];


}
