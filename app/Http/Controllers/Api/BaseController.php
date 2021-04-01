<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Utils\Items;
use Illuminate\Support\Facades\Validator;

//https://github.com/DarkaOnLine/L5-Swagger/wiki/Installation-&-Configuration

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi Description",
 *      @OA\Contact(
 *          email="fulviocanducci@hotmail.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * 
 * @OA\Parameter(
 *  name="id",
 *  description="Id",
 *  required=true,
 *  in="path",
 *  @OA\Schema(
 *      type="integer"
 *  )
 * )
 * 
 */

abstract class BaseController extends Controller
{
    protected $model;
    protected $validations;

    public function __construct($model)
    {
        $this->model = $model;
        $this->validations = new Items();
    }

    public function validated(array $values, array &$errors, array $excepts = [])
    {
        $validator = Validator::make($values, $this->validations->except($excepts));
        $errors = $validator->errors();
        return !$validator->fails();
    }

    public function ok($value)
    {
        return response()->json($value, 200);
    }

    public function okDeleted()
    {
        return response()->json(['status' => 'Successfully deleted'], 200);
    }

    public function notFound()
    {
        return response()->json(['status' => 'Not Found'], 404);
    }

    public function created($value)
    {
        return response()->json($value, 201);
    }

    public function badRequest($value)
    {
        return response()->json($value, 400);
    }
}
