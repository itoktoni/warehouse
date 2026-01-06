<?php

namespace App\Http\Controllers;

use App\Dao\Models\Masuk;
use App\Dao\Models\Supplier;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Facades\Model\MasukModel;

class MasukController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public function __construct(MasukModel $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

     public function getData()
    {
        $query = $this->model->select(Masuk::getTableName().'.*', Supplier::field_name())->leftJoinRelationship('has_supplier');

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
        $supplier = Supplier::getOptions();

        $view = [
            'supplier' => $supplier,
            'model' => $this->model,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }
}
