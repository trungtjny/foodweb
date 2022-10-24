<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /* public function __construct()
    {
        $this->middleware(['auth:api'], ['except' => ['login', 'register']]);
    } */
    public function login(LoginRequest $request)
    {
        /* $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } */
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = JWTAuth::attempt($request->only('email', 'password'));
            $user = User::findOrFail(Auth::id());

            return response()->json(['type' => 'success', 'token' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            return Auth::logout();
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return User::create($request->only('email', 'name', 'password', 'address'));
    }
    public function getInfo()
    {
        if (!Auth::check()) {
            return responseError([], "Unauthention", 401);
        }
        return response()->json(Auth::user(),200,['Access-Control-Allow-Origin' =>  '*']);
    }

    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $check = User::where('email', $request->email)->first();
        logger($check);
        if ($check) {
            logger("dangnhap");
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $token = JWTAuth::attempt($validator->validated());
                $user = User::findOrFail(Auth::id());

                return response()->json(['type' => 'success', 'token' => $token, 'user' => $user], 200);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            logger("dangky");
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            logger($user);
            logger(Auth::attempt(['email' => $request->email, 'password' => $request->password]),['auth attemp']);
            $token = JWTAuth::attempt($validator->validated());
            logger($token, ['auth token']);
            $user = User::findOrFail(Auth::id());

            return response()->json(['type' => 'success', 'token' => $token, 'user' => $user], 200);
        }
    }
}
