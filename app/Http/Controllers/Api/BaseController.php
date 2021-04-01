<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Utils\Items;
use Illuminate\Support\Facades\Validator;

abstract class BaseController extends Controller
{
    protected $model;
    protected $validations;

    public function __construct($model)
    {
        $this->model = $model;
        $this->validations = new Items();
    }

    public function validated(array $values, array $excepts, &$errors)
    {
        $validator = Validator::make($values, $this->validations->except($excepts));
        $errors = $validator->errors();
        return !$validator->fails();
    }
}
