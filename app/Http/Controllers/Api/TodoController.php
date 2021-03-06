<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends BaseController
{
    /**
     * @OA\Schema(
     *   schema="todo",
     *   allOf={
     *     @OA\Schema(
     *       @OA\Property(property="id", type="integer"),
     *       @OA\Property(property="description", type="string"),
     *       @OA\Property(property="done", type="string", format="boolean")
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
     *     path="/api/v1/todo",
     *     description="Todos - Get All",
     *     tags={"Todo"},
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *      response=200,
     *      description="Ok", 
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
     *     description="Todo - Get By Id",
     *     tags={"Todo"},
     *     security={{"bearer_token":{}}}, 
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(
     *      response=200,
     *      description="Ok",
     *      @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/todo")
     *      )
     *     ),   
     *     @OA\Response(response=400, description="Bad request"), 
     *     @OA\Response(response=401, description="Unauthorized"), 
     *     @OA\Response(response=404, description="Not Found"),
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

    /**
     * @OA\Post(
     *     path="/api/v1/todo",
     *     description="Todo - Created",
     *     tags={"Todo"},
     *     security={{"bearer_token":{}}}, 
     *     @OA\RequestBody(
     *      required=true,
     *      description="Todo",
     *      @OA\JsonContent(
     *          ref="#/components/schemas/todo"   
     *      ),
     *     ),     
     *     @OA\Response(
     *      response=200,
     *      description="Successful",
     *      @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/todo")
     *      )
     *     ),   
     *     @OA\Response(response=400, description="Bad request"), 
     *     @OA\Response(response=401, description="Unauthorized"), 
     * )
     */
    public function postTodo(Request $request)
    {
        $errors = array();
        if ($this->validated($request->all(), $errors, ['id'])) {
            $result = $this->model->create($request->all());
            return $this->created($result);
        }
        return $this->badRequest($errors);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/todo/{id}",
     *     description="Todo - Update",
     *     tags={"Todo"},
     *     security={{"bearer_token":{}}}, 
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\RequestBody(
     *      required=true,
     *      description="Todo",
     *      @OA\JsonContent(
     *          ref="#/components/schemas/todo"   
     *      ),
     *     ),     
     *     @OA\Response(
     *      response=200,
     *      description="Successful",
     *      @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/todo")
     *      )
     *     ),   
     *     @OA\Response(response=400, description="Bad request"), 
     *     @OA\Response(response=401, description="Unauthorized"), 
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/v1/todo/{id}",
     *     description="Todo - Delete",
     *     tags={"Todo"},
     *     security={{"bearer_token":{}}}, 
     *     @OA\Parameter(ref="#/components/parameters/id"),
     *     @OA\Response(
     *      response=200,
     *      description="Successful"     
     *     ),   
     *     @OA\Response(response=400, description="Bad request"), 
     *     @OA\Response(response=401, description="Unauthorized"), 
     *     @OA\Response(response=404, description="Not Found"),
     * )
     */
    public function delTodo($id)
    {
        $result = $this->model->find($id);
        if ($result) {
            $result->delete();
            return $this->okDeleted();
        }
        return $this->notFound();
    }
}
