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
    protected $primaryKey = 'masuk_code';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true;

    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    const CREATED_AT = 'masuk_created_at';
    const UPDATED_AT = 'masuk_updated_at';
    const CREATED_BY = 'masuk_created_by';
    const UPDATED_BY = 'masuk_updated_by';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['masuk_code', 'masuk_no_po', 'masuk_tanggal_pengiriman', 'masuk_no_pengiriman', 'masuk_id_supplier', 'masuk_tanggal', 'masuk_catatan', 'masuk_created_at', 'masuk_updated_at', 'masuk_created_by', 'masuk_updated_by'];

    public function has_supplier()
    {
        return $this->hasOne(Supplier::getModel(), Supplier::field_primary(), 'masuk_id_supplier');
    }

    public function has_details()
    {
        return $this->hasMany(MasukDetail::class, 'masuk_detail_code_masuk', 'masuk_code');
    }

    public static function field_name()
    {
        return 'masuk_tanggal';
    }

    public function fieldSearching()
    {
        return self::field_name();
    }

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }

    public static function boot()
    {
        parent::creating(function ($model) {
            if (empty($model->{Masuk::field_primary()})) {
                $model->{Masuk::field_primary()} = unic(5).date('Ymd');
            }
        });
        parent::boot();
    }
}