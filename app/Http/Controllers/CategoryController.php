<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\CrudController;
use App\Models\Category;

class CategoryController extends CrudController
{
    public function __construct(Category $model)
    {
        $this->model = $model::getModel();
    }
}
