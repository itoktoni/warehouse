<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Trait\ControllerHelper;
use App\Http\Requests\Core\GeneralRequest;
use Plugins\Response;

class CrudController extends Controller
{
    use ControllerHelper;

    public $model;

    public function index()
    {
        return $this->getTable();
    }

    public function model()
    {
        return $this->model;
    }

    public function getData()
    {
        $perPage = request('perpage', 10);
        $data    = $this->model->filter(request())->paginate($perPage);
        $data->appends(request()->query());
        return $data;
    }

    public function getTable()
    {
        $data = $this->getData();

        return $this->views($this->template(), [
            'data' => $data,
        ]);
    }

    public function postTable()
    {
        if (request()->exists('delete')) {
            $code = array_unique(request()->get('code'));
            $data = $this->model->whereIn($this->model->field_primary(), $code)->delete();
        }

        return Response::redirectBack($data);
    }

    public function getCreate()
    {
        return $this->views($this->template());
    }

    public function postCreate(GeneralRequest $request)
    {
        return $this->create($request->validated());
    }

    public function getUpdate($code)
    {
        $model = $this->model->findOrFail($code);

        return $this->views($this->template(), $this->share([
            'model' => $model,
        ]));
    }

    public function postUpdate(GeneralRequest $request)
    {
        return $this->update($request);
    }

    public function getDelete($code)
    {
        $this->model = $this->model->findOrFail($code);
        $this->model->delete();

        return redirect()->route($this->module('getTable'))->with('success', 'deleted successfully');
    }
}
