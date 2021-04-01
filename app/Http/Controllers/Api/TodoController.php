<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends BaseController
{
    public function __construct(Todo $model)
    {
        parent::__construct($model);
        $this->validations
            ->add('id', 'required|integer')
            ->add('description', 'required|min:2')
            ->add('done', 'required');
    }

    public function getTodos()
    {
        $result = $this->model->all();
        return response()->json($result, 200);
    }

    public function getTodo($id)
    {
        $result = $this->model->find($id);
        if ($result) {
            return response()->json($result, 200);
        }
        return response()->json(['status' => 'NotFound'], 404);
    }

    public function postTodo(Request $request)
    {
        $errors = array();
        if ($this->validated($request->all(), ['id'], $errors)) {
            $result = $this->model->create($request->all());
            return response()->json($result, 201);
        }
        return response()->json($errors, 400);
    }

    public function putTodo(Request $request, $id)
    {
        $errors = array();
        if ($this->validated($request->all(), [], $errors)) {
            $result = $this->model->find($id);
            $result->fill($request->except('id'));
            $result->save();
            return response()->json($result, 200);
        }
        return response()->json($errors, 400);
    }

    public function delTodo($id)
    {
        $result = $this->model->find($id);
        if ($result) {
            $result->delete();
            return response()->json(['status' => 'Successfully deleted'], 200);
        }
        return response()->json(['status' => 'NotFound'], 404);
    }
}
