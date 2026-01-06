<?php

namespace App\Http\Controllers;

use App\Dao\Models\Barang;
use App\Dao\Models\Category;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Facades\Model\BarangModel;

class BarangController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public function __construct(BarangModel $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    public function getData()
    {
        $query = $this->model->select(Barang::getTableName().'.*', Category::field_name())->leftJoinRelationship('has_category');

        $page = env('PAGINATION_NUMBER', 10);
        if(request()->get('page'))
        {
            $page = request()->get('page');
        }

        $query = env('PAGINATION_SIMPLE') ? $query->simplePaginate($page) : $query->fastPaginate($page);


        return $query;
    }

    protected function share($data = [])
    {
        $category = Category::getOptions();

        $view = [
            'category' => $category,
            'model' => $this->model,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }
}
