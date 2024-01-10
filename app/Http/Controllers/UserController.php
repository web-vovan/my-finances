<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Авторизация пользователя
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where([
            'login' => $request->login,
        ])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверный логин или пароль'],
            ]);
        }

        $token = $user->createToken('finance');

        return response()->json([
            'token' => $token->plainTextToken
        ]);
    }

    /**
     * Выход пользователя
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'result' => 'success'
        ]);
    }
}
