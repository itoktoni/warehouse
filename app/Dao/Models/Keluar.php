<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class Keluar
 *
 * @property $keluar_code
 * @property $keluar_no_po
 * @property $keluar_tanggal_pengiriman
 * @property $keluar_no_pengiriman
 * @property $keluar_id_departemen
 * @property $keluar_tanggal
 * @property $keluar_catatan
 * @property $keluar_created_at
 * @property $keluar_updated_at
 * @property $keluar_created_by
 * @property $keluar_updated_by
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Keluar extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'keluar';
    protected $primaryKey = 'keluar_code';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true;

    protected $dates = [
        'keluar_tanggal',
        self::CREATED_AT,
        self::UPDATED_AT,
    ];

    protected $filters = [
        'filter',
        'keluar_id_departemen',
        'start_date',
        'end_date',
    ];

    const CREATED_AT = 'keluar_created_at';
    const UPDATED_AT = 'keluar_updated_at';
    const CREATED_BY = 'keluar_created_by';
    const UPDATED_BY = 'keluar_updated_by';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['keluar_code', 'keluar_no_po', 'keluar_tanggal_pengiriman', 'keluar_no_pengiriman', 'keluar_id_departemen', 'keluar_tanggal', 'keluar_catatan', 'keluar_created_at', 'keluar_updated_at', 'keluar_created_by', 'keluar_updated_by'];

    public function has_departemen()
    {
        return $this->hasOne(Departemen::getModel(), Departemen::field_primary(), 'keluar_id_departemen');
    }

    public function has_details()
    {
        return $this->hasMany(KeluarDetail::class, 'keluar_detail_code_keluar', 'keluar_code');
    }

    public static function field_name()
    {
        return 'keluar_tanggal';
    }

    public function fieldSearching()
    {
        return self::field_name();
    }

    public function start_date($query)
    {
        $date = request()->get('start_date');
        if ($date) {
            $query = $query->whereDate('keluar_tanggal', '>=', $date);
        }

        return $query;
    }

    public function end_date($query)
    {
        $date = request()->get('end_date');

        if ($date) {
            $query = $query->whereDate('keluar_tanggal', '<=', $date);
        }

        return $query;
    }

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }

    public static function boot()
    {
        parent::creating(function ($model) {
            if (empty($model->{Keluar::field_primary()})) {
                $model->{Keluar::field_primary()} = unic(5).date('Ymd');
            }
        });
        parent::boot();
    }
}