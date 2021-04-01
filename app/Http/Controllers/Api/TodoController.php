<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends BaseController
{
    /**
     * @OA\Schema(
     *   schema="todo",
     *   allOf={
     *     @OA\Schema(
     *       @OA\Property(property="id", type="integer"),
     *       @OA\Property(property="description", type="string"),
     *       @OA\Property(property="done", type="string", format="boolean"),
     *       @OA\Property(property="created_at", type="string", format="datetime"),
     *       @OA\Property(property="updated_at", type="string", format="datetime")
     *     )
     *   }
     * )
     *
     */

    public function __construct(Todo $model)
    {
        parent::__construct($model);
        $this->validations
            ->add('id', 'required|integer')
            ->add('description', 'required|min:2')
            ->add('done', 'required');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/todos",
     *     description="Todos",
     *     tags={"Todo"},
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *      response=200,
     *      description="Successful operation", 
     *      @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *          @OA\Items(ref="#/components/schemas/todo")
     *         )
     *      )
     *     ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=401, description="Unauthorized"), 
     * )
     */
    public function getTodos()
    {
        $result = $this->model->all();
        return $this->ok($result);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/todo/{id}",
     *     description="Todo Get By Id",
     *     tags={"Todo"},
     *     security={{"bearer_token":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         description="Id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *      response=200,
     *      description="Successful operation",
     *      @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/todo")
     *      )
     *     ),   
     *     @OA\Response(response=400, description="Bad request"), 
     *     @OA\Response(response=401, description="Unauthorized"), 
     * )
     */
    public function getTodo($id)
    {
        $result = $this->model->find($id);
        if ($result) {
            return $this->ok($result);
        }
        return $this->notFound();
    }

    public function postTodo(Request $request)
    {
        $errors = array();
        if ($this->validated($request->all(), $errors, ['id'])) {
            $result = $this->model->create($request->all());
            return $this->created($result);
        }
        return $this->badRequest($errors);
    }

    public function putTodo(Request $request, $id)
    {
        $errors = array();
        if ($this->validated($request->all(), $errors)) {
            $result = $this->model->find($id);
            $result->fill($request->except('id'));
            $result->save();
            return $this->ok($result);
        }
        return $this->badRequest($errors);
    }

    public function delTodo($id)
    {
        $result = $this->model->find($id);
        if ($result) {
            $result->delete();
            return $this->ok(['status' => 'Successfully deleted']);
        }
        return $this->notFound();
    }
}
