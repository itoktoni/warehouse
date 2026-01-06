<?php

namespace App\Dao\Models;

use App\Dao\Models\Core\SystemModel;


/**
 * Class Supplier
 *
 * @property $supplier_id
 * @property $supplier_nama
 * @property $supplier_telp
 * @property $supplier_email
 * @property $supplier_alamat
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class Supplier extends SystemModel
{
    protected $perPage = 20;
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['supplier_id', 'supplier_nama', 'supplier_pic', 'supplier_telp', 'supplier_email', 'supplier_alamat'];

    public static function field_name()
    {
        return 'supplier_nama';
    }

    public function fieldSearching()
    {
        return self::field_name();
    }

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }
}
