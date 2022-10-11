<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api'], ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = JWTAuth::attempt($validator->validated());
            $user = User::findOrFail(Auth::id());
            
            return response()->json(['type' => 'success', 'token' => $token, 'user' => $user], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout()
    {
        if(Auth::check()) {
            return Auth::logout();
        }
    }

    public function register(UserRequest $request)
    {
       return User::create($request->only('email','name','password','address'));
    }
    public function getInfo()
    {
        return "login";
    }
}
