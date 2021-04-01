<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected const api = 'api';

    /**
     * @OA\Schema(
     *   schema="auth_login",
     *   allOf={
     *     @OA\Schema(
     *       @OA\Property(property="access_token", type="string"),
     *       @OA\Property(property="token_type", type="string"),
     *       @OA\Property(property="expires_minute_in", type="string"),
     *       @OA\Property(
     *          property="expires_date_in", 
     *          type="object", 
     *          @OA\Property(property="date", type="string", format="datetime"),
     *          @OA\Property(property="timezone_type", type="integer"),
     *          @OA\Property(property="timezone", type="string")           
     *       ),
     *       @OA\Property(
     *          property="user", 
     *          type="object", 
     *          @OA\Property(property="id", type="integer"),
     *          @OA\Property(property="name", type="string"),
     *          @OA\Property(property="email", type="string", format="email") 
     *       ),
     *     )
     *   }
     * )
     */

    /**
     * @OA\Post(
     *    path="/api/v1/auth/login",
     *    description="Auth Login",
     *    tags={"Authentication"},
     *    @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/auth_login")
     *     )
     *    ),
     *    @OA\Response(response=400, description="Bad request"),
     *    @OA\RequestBody(
     *      required=true,
     *      description="Pass user credentials",
     *      @OA\JsonContent(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="******")     
     *      ),
     *    )
     *   ),
     * )
     */
    public function postLogin(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        if (!$token = auth(AuthController::api)->attempt($validate->validated())) {
            return response()->json(['Auth error' => 'Unauthorized'], 401);
        }

        return $this->generateToken($token);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/user",
     *     description="Auth User",
     *     tags={"Authentication"},
     *     security={{"bearer_token":{}}},      
     *     @OA\Response(
     *      response=200,
     *      description="Successful operation"
     *     ),   
     *     @OA\Response(response=400, description="Bad request"), 
     *     @OA\Response(response=401, description="Unauthorized"), 
     * )
     */
    public function getUser()
    {
        return response()->json(auth(AuthController::api)->user());
    }

    /**
     * @OA\Post(
     *    path="/api/v1/auth/logout",
     *    description="Auth User Logout",
     *    tags={"Authentication"},
     *    security={{"bearer_token":{}}}, 
     *    @OA\Response(
     *     response=200,
     *     description="Successful operation"
     *    ),
     *    @OA\Response(response=400, description="Bad request")     
     * )
     */
    public function postSignout()
    {
        auth(AuthController::api)->logout();
        return response()->json(['message' => 'User loged out']);
    }

    /**
     * @OA\Post(
     *    path="/api/v1/auth/refresh",
     *    description="Auth Refresh Token",
     *    tags={"Authentication"},
     *    security={{"bearer_token":{}}}, 
     *    @OA\Response(
     *     response=200,
     *     description="Successful operation"
     *    ),
     *    @OA\Response(response=400, description="Bad request")     
     * )
     */
    public function postRefresh()
    {
        return $this->generateToken(auth(AuthController::api)->refresh());
    }

    protected function generateToken($token)
    {
        $date = (new DateTime('now', new DateTimeZone(config('app.timezone'))))
            ->add(new DateInterval("PT" . config('jwt.ttl') . "M"));
        $user = auth(AuthController::api)->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_minute_in' => (int)config('jwt.ttl'),
            'expires_date_in' => $date,
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]
        ]);
    }
}
