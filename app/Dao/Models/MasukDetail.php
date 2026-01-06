<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;

class MasukDetail extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'masuk_detail';
    protected $primaryKey = 'masuk_detail_id';

    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'masuk_detail_id',
        'masuk_detail_code_barang',
        'masuk_detail_code_masuk',
        'masuk_detail_qty',
    ];
}
