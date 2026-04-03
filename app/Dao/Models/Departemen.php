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

    protected $filters = [
        'filter',
        'departemen_nama',
        'departemen_pic',
        'departemen_telp',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['departemen_id', 'departemen_nama', 'departemen_pic', 'departemen_telp'];

    public static function field_name()
    {
        return 'departemen_nama';
    }

    public function fieldSearching()
    {
        return self::field_name();
    }

    public function getFieldNameAttribute()
    {
        return $this->{$this->field_name()};
    }

    public function department_nama($query)
    {
        $name = request()->get('department_nama');
        if ($name) {
            $query = $query->where('department_nama', 'like', "%$name%");
        }

        return $query;
    }

    public function department_pic($query)
    {
        $pic = request()->get('department_pic');
        if ($pic) {
            $query = $query->where('department_pic', 'like', "%$pic%");
        }

        return $query;
    }

    public function department_telp($query)
    {
        $telp = request()->get('department_telp');
        if ($telp) {
            $query = $query->where('department_telp', 'like', "%$telp%");
        }

        return $query;
    }
}
