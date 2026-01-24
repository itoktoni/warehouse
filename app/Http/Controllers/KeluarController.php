<?php

namespace App\Http\Controllers;

use App\Dao\Models\Departemen;
use App\Dao\Models\Keluar;
use App\Dao\Models\KeluarDetail;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Http\Controllers\Core\MasterController;
use Plugins\Response;

class KeluarController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public function __construct(Keluar $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

     public function getData()
    {
        $query = $this->model->select(Keluar::getTableName().'.*', Departemen::field_name())
            ->leftJoinRelationship('has_departemen')
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
        $departemen = Departemen::getOptions();

        $view = [
            'departemen' => $departemen,
            'model' => $this->model,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getDelete()
    {
        $code = request()->get('code');
        $data = self::$service->delete($this->model, $code);

        KeluarDetail::where('keluar_detail_code_keluar', $code)->delete();

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

        KeluarDetail::whereIn('keluar_detail_code_keluar', $code)->delete();

        return $data;
    }

    public function getPrint($code)
    {
        $detail = KeluarDetail::with(['has_barang'])->where('keluar_detail_code_keluar', $code)->get();
        $model = $this->model->with(['has_departemen'])->where($this->model->getKeyName(), $code)->firstOrFail();
        $departemen = $model->has_departemen;

        return moduleView(modulePathForm('print', 'keluar'), $this->share([
            'detail' => $detail,
            'model' => $model,
            'departemen' => $departemen,
            'print' => true,
        ]));
    }
}
