<?php

namespace App\Http\Controllers;

use App\Dao\Models\Barang;
use App\Dao\Models\Keluar;
use App\Dao\Models\KeluarDetail;
use App\Http\Controllers\Core\ReportController;
use Illuminate\Http\Request;

class ReportTransaksiKeluarController extends ReportController
{
    public $data;

    public function __construct(Barang $model)
    {
        $this->model = $model::getModel();
    }

    public function getData()
    {
        $query = KeluarDetail::select(KeluarDetail::getTableName() . '.*', Keluar::getTableName() . '.*', Barang::field_primary(), Barang::field_name())
            ->leftJoinRelationship('has_keluar')
            ->leftJoinRelationship('has_barang')
            ;

        return $query->get();
    }

    public function getPrint(Request $request)
    {
        set_time_limit(0);

        $this->data = $this->getData($request);

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data,
        ]));
    }
}
