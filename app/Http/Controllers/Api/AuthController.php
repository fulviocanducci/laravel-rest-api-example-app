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

    public function getUser()
    {
        return response()->json(auth(AuthController::api)->user());
    }

    public function postSignout()
    {
        auth(AuthController::api)->logout();
        return response()->json(['message' => 'User loged out']);
    }

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
