<?php
namespace App\Models;

use App\Models\Traits\DefaultEntity;
use App\Models\Traits\Filterable;
use App\Models\Traits\OptionModel;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Filterable, DefaultEntity, OptionModel;

    protected $table   = 'category';
    public $timestamps = false;

    protected $primaryKey = 'category_id';

    public $filterable = [
        'category_name' => 'Category Nama',
        'category_id' => 'Category ID',
    ];

    public $sortable = [
        'category_nama' => 'Category Nama',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['category_id', 'category_name'];

    public static function field_name()
    {
        return 'category_name';
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
