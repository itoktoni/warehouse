<?php

namespace App\Http\Controllers;

use App\Dao\Models\Barang;
use App\Dao\Models\Category;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarangController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public function __construct(Barang $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    public function getData()
    {
        $query = Barang::select(Barang::getTableName().'.*', Category::field_name())
            ->leftJoinRelationship('has_category')
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
        $category = Category::getOptions();

        $view = [
            'category' => $category,
            'model' => $this->model,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getPrint($code)
    {
        $model = Barang::find($code);
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($model->field_primary));

        $pdf = Pdf::loadView($this->template(), [
            'model' => $model,
            'qrcode' => $qrcode,
            'print' => true,
        ]);

        $pdf->setPaper([0, 0, 155, 113], 'potrait');


        // You can stream the PDF to the browser or download it
        // return $pdf->stream('invoice.pdf');
        return $pdf->stream($model->field_primary.'.pdf')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');

        return $this->views($this->template(), $this->share([
            'model' => $model,
            'print' => true,
        ]));
    }
}
