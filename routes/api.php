<?php

use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\TokenAuthController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/login', [TokenAuthController::class, 'login'])->middleware('guest');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('impersonate/{user}', [ImpersonateController::class, 'store']);
    Route::get('me', [ImpersonateController::class, 'show']);
    Route::delete('impersonate', [ImpersonateController::class, 'destroy']);
});