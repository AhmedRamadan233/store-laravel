<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
    //  */
    // public function store(LoginRequest $request): JsonResponse
    // {
    //     $request->authenticate();
    
    //     return response()->json(['message' => 'Login successful'], Response::HTTP_OK);
    // }
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = Auth::user(); // Get the authenticated user

        // Generate a new token for the user
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'token' => $token], Response::HTTP_OK);
    }
    public function destroy(): JsonResponse
    {
        Auth::guard('web')->logout();
        
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    
}