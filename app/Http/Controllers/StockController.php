<?php

namespace App\Http\Controllers;

use App\Dao\Models\Barang;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Facades\Model\BarangModel;
use App\Dao\Models\Category;

class StockController extends BarangController
{
    use CreateFunction, UpdateFunction;

    public function __construct(BarangModel $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    public function getData()
    {
        $query = Barang::select(Barang::getTableName().'.*', Category::field_name())
            ->leftJoinRelationship('has_category')
            ->orderBy('barang_created_at', 'DESC')
            ->filter();

        if(request('available') == 1)
        {
            $query->where('barang_qty', '>', 0);
        }

        $page = env('PAGINATION_NUMBER', 10);
        $query = env('PAGINATION_SIMPLE') ? $query->simplePaginate($page) : $query->fastPaginate($page);

        return $query;
    }

    protected function share($data = [])
    {
        $category = Category::getOptions();
        $barang = Barang::getOptions();

        $view = [
            'barang' => $barang,
            'category' => $category,
            'model' => $this->model,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

}
