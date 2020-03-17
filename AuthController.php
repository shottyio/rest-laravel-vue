<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;

class AuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('jwt.auth')->only('logout', 'user');

        $this->user = $user;
    }

    public function register(CreateUserRequest $request)
    {
        $user = $this->user->create($request->all());

        $token = auth('api')->setTTL(43200)->login($user);

        return response()->json([
            'token' => $token
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->setTTL(43200)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token
        ]);
    }

    public function user()
    {
        $user = auth('api')->user();

        return response()->json($user);
    }

    public function index()
    {
        $users = $this->user->all();

        return response()->json($users);
    }

    public function logout()
    {
        $logout = auth('api')->logout();

        return response()->json(['message' => 'Log out']);
    }
}
