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

class KeluarController extends MasterController
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

    public function postCreate(Request $request)
    {
        // Check if request is coming from Livewire (has scanned_items)
        if ($request->has('scanned_items')) {
            return $this->handleMasukWithScannedItems($request, null);
        }

        // Use the default CreateFunction for regular form submissions
        return (new class { use CreateFunction; })->postCreate($request, app(\App\Services\Master\CreateService::class));
    }

    public function postUpdate($code, Request $request)
    {
        // Check if request is coming from Livewire (has scanned_items)
        if ($request->has('scanned_items')) {
            return $this->handleMasukWithScannedItems($request, $code);
        }

        // Use the default UpdateFunction for regular form submissions
        return (new class { use UpdateFunction; })->postUpdate($code, $request, app(\App\Services\Master\UpdateService::class));
    }

    private function handleMasukWithScannedItems($request, $code = null)
    {
        DB::beginTransaction();

        try {
            // Remove scanned_items from request data to prevent it from being saved to masuk table
            $scannedItems = json_decode($request->get('scanned_items', '[]'), true);
            $requestData = $request->except(['scanned_items']);

            if ($code) {
                // Update existing record
                $masuk = $this->model->findOrFail($code);
                $masuk->update($requestData);
            } else {
                // Create new record
                $masuk = $this->model->create($requestData);
            }

            // Delete existing masuk detail records if updating
            if ($code) {
                MasukDetail::where('masuk_detail_code_masuk', $masuk->masuk_code)->delete();
            }

            // Create masuk detail records if any scanned items exist
            if (!empty($scannedItems)) {
                foreach ($scannedItems as $item) {
                    MasukDetail::create([
                        'masuk_detail_code_masuk' => $masuk->masuk_code,
                        'masuk_detail_code_barang' => $item['barang_code'],
                        'masuk_detail_qty' => $item['qty']
                    ]);
                }
            }

            DB::commit();

            // Return success response
            if ($code) {
                session()->flash('success', 'Data updated successfully');
            } else {
                session()->flash('success', 'Data created successfully');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
