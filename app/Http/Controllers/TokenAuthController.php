<?php

namespace App\Http\Controllers;

use App\Http\Requests\MobileLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TokenAuthController extends Controller
{
    public function login(MobileLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('$request->device')->plainTextToken
        ];
    }

    public function destroy(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        auth()->user()->deviceTokens()->delete();
        return response()->json();
    }
}