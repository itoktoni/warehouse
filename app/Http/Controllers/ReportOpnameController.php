<?php

namespace App\Http\Controllers;

use App\Dao\Models\Barang;
use App\Dao\Models\Keluar;
use App\Dao\Models\KeluarDetail;
use App\Dao\Models\Opname;
use App\Dao\Models\OpnameDetail;
use App\Http\Controllers\Core\ReportController;
use Illuminate\Http\Request;

class ReportOpnameController extends ReportController
{
    public $data;

    public function __construct(Opname $model)
    {
        $this->model = $model::getModel();
    }

    protected function share($data = [])
    {
        $opname = Opname::get()->map(function($item){
            return [
                'id' => $item->field_primary,
                'name' => $item->opname_mulai . ' - ' . $item->opname_selesai . ' ID : ' . $item->field_primary,
            ];
        })->pluck('name', 'id')->toArray();

        $view = [
            'opname' => $opname,
            'model' => false,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getData()
    {
        $query = OpnameDetail::select('*')->where('odetail_id_opname', request()->get('opname_id'));

        return $query->get();
    }

    public function getPrint(Request $request)
    {
        set_time_limit(0);

        $this->data = $this->getData($request);
        $model = Opname::find(request()->get('opname_id'));

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data,
            'model' => $model,
        ]));
    }
}
