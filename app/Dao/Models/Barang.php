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
    protected $primaryKey = 'barang_code';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $filters = [
        'filter',
        'barang_code',
        'barang_nama',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['barang_code', 'barang_nama', 'barang_id_category', 'barang_qty'];

    public static function field_name()
    {
        return 'barang_nama';
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
            if (empty($model->{Barang::field_primary()})) {
                $model->{Barang::field_primary()} = unic(5).date('Ymd');
            }
        });
        parent::boot();
    }

    public function has_category()
    {
        return $this->hasOne(Category::getModel(), Category::field_primary(), 'barang_id_category');
    }
}
