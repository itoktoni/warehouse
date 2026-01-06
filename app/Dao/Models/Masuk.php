<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class Masuk
 *
 * @property $masuk_code
 * @property $masuk_no_po
 * @property $masuk_tanggal_pengiriman
 * @property $masuk_no_pengiriman
 * @property $masuk_id_supplier
 * @property $masuk_tanggal
 * @property $masuk_catatan
 * @property $masuk_created_at
 * @property $masuk_updated_at
 * @property $masuk_created_by
 * @property $masuk_updated_by
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Masuk extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'masuk';
    protected $primaryKey = 'masuk_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['masuk_code', 'masuk_no_po', 'masuk_tanggal_pengiriman', 'masuk_no_pengiriman', 'masuk_id_supplier', 'masuk_tanggal', 'masuk_catatan', 'masuk_created_at', 'masuk_updated_at', 'masuk_created_by', 'masuk_updated_by'];


}
