<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $token = auth('api')->attempt($credentials);

        if ($token === false) {
            return response()->json(['message' => 'Email atau password tidak valid.'], 401);
        }

        return $this->tokenResponse($token, auth('api')->user());
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json(['data' => $this->userData($user)]);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Berhasil keluar.']);
    }

    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();

        return $this->tokenResponse($token, auth('api')->user());
    }

    private function tokenResponse(string $token, ?User $user): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user === null ? null : $this->userData($user),
        ]);
    }

    /**
     * @return array{id: int, name: string, email: string, role: string}
     */
    private function userData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}
