<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class Departemen
 *
 * @property $departemen_id
 * @property $departemen_nama
 * @property $departemen_pic
 * @property $departemen_telp
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Departemen extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'departemen';
    protected $primaryKey = 'departemen_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['departemen_id', 'departemen_nama', 'departemen_pic', 'departemen_telp'];


}
