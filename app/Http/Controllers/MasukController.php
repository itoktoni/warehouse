<?php

namespace App\Http\Controllers;

use App\Dao\Models\Masuk;
use App\Dao\Models\Supplier;
use App\Dao\Models\MasukDetail;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Facades\Model\MasukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Plugins\Response;

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
        $query = $this->model->select(Masuk::getTableName().'.*', Supplier::field_name())
            ->leftJoinRelationship('has_supplier')
            ->filter();

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

    public function getDelete()
    {
        $code = request()->get('code');
        $data = self::$service->delete($this->model, $code);

        MasukDetail::where('masuk_detail_code_masuk', $code)->delete();

        return Response::redirectBack($data);
    }

    public function postTable()
    {
        if (request()->exists('delete'))
        {
            $data = $this->deleteData(request()->get('code'));
        }

        if (request()->exists('sort')) {
            $sort = array_unique(request()->get('sort'));
            $data = self::$service->sort($this->model, $sort);
        }

        return Response::redirectBack($data);
    }

    public function deleteData($code)
    {
        $code = array_unique(request()->get('code'));
        $data = self::$service->delete($this->model, $code);

        MasukDetail::whereIn('masuk_detail_code_masuk', $code)->delete();

        return $data;
    }

    public function getPrint($code)
    {
        $detail = MasukDetail::with(['has_barang'])->where('masuk_detail_code_masuk', $code)->get();
        $model = $this->model->with(['has_supplier'])->where($this->model->getKeyName(), $code)->firstOrFail();
        $supplier = $model->has_supplier;

        return moduleView(modulePathForm('print', 'masuk'), $this->share([
            'detail' => $detail,
            'model' => $model,
            'supplier' => $supplier,
            'print' => true,
        ]));
    }
}
