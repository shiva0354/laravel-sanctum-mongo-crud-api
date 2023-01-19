<?php

namespace App\Http\Controllers;

use App\Library\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|max:20'
        ]);

        $hasPassword = bcrypt($request->password);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => $hasPassword
            ]);

            $token = $user->createToken('api token')->plainTextToken;
            return ApiResponse::success(['token' => $token], 'User created successfully.');
        } catch (Exception $e) {
            return ApiResponse::exception($e);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|min:4|max:100',
            'password' => 'required|string|min:6|max:50'
        ]);

        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return ApiResponse::failed('Invalid email or password');
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('api token')->plainTextToken;
            return ApiResponse::success(['token' => $token], 'User logged in successfully.');
        } catch (Exception $e) {
            return ApiResponse::exception($e);
        }
    }
}
