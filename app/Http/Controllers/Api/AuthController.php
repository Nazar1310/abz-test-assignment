<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    /**
     * @param AuthService $authService
     */
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!$token = $this->authService->login($request->email, $request->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'success' => true,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getToken(): JsonResponse
    {
        return response()->json([
            'token' => $this->authService->login('test@gmail.com', 'qwerty123'),
            'success' => true,
        ]);
    }
}
