<?php

namespace App\Http\Controllers;

use App\Dao\Enums\OpnameType;
use App\Dao\Models\Barang;
use App\Dao\Models\Opname;
use App\Dao\Models\OpnameDetail;
use App\Http\Controllers\Core\MasterController;
use App\Http\Requests\OpnameRequest;
use App\Services\Master\CreateService;
use App\Services\Master\SingleService;
use App\Services\Master\UpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Plugins\Alert;
use Plugins\Response;

class OpnameController extends MasterController
{
    public function __construct(Opname $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    protected function share($data = [])
    {
        $status = OpnameType::getOptions();

        $view = [
            'model' => $this->model,
            'status' => $status,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function postCreate(OpnameRequest $request, CreateService $service)
    {
        $data = $service->save($this->model, $request);
        $id = DB::getPdo()->lastInsertId();

        $this->capture($id);

        return Response::redirectBack($data);
    }

    public function postUpdate($code, OpnameRequest $request, UpdateService $service)
    {
        $data = $service->update($this->model, $request, $code);

        return Response::redirectBack($data);
    }

    public function getData()
    {
        $query = Opname::query()
            ->addSelect([$this->model->getTable().'.*'])
            ->filter();

        $per_page = env('PAGINATION_NUMBER', 10);
        if(request()->get('per_page'))
        {
            $per_page = request()->get('per_page');
        }

        $query = env('PAGINATION_SIMPLE') ? $query->simplePaginate($per_page) : $query->fastPaginate($per_page);

        return $query;
    }

    private function capture($code)
    {
        try {

            $register = Barang::query()->get();

            $data = [];

            $date = date('Y-m-d H:i:s');
            $user = auth()->user()->id;

            foreach($register as $value)
            {
                $data[] = [
                    'odetail_id_opname' => $code,
                    'odetail_code_barang' => $value->barang_code,
                    'odetail_nama_barang' => $value->barang_nama,
                    'odetail_register' => $value->barang_qty,
                    'odetail_created_at' => $date,
                    'odetail_created_by' => $user,
                ];
            }

            if(!empty($data))
            {
                OpnameDetail::insert($data);
            }

            Alert::create("Opname berhasil dicapture !");

        } catch (\Throwable $th) {

            Alert::error($th->getMessage());
        }

        return redirect()->back();
    }

    public function getOpname($code)
    {
        $model = Opname::find($code);
        $detail = OpnameDetail::where('odetail_id_opname', $model->field_primary)->get();

        return $this->views($this->template(), $this->share([
            'model' => $model,
            'detail' => $detail,
        ]));
    }

    public function postOpname($code, Request $request, UpdateService $service)
    {
        foreach ($request->qty as $key => $value) {
            $model = OpnameDetail::find($key);
            if ($model) {
                $model->odetail_ketemu = $value['ketemu'] ?? 0;
                $model->save();
            }
        }

        Alert::update("Opname berhasil diupdate !");

        return Response::redirectBack($request->all());
    }

    public function getPrint($code)
    {
        $model = Opname::find($code);
        $detail = OpnameDetail::where('odetail_id_opname', $model->field_primary)->get();

        return $this->views($this->template(), $this->share([
            'model' => $model,
            'detail' => $detail,
            'print' => true,
        ]));
    }
}
