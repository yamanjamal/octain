<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class ImpersonateController extends Controller
{
    public function show()
    {
        return response()->json([
            'user' => auth()->user(),
        ], 200);
    }

    public function store(Request $request, User $user)
    {
        $impersonator = auth()->user();

        cache()->add('impersonator', [
            'impersonator_id' => $impersonator->id,
            'impersonator_name' => $impersonator->name,
            'impersonator_email' => $impersonator->email
        ]);

        $impersonator = auth()->user()->tokens()->delete();

        return response()->json([
            'impersonatee' => $user,
            'impersonatee_token' => $user->createToken('$request->device')->plainTextToken
        ], 200);
    }

    public function destroy()
    {
        $impersonatorId = cache()->get('impersonator')['impersonator_id'];
        $impersonator = User::find($impersonatorId);

        if (!$impersonator) {
            return response()->json([
                'message' => 'impersonator not found'
            ], 404);
        }

        cache()->forget('impersonator');
        $impersonatee = auth()->user()->tokens()->delete();

        return response()->json([
            'user' => $impersonator,
            'token' => $impersonator->createToken('$request->device')->plainTextToken
        ], 200);
    }
}